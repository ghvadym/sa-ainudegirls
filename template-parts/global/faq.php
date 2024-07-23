<?php
if (empty($faq_list)) {
    return;
}
?>

<section class="faq_section">
    <div class="container-sm">
        <h2 class="title_main">
            <?php _e('FAQs', DOMAIN); ?>
        </h2>
        <div class="faq__list">
            <?php foreach ($faq_list as $faq_item) { ?>
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
            <?php } ?>
        </div>
    </div>
</section>

