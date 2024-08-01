<?php

add_filter('show_admin_bar', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');

add_action('wp_enqueue_scripts', 'wp_enqueue_scripts_call');
function wp_enqueue_scripts_call()
{
    wp_enqueue_style('main-style', FV_THEME_URL . '/dest/css/app-style.css');
    wp_enqueue_script('main-scripts', FV_THEME_URL . '/dest/js/app-scripts.js', ['jquery'], time());

    wp_localize_script('main-scripts', 'fvajax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('posts-nonce')
    ]);

    if (is_home() || is_front_page()) {
        wp_enqueue_style('home-styles', FV_THEME_URL . '/dest/css/home.css');
    }

    if (is_archive() || is_tax() || is_tag()) {
        wp_enqueue_style('archive-styles', FV_THEME_URL . '/dest/css/archive.css');
    }

    if (is_single()) {
        wp_enqueue_style('single-style', FV_THEME_URL . '/dest/css/single-post.css');
    }
}

add_action('admin_enqueue_scripts', 'admin_scripts_call');
function admin_scripts_call()
{
    wp_enqueue_style('post-custom-styles', FV_THEME_URL . '/dest/css/admin-styles.css');
    wp_enqueue_script('post-custom-scripts', FV_THEME_URL . '/dest/js/admin-scripts.js');

    wp_localize_script('post-custom-scripts', 'adminajax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('admin-nonce')
    ]);
}

add_action('after_setup_theme', 'after_setup_theme_call');
function after_setup_theme_call()
{
    register_nav_menus([
        'main_header' => __('Main Header', DOMAIN)
    ]);

    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'unlink-homepage-logo' => true
    ]);

    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => 'Options',
            'menu_title' => 'Options',
            'menu_slug'  => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect'   => false,
        ]);
    }
}

add_action('admin_menu', 'remove_default_post_types');
function remove_default_post_types()
{
    remove_menu_page('edit-comments.php');
}

add_filter('upload_mimes', 'upload_mimes_types');
function upload_mimes_types($types)
{
    $types['svg'] = 'image/svg+xml';
    $types['webp'] = 'image/webp';

    return $types;
}

add_filter('wpseo_breadcrumb_separator', 'wpseo_breadcrumb_separator_call', 10, 1);
function wpseo_breadcrumb_separator_call($separator) {
    return '<svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.60646 0.912893L6.55424 4.51989C6.69555 4.64873 6.80765 4.80177 6.88414 4.97024C6.96063 5.13872 7 5.31932 7 5.50172C7 5.68411 6.96063 5.86472 6.88414 6.03319C6.80765 6.20167 6.69555 6.3547 6.55424 6.48354L2.60646 10.0905C1.64618 10.9679 9.00529e-07 10.3412 7.92171e-07 9.10175L0 1.88776C-1.08358e-07 0.648286 1.64618 0.0355155 2.60646 0.912893Z" fill="#564C4C"/></svg>';
}

add_action('init', 'disable_emojis');
function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
    add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);
}

function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, ['wpemoji']);
    } else {
        return [];
    }
}

function disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
    if ('dns-prefetch' == $relation_type) {
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

        $urls = array_diff($urls, [$emoji_svg_url]);
    }

    return $urls;
}

add_action('add_meta_boxes', 'register_meta_boxes_call');
function register_meta_boxes_call()
{
    add_meta_box(
        'faq-fields',
        __('FAQ By AI', DOMAIN),
        'faq_metabox_call',
        'post',
        'side',
        'default'
    );
}

function faq_metabox_call($post)
{
    get_template_part_var('admin/metabox-ai', [
        'post' => $post
    ]);
}

add_filter('wp_nav_menu_items', 'add_custom', 10, 2);
function add_custom($items, $args) {
    if (wp_is_mobile()) {
        return $items;
    }

    $footerMenuNames = [
        'Footer menu 3',
        'Footer menu 4',
        'Footer menu 5',
        'Footer menu 6',
        'Footer menu 7',
        'Footer menu 8',
        'Footer menu 9',
        'Footer menu 10'
    ];

    if (in_array($args->menu->name, $footerMenuNames) && $args->menu->count > 4) {
        $items .= '<li class="menu_load_more" data-title="'.__('View Less', DOMAIN).'">'.__('View More', DOMAIN).'</li>';
    }

    return $items;
}

add_filter('manage_post_posts_columns', 'manage_posts_columns_call');
function manage_posts_columns_call($columns)
{
    return array_merge($columns, [
        'ai_faq' => 'FAQ'
    ]);
}

add_action('manage_posts_custom_column', 'action_custom_columns_content', 10, 2);
function action_custom_columns_content($columnId, $postId)
{
    $fields = [
        'ai_faq' => 'faq'
    ];

    $faqOptions = get_field('faq_questions', 'options');
    $faqOptionsCount = count($faqOptions);

    foreach ($fields as $key => $field) {
        if ($columnId !== $key) {
            continue;
        }

        $faqCount = get_post_meta($postId, 'faq', true);

        if ($faqOptionsCount == $faqCount) {
            echo '<i class="subscribers_status status_true" title="FAQ done"></i>';
            return;
        }

        if (!$faqCount) {
            echo '<i class="subscribers_status status_false" title="FAQ is empty"></i>';
            return;
        }

        echo '<i class="subscribers_status status_empty" title="FAQ is not fully done"></i>';
    }
}

add_action('admin_menu', 'admin_menu_call');
function admin_menu_call()
{
    add_submenu_page(
        'options-general.php',
        __('FAQ By AI', DOMAIN),
        __('FAQ By AI', DOMAIN),
        'manage_options',
        'ai-settings',
        'admin_ai_settings'
    );
}

function admin_ai_settings()
{
    get_template_part_var('admin/ai-settings');
}