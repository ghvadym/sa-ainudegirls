<?php

get_header();
$post = get_post();
$fields = get_fields($post->ID);
$options = get_fields('options');

$terms = get_the_terms($post, 'category');
?>

<section class="single_hero">
    <div class="container">
        <h1 class="single__title">
            <?php echo $post->post_title; ?>
        </h1>
        <?php adv_banner_group($fields['adv_banner_1'] ?? [], $options['adv_banner_1'] ?? [], 'banner_full_width'); ?>
    </div>
</section>

<section class="section_content">
    <div class="container">
        <div class="single__content">
            <div class="single__content_row">
                <div class="card">
                    <?php get_template_part_var('cards/card-model', [
                        'post'    => $post,
                        'fields'  => $fields,
                        'options' => $options
                    ]); ?>
                </div>
                <?php if (!empty($fields['main_info_text'])) { ?>
                    <div class="text_block">
                        <?php echo $fields['main_info_text']; ?>
                    </div>
                <?php } ?>
            </div>
            <?php if (!empty($terms)) { ?>
                <div class="tags">
                    <div class="tags__title">
                        <?php
                        if (!empty($fields['tags_title']) && !empty($fields['main_info_title'])) {
                            echo str_replace('[name]', $fields['main_info_title'], $fields['tags_title']);
                        } else {
                            echo sprintf('%1$s relevant categories:', $fields['main_info_title'] ?? $post->post_title);
                        }
                        ?>
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
</section>

<?php
get_template_part_var('global/faq', [
    'faq_list' => get_field('faq', $post->ID)
]);

get_footer();