<?php

function breadcrumbs()
{
    if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<p id="breadcrumbs" class="breadcrumbs">', '</p>');
    }
}

function logo()
{
    if (function_exists('the_custom_logo') && has_custom_logo()) {
        the_custom_logo();
    }

    if (!is_home() && !is_front_page()) { ?>
        <a class="logo__title" href="<?php echo esc_url(home_url()); ?>">
            <?php echo get_bloginfo('name'); ?>
        </a>
    <?php } else { ?>
        <span class="logo__title">
            <?php echo get_bloginfo('name'); ?>
        </span>
    <?php }
}