<?php

if (empty($post)) {
    return;
}
?>

<div class="metabox_ai">
    <div class="metabox_ai__row">
        <small class="faq_ai_text">
            <?php _e('The process may take a few minutes.', DOMAIN); ?><br>
            <?php echo $post->post_status !== 'publish' ? __('Publish the post beforehand.', DOMAIN) : ''; ?>
        </small>
        <div id="faq-ai-generate"
             class="components-button is-primary ai_btn"
             data-id="<?php echo $post->ID; ?>">
            <?php _e('Generate FAQ by AI', DOMAIN); ?>
        </div>
        <p class="faq_ai_error"></p>
    </div>

    <div class="metabox_ai__row">
        <small class="faq_ai_text">
            <?php echo $post->post_status !== 'publish' ? __('Publish the post beforehand.', DOMAIN) : ''; ?>
        </small>
        <div id="desc-ai-generate"
             class="components-button is-primary ai_btn"
             data-id="<?php echo $post->ID; ?>">
            <?php _e('Generate Description by AI', DOMAIN); ?>
        </div>
        <p class="faq_ai_error"></p>
    </div>
</div>
