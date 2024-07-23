<?php
if (empty($socials)) {
    return;
}
?>

<div class="socials">
    <?php foreach ($socials as $social) {
        $image_url = $social['img'] ?? '';
        $link = $social['link'] ?? '';

        if (!$image_url || !$link) {
            continue;
        }
        ?>
        <div class="social__item">
            <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener nofollow">
                <img src="<?php echo esc_url($image_url); ?>" alt="Social">
            </a>
        </div>
    <?php } ?>
</div>
