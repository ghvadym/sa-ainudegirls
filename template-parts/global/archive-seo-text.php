<?php
if (empty($term_id)) {
    return;
}

$seoData = get_field('seo_data', 'term_'.$term_id);

if (empty($seoData)) {
    return;
}
?>

<section class="seo_data">
    <div class="container-sm">
        <?php banner_field(get_field('archive_faq_adv_banner', 'options'), 'banner_full_width'); ?>
    </div>
    <div class="container">
        <?php if (!empty($seoData['title'])) { ?>
            <h2 class="title_main seo_data__title">
                <?php echo $seoData['title']; ?>
            </h2>
        <?php } ?>
        <?php if (!empty($seoData['text'])) { ?>
            <div class="text_block seo_data__text">
                <?php echo $seoData['text']; ?>
            </div>
        <?php } ?>
    </div>
</section>
