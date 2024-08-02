<?php

register_ajax([
    'load_posts',
    'faq_by_ai',
    'desc_by_ai',
    'ai_tool'
]);

function load_posts()
{
    check_ajax_referer('posts-nonce', 'nonce');

    $data = sanitize_post($_POST);
    $page = $data['page'] ?? 1;
    $termId = $data['term'] ?? 0;
    $numberposts = POSTS_PER_PAGE;
    $offset = ($page - 1) * $numberposts;

    if (empty($data)) {
        wp_send_json_error('There is no data');
        return;
    }

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $numberposts,
        'paged'          => $page,
        'offset'         => $offset,
        'orderby'        => 'DATE',
        'order'          => 'DESC'
    ];

    if ($termId) {
        $args['tax_query'] = [
            'relation' => 'OR',
            [
                'taxonomy' => 'category',
                'field'    => 'id',
                'terms'    => [$termId]
            ],
            [
                'taxonomy' => 'post_tag',
                'field'    => 'id',
                'terms'    => [$termId]
            ]
        ];
    }

    $posts = new WP_Query($args);

    ob_start();

    if ($posts->have_posts()) {
        while ($posts->have_posts()) {
            $posts->the_post();
            get_template_part_var('cards/card-post', [
                'post' => $posts->post
            ]);
        }
    } else {
        echo '<h3 class="no-posts-message">' . __('Posts not found', DOMAIN) . '</h3>';
    }

    $html = ob_get_contents();
    ob_end_clean();

    wp_send_json([
        'posts'     => $html,
        'append'    => $page > 1,
        'count'     => count($posts->posts),
        'end_posts' => count($posts->posts) < $numberposts
    ]);
}

function faq_by_ai()
{
    check_ajax_referer('admin-nonce', 'nonce');

    $data = sanitize_post($_POST);

    if (empty($data)) {
        wp_send_json_error('There is no data');
        return;
    }

    $postId = $data['post_id'] ?? 0;
    $step = $data['step'] ?? 1;

    if (!$postId) {
        wp_send_json_error('There is no Post ID');
        return;
    }

    $result = faq_answers_generation($postId, $step);

    if (!$result) {
        return;
    }

    if ($result !== 'finish') {
        wp_send_json([
            'step' => $result
        ]);

        return;
    }

    wp_send_json_success();
}

function desc_by_ai()
{
    check_ajax_referer('admin-nonce', 'nonce');

    $data = sanitize_post($_POST);

    if (empty($data)) {
        wp_send_json_error('There is no data');
        return false;
    }

    $postId = $data['post_id'] ?? 0;

    if (!$postId) {
        wp_send_json_error('There is no Post ID');
        return false;
    }

    $descriptionUpdate = description_update($postId);

    if ($descriptionUpdate) {
        wp_send_json_success();
    } else {
        wp_send_json_error('Something went wrong');
    }
}

function ai_tool()
{
    check_ajax_referer('admin-nonce', 'nonce');

    $data = sanitize_post($_POST);

    if (empty($data)) {
        wp_send_json_error('There is no data');
        return;
    }

    $optionsFaq = get_field('faq_questions', 'options');

    if (empty($optionsFaq)) {
        wp_send_json([
            'error'   => true,
            'message' => 'There is no prepared questions. Fill out them first here: <a href="'.admin_url('admin.php?page=theme-general-settings').'" target="_blank">Options</a>'
        ]);

        return;
    }

    $posts = get_posts([
        'numberposts' => -1,
        'fields'      => 'ids',
        'meta_query'  => [
            'relation' => 'OR',
            [
                'key'     => 'faq',
                'compare' => 'NOT EXISTS'
            ],
            [
                'key'     => 'faq',
                'value'   => count($optionsFaq),
                'compare' => '<',
                'type'    => 'numeric'
            ]
        ],
    ]);

    if (empty($posts)) {
        wp_send_json([
            'success' => true,
            'finish'  => true,
            'message' => __('FAQ for all posts are generated', DOMAIN)
        ]);

        return;
    }

    $postId = $posts[0] ?? 0;

    if (!$postId) {
        wp_send_json([
            'error'   => true,
            'message' => 'There are no Post ID'
        ]);

        return;
    }

    if (!get_post_meta($postId, 'fanvue_description_updated', true)) {
        $descriptionUpdate = description_update($postId);

        if ($descriptionUpdate) {
            wp_send_json([
                'success'      => true,
                'post_id'      => $postId,
                'desc_updated' => true,
                'message'      => '<p><a href="' . get_edit_post_link($postId) . '" target="_blank">' . get_the_title($postId) . '</a> - Description updated</p>'
            ]);

            return;
        } else {
            wp_send_json([
                'error'   => true,
                'message' => 'Description was not updated'
            ]);

            return;
        }
    }

    $modelFields = prepare_model_fields(get_post_meta($postId));
    $name = $modelFields['fanvue_name'] ?? '';

    if (empty($modelFields) || empty($name)) {
        wp_send_json([
            'error'   => true,
            'message' => 'There is no necessary Fanvue data, check fields'
        ]);

        return;
    }

    $question = '';
    $postIndex = 0;
    $postFaqs = acf_repeater($postId, 'faq', ['title', 'text']);
    $postFaqsTitles = array_map('trim', array_column($postFaqs, 'title'));

    if (empty($postFaqs)) {
        $question = model_fields_replacement($optionsFaq[0]['question'], $modelFields);
    } else {
        foreach ($optionsFaq as $index => $optionFaq) {
            $optionsFaqQuestion = $optionFaq['question'] ?? '';
            if (!$optionsFaqQuestion) {
                continue;
            }

            $optionsFaqQuestion = model_fields_replacement($optionsFaqQuestion, $modelFields);

            if (!in_array(trim($optionsFaqQuestion), $postFaqsTitles)) {
                $question = $optionsFaqQuestion;
                $postIndex = $index;
                break;
            }

            /* If title exists in faq post */
            $faqTitlesIndex = array_search($optionsFaqQuestion, $postFaqsTitles);
            $faqText = $postFaqs[$faqTitlesIndex]['text'] ?? '';

            /* If title exists but an answer is not exists */
            if (!$faqText) {
                $question = $optionsFaqQuestion;
                $postIndex = $index;
                break;
            }
        }
    }

    $keywords = "$name nude model";
    $wordCount = 75;
    $guide = "Include information about $name's identity, her professional activities, and why she's popular on Fanvue.";
    $promptBody = get_field('prompt_body', 'options');
    $answer = ask_question($modelFields, $question, $promptBody, $keywords, $wordCount, $guide);

    if ($answer) {
        faq_update($postId, $question, $answer);

        $responseArgs = [
            'success'        => true,
            'post_id'        => $postId,
            'question_index' => $postIndex
        ];

        if (($postIndex + 1) === count($optionsFaq)) {
            $responseArgs['post_finished'] = true;
            $responseArgs['message'] = '<p><a href="'.get_edit_post_link($postId).'" target="_blank">'.get_the_title($postId).'</a> - FAQ is Ready</p>';
            $responseArgs['count_models'] = $posts > 1 ? count($posts) - 1 : 0;
        }

        wp_send_json($responseArgs);
    } else {
        wp_send_json([
            'error' => true
        ]);
    }
}