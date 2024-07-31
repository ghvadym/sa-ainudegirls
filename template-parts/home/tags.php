<?php
$terms = get_terms([
    'taxonomy'   => ['category', 'post_tag'],
    'orderby'    => 'count',
    'order'      => 'desc',
    'hide_empty' => true
]);

if (empty($terms)) {
    return;
}
?>

<section class="tags">
    <div class="container">
        <?php if (!empty($fields['tags_title'])) { ?>
            <h2 class="title_main">
                <?php echo $fields['tags_title']; ?>
            </h2>
        <?php } ?>
        <div class="tags__list">
            <?php foreach ($terms as $term) { ?>
                <a href="<?php echo get_term_link($term, $term->taxonomy); ?>" class="tags__item">
                    <?php echo esc_html($term->name); ?>
                </a>
            <?php } ?>
        </div>
    </div>
</section>
