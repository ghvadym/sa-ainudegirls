<?php
$notification = get_field('push_notification_wide', 'options');

if (!$notification) {
    return;
}

if (!empty($notification['hide']) || empty($notification['img'])) {
    return;
}

$img = wp_get_attachment_image($notification['img'], 'large');

if (!$img) {
    return;
}
?>

<div class="push_notification notification_wide <?php echo $notification['position'] ?? ''; ?>"
     id="notification-wide"
     data-delay="<?php echo $notification['delay'] ?? ''; ?>">
    <div class="push_notification__row">
        <?php if ($notification['url']) { ?>
            <a href="<?php echo esc_url($notification['url']); ?>" class="notification_wide__img">
                <?php echo $img; ?>
            </a>
        <?php } else { ?>
            <div class="notification_wide__img">
                <?php echo $img; ?>
            </div>
        <?php } ?>
        <div class="push_notification__body">
            <?php if (!empty($notification['title'])) { ?>
                <div class="push_notification__title">
                    <?php echo $notification['title']; ?>
                </div>
            <?php } ?>
            <?php if (!empty($notification['text'])) { ?>
                <div class="push_notification__text">
                    <?php echo $notification['text']; ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="close_btn">
        <?php get_svg('cross-white'); ?>
    </div>
</div>
