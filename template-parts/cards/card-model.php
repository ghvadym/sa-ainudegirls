<?php

if (empty($post) || empty($fields)) {
    return;
}

$thumbnail = get_the_post_thumbnail($post, 'large');
$permalink = get_the_permalink($post);

$fanvueData = [
    'like'   => 'fanvue_likes_count',
    'photos' => 'fanvue_photos_count',
    'videos' => 'fanvue_videos_count'
];
?>

<div class="card">
    <?php if ($thumbnail) { ?>
        <?php if (!empty($gallery)) { ?>
            <a class="card__img" href="<?php echo $permalink; ?>">
                <?php echo $thumbnail ?>
            </a>
        <?php } else { ?>
            <div class="card__img">
                <?php echo $thumbnail ?>
            </div>
        <?php } ?>
    <?php } ?>
    <div class="card__body">
        <h3 class="card__title">
            <?php if (!empty($gallery)) { ?>
                <a href="<?php echo $permalink; ?>">
                    <?php if (!empty($fields['fanvue_name'])) {
                        echo esc_html($fields['fanvue_name'][0]);
                    } else {
                        echo esc_html($post->post_title);
                    } ?>
                </a>
            <?php } else {
                if (!empty($fields['fanvue_name'])) {
                    echo esc_html($fields['fanvue_name'][0]);
                } else {
                    echo esc_html($post->post_title);
                }
            } ?>
        </h3>

        <div class="card__social">
            <?php foreach ($fanvueData as $key => $field) {
                $socialValue = post_meta_field($fields[$field] ?? []);

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
                <?php echo sprintf('Pricing: <b>%1$s/month</b>', $fields['fanvue_pricing'][0]); ?>
            </div>
        <?php } ?>

        <?php if (!empty($fields['fanvue_username'])) { ?>
            <a href="<?php echo esc_url(FANVUE_URL. $fields['fanvue_username'][0] . '/') ?>" class="card__btn btn_light" target="_blank">
                <?php get_svg('fanvue'); ?>
                <?php _e('Fanvue Profile', DOMAIN); ?>
            </a>
        <?php } ?>

        <?php
        if (empty($gallery) && !empty($options['adv_links'])) {
            foreach ($options['adv_links'] as $advLink) {
                $link = $advLink['link'] ?? [];
                $imgUrl = $advLink['img'] ?? '';
                $bgTransparent = $advLink['transparent'] ?? false;
                $btnClasses = 'card__btn ';
                $btnClasses .= $bgTransparent ? 'btn_light' : 'btn';
                ?>

                <a href="<?php echo esc_url($link['url']); ?>"
                   target="<?php echo $link['target'] ?? '_self'; ?>"
                   class="<?php echo $btnClasses; ?>">
                    <?php if ($imgUrl) { ?>
                        <img src="<?php echo esc_url($imgUrl); ?>" alt="Button image">
                    <?php } else {
                        echo $link['title'] ?? __('Undress AI', DOMAIN);
                    } ?>
                </a>

                <?php
            }
        } ?>
    </div>
</div>