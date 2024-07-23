<?php
get_header();
$post = get_post();
$contents = get_field('content', $post->ID);
?>

<section class="page">
    <div class="container">
        <h1>
            <?php echo esc_html($post->post_title); ?>
        </h1>
        <?php if ($post->post_content) { ?>
            <div class="text_block_full">
                <?php the_content(); ?>
            </div>
        <?php } ?>
        <?php if (!empty($contents)) { ?>
            <div class="content_blocks">
                <?php foreach ($contents as $content) {
                    $imgId = $content['img'] ?? '';
                    $text = $content['text'] ?? '';

                    if ($imgId) {
                        $imgUrl = wp_get_attachment_image_url($imgId, 'large');
                        $label = get_post_meta($imgId, '_wp_attachment_image_alt', true);
                    }
                    ?>
                    <div class="content_block text_block">
                        <?php if ($text) { ?>
                            <div class="content_block__text">
                                <?php echo $text; ?>
                            </div>
                        <?php } ?>
                        <?php if (!empty($imgUrl)) { ?>
                            <div class="content_block__img">
                                <img src="<?php echo esc_url($imgUrl); ?>" alt="<?php echo $label ?? ''; ?>">
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</section>

<?php
get_footer();