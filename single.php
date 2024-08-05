<?php

get_header();
$post = get_post();
$fields = get_post_meta($post->ID);
$options = get_fields('options');
$terms = get_the_terms($post, 'category');

$morePosts = get_posts([
    'numberposts' => 3,
    'post__not_in' => [$post->ID],
    'category__in' => array_column($terms, 'term_id')
]);
?>

<?php if (!empty($options['adv_links_top'])) { ?>
    <section class="adv_buttons">
        <div class="container">
            <div class="adv_buttons__row">
                <?php foreach ($options['adv_links_top'] as $advLink) {
                    $link = $advLink['link'] ?? []; ?>

                    <a href="<?php echo esc_url($link['url']); ?>"
                       target="<?php echo $link['target'] ?? '_self'; ?>" class="btn">
                        <?php echo $link['title'] ?? '';?>
                    </a>

                    <?php
                } ?>
            </div>
        </div>
    </section>
<?php } ?>

<section class="single_hero">
    <div class="container">
        <h1 class="single__title">
            <?php echo $post->post_title; ?>
        </h1>
        <?php adv_banner_group(post_meta_field($fields['adv_banner_1'] ?? []), $options['adv_banner_1'] ?? [], 'banner_full_width'); ?>
    </div>
</section>

<section class="section_content">
    <div class="container">
        <div class="single__content">
            <div class="single__content_row">
                <?php get_template_part_var('cards/card-model', [
                    'post'    => $post,
                    'fields'  => $fields,
                    'options' => $options
                ]); ?>
                <div class="single__content_body">
                    <?php if (!empty($fields['fanvue_description'])) { ?>
                        <div class="text_block">
                            <?php echo $fields['fanvue_description'][0]; ?>
                        </div>
                    <?php } else { ?>
                        <div class="text_block">
                            <?php echo apply_filters('the_content', $post->post_content); ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($terms)) { ?>
                        <div class="tags">
                            <div class="tags__title">
                                <?php echo sprintf('%1$s relevant categories:', post_meta_field($fields['fanvue_name']) ?: $post->post_title); ?>
                            </div>
                            <div class="tags__list">
                                <?php foreach ($terms as $term) { ?>
                                    <a href="<?php echo get_term_link($term, $term->taxonomy); ?>" class="tags__item">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_template_part_var('global/faq', [
    'faq_list' => acf_repeater($post->ID, 'faq', ['title', 'text']),
    'fields'   => $fields,
    'options'  => $options
]); ?>

<?php if (!empty($morePosts)) { ?>
    <section class="more_posts">
        <div class="container">
            <h2 class="title_main">
                <?php _e('More models', DOMAIN); ?>
            </h2>
            <div class="more_posts__list">
                <?php foreach ($morePosts as $morePost) { ?>
                    <div class="more_posts__item">
                        <?php get_template_part_var('cards/card-model', [
                            'post'    => $morePost,
                            'fields'  => get_post_meta($morePost->ID),
                            'options' => $options,
                            'gallery' => true
                        ]); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>

<?php
get_footer();