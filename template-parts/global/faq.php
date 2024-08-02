<?php
if (empty($faq_list)) {
    return;
}
?>

<section class="faq_section">
    <div class="container-sm">
        <?php banner_field($banner ?? [], 'banner_full_width'); ?>
        <h2 class="title_main">
            <?php _e('FAQ', DOMAIN); ?>
        </h2>
    </div>
    <div class="container">
        <?php adv_banner_group($fields['adv_banner_2'] ?? [], $options['adv_banner_2'] ?? [], 'banner_full_width'); ?>
    </div>
    <div class="container-sm">
        <div class="faq__list">
            <?php foreach ($faq_list as $i => $faq_item) { ?>
                <div class="faq__item">
                    <?php if ($faq_item['title']) { ?>
                        <h3 class="faq__title">
                            <?php echo esc_html($faq_item['title']); ?>
                        </h3>
                    <?php } ?>
                    <?php if ($faq_item['text']) { ?>
                        <div class="faq__text">
                            <?php echo $faq_item['text']; ?>
                        </div>
                    <?php } ?>
                </div>
                <?php
                if ($i === 2) {
                    adv_banner_group($fields['adv_banner_3'] ?? [], $options['adv_banner_3'] ?? [], 'banner_full_width');
                } else if ($i === 6) {
                    adv_banner_group($fields['adv_banner_4'] ?? [], $options['adv_banner_4'] ?? [], 'banner_full_width');
                } else if ($i === 10) {
                    adv_banner_group($fields['adv_banner_5'] ?? [], $options['adv_banner_5'] ?? [], 'banner_full_width');
                }
                ?>
            <?php } ?>
        </div>
    </div>
</section>

