<?php

register_ajax([
    'load_posts',
    'faq_by_ai',
    'desc_by_ai'
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

    $modelFields = prepare_model_fields(get_post_meta($postId));
    $name = $modelFields['fanvue_name'] ?? '';

    if (empty($modelFields) || empty($name)) {
        wp_send_json([
            'error'   => true,
            'message' => 'There is no necessary Fanvue data, check fields'
        ]);

        return false;
    }

    $question = 'Describe me ' . $name;
    $keywords = "$name nude model";
    $wordCount = 75;
    $guide = "Include information about $name's identity, her professional activities, and why she's popular on Fanvue.";
    $promptBody = get_field('prompt_body', 'options');
    $answer = ask_question($modelFields, $question, $promptBody, $keywords, $wordCount, $guide);

    if ($answer) {
        update_field('fanvue_description', $answer, $postId);
    }

    wp_send_json_success();
}