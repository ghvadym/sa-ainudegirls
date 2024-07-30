<?php
/*
* Template name: AI Models
*/

get_header();

$postId = get_the_ID();
$numberposts = wp_is_mobile() ? 4 : POSTS_PER_PAGE;

$posts = _get_posts([
    'numberposts' => $numberposts
]);

if (empty($posts)) {
    echo sprintf('<div class="container"><h3>%s</h3></div>', __('There are no models', DOMAIN));
    return;
}
?>

<section class="archive">
    <div class="container-sm">
        <h1 class="title">
            <?php echo sprintf(
                '%s <span>%s</span>',
                __('List of', DOMAIN),
                __('Fanvue Accounts', DOMAIN)
            ); ?>
        </h1>
        <div class="search">
            <form role="search" method="get" id="search_form" class="search__form" action="http://fanvue.local/">
                <div class="search__form_body">
                    <input type="text" class="search__input" placeholder="<?php _e('Search', DOMAIN); ?>">
                    <button class="search__btn">
                        <?php get_svg('arrow-top'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="container">
        <div class="articles">
            <?php foreach ($posts as $post) {
                get_template_part_var('cards/card-post', [
                    'post' => $post
                ]);
            } ?>
        </div>
        <?php if ($posts > $numberposts) { ?>
            <div class="articles__btn">
                <span id="articles_load" class="btn" data-page="1">
                    <?php _e('Load more', DOMAIN); ?>
                </span>
            </div>
        <?php } ?>
    </div>
</section>

<?php

get_template_part_var('global/faq', [
    'faq_list' => get_field('faq', $postId)
]);

get_footer();