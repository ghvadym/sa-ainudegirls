<?php
if (empty($term_id)) {
    return;
}

$fields = get_fields('term_'.$term_id);
$options = get_fields('options');
$seoData = $fields['seo_data'] ?? [];

if (empty($seoData)) {
    return;
}
?>

<section class="seo_data">
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
