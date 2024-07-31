<?php

register_ajax([
    'load_posts',
    'faq_by_ai'
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