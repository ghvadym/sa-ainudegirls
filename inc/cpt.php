<?php

add_action('init', 'create_post_types');

function create_post_types()
{
    create_post_type('social', [
        'publicly_queryable' => false,
        'menu_icon'          => 'dashicons-share',
        'supports'           => ['title', 'thumbnail'],
        'labels'             => [
            'name'          => __('Socials', DOMAIN),
            'singular_name' => __('Social', DOMAIN),
            'view_item'     => __('View Social', DOMAIN),
            'menu_name'     => __('Social', DOMAIN)
        ]
    ]);
}


function create_post_type($post_type, $args = [])
{
    $args = array_merge([
        'public'        => true,
        'show_ui'       => true,
        'has_archive'   => true,
        'menu_position' => 20,
        'hierarchical'  => true,
        'menu_icon'     => 'dashicons-admin-post',
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
        'labels'        => [
            'name'               => __('Posts', DOMAIN),
            'singular_name'      => __('Posts', DOMAIN),
            'add_new'            => __('Add New Post', DOMAIN),
            'add_new_item'       => __('Add New Post', DOMAIN),
            'edit_item'          => __('Editing Post', DOMAIN),
            'new_item'           => __('New Post', DOMAIN),
            'view_item'          => __('View Post', DOMAIN),
            'search_items'       => __('Find Post', DOMAIN),
            'not_found'          => __('Post isn\'t found', DOMAIN),
            'not_found_in_trash' => __('Post isn\'t found in trash', DOMAIN),
            'parent_item_colon'  => '',
            'menu_name'          => __('Posts', DOMAIN)
        ]
    ], $args);

    register_post_type($post_type, $args);
}

function create_taxonomy($taxonomy, $post_type, $args = [])
{
    $args = array_merge([
        'description'  => '',
        'public'       => true,
        'hierarchical' => true,
        'has_archive'  => true
    ], $args);

    register_taxonomy($taxonomy, $post_type, $args);
}