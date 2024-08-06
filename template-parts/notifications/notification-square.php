<?php
$notification = get_field('push_notification_square', 'options');
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

<div class="push_notification notification_square <?php echo $notification['position'] ?? ''; ?>"
     id="notification-square"
     data-delay="<?php echo $notification['delay'] ?? ''; ?>">
    <?php if (empty($notification['link']['title'])) { ?>
        <a href="<?php echo $notification['link']['url'] ?? ''; ?>"
           target="<?php echo $notification['link']['target'] ?? '_self'; ?>"
           class="push_notification__body">
            <?php echo $img; ?>
            <?php if (!empty($notification['link'])) {
                echo link_html($notification['link'], 'push_notification__btn btn_light');
            } ?>
        </a>
    <?php } else { ?>
        <div class="push_notification__body">
            <?php echo $img; ?>
            <?php if (!empty($notification['link'])) {
                echo link_html($notification['link'], 'push_notification__btn btn_light');
            } ?>
        </div>
    <?php } ?>
    <div class="close_btn">
        <?php get_svg('cross-white'); ?>
    </div>
</div>
