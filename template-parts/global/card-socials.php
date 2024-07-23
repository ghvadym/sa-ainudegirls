<?php
if (empty($socials)) {
    return;
}
?>

<div class="card__socials">
    <?php foreach ($socials as $social) {
        $socialLink = $social['link'] ?? '';
        $socialId = $social['icon'] ?? '';

        if (empty($socialId[0]) || !$socialLink) {
            continue;
        }

        $socialId = $socialId[0];
        $thumbnailUrl = has_post_thumbnail($socialId) ? get_the_post_thumbnail_url($socialId) : '';

        if (!$thumbnailUrl) {
            continue;
        }
        ?>
        <div class="card__social">
            <a href="<?php echo esc_url($socialLink); ?>" target="_blank" rel="noreferrer noopener">
                <img src="<?php echo esc_url($thumbnailUrl); ?>" alt="Social Icon image">
            </a>
        </div>
    <?php } ?>
</div>
