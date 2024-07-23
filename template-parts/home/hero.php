<?php
if (empty($fields)) {
    return;
}

$imgUrl = !empty($fields['hero_img']) ? wp_get_attachment_image_url($fields['hero_img'], 'full') : '';
?>

<section class="hero_section">
    <div class="container">
        <div class="cta__row">
            <div class="cta__content">
                <?php if (!empty($fields['hero_title'])) { ?>
                    <h1 class="cta__title">
                        <?php echo $fields['hero_title']; ?>
                    </h1>
                <?php } ?>
                <?php if (!empty($fields['hero_text'])) { ?>
                    <div class="cta__subtitle">
                        <?php echo $fields['hero_text']; ?>
                    </div>
                <?php } ?>
                <?php echo link_html($fields['hero_link'] ?? [], 'cta__btn btn'); ?>
            </div>
            <?php if ($imgUrl) { ?>
                <div class="cta__img">
                    <img src="<?php echo esc_url($imgUrl); ?>" alt="Hero Image">
                </div>
            <?php } ?>
            <?php echo link_html($fields['hero_link'] ?? [], 'cta__btn btn'); ?>
        </div>
    </div>
</section>