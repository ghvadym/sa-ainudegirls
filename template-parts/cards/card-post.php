<?php
if (empty($post)) {
    return;
}

$thumbnail = get_thumbnail_html($post->ID, $post->post_title, 'large');
?>

<div class="article">
    <div class="article__body">
        <a href="<?php echo get_the_permalink($post); ?>"
           class="article__img">
            <?php echo $thumbnail; ?>
        </a>
        <div class="article__content">
            <h3 class="article__title">
                <a href="<?php echo get_the_permalink($post); ?>">
                    <?php echo esc_html($post->post_title); ?>
                </a>
            </h3>
        </div>
    </div>
</div>
