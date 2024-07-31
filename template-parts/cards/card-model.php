<?php
if (empty($post) || empty($fields)) {
    return;
}

$thumbnail = get_the_post_thumbnail($post, 'large');

$fanvueData = [
    'like'   => 'fanvue_likes_count',
    'photos' => 'fanvue_photos_count',
    'videos' => 'fanvue_videos_count'
];
?>

<?php if ($thumbnail) { ?>
    <div class="card__img">
        <?php echo $thumbnail ?>
    </div>
<?php } ?>
<div class="card__body">
    <h1 class="card__title">
        <?php if (!empty($fields['main_info_title'])) {
            echo esc_html($fields['main_info_title']);
        } else {
            echo esc_html($post->post_title);
        } ?>
    </h1>

    <div class="card__social">
        <?php foreach ($fanvueData as $key => $field) {
            $socialValue = $fields[$field] ?? '';

            if (!$socialValue) {
                continue;
            }
            ?>
            <div class="card__social_item">
                <?php get_svg($key); ?>
                <?php echo $socialValue; ?>
            </div>
        <?php } ?>
    </div>

    <?php if (!empty($fields['fanvue_pricing'])) { ?>
        <div class="card__pricing">
            <?php echo sprintf('Pricing: <b>%1$s/month</b>', $fields['fanvue_pricing']); ?>
        </div>
    <?php } ?>

    <?php if (!empty($fields['fanvue_username'])) { ?>
        <a href="<?php echo esc_url(FANVUE_URL. $fields['fanvue_username'] . '/') ?>" class="card__btn btn_light">
            <?php get_svg('fanvue'); ?>
            <?php _e('Fanvue Profile', DOMAIN); ?>
        </a>
    <?php } ?>

    <?php if (!empty($fields['main_info_link'])) {
        echo link_html($fields['main_info_link'] ?? [], 'card__btn btn');
    } ?>
</div>
