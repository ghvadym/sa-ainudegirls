<?php
if (empty($fields)) {
    return;
}

$thumbnail = get_the_post_thumbnail($post, 'large');
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
    <?php if (!empty($fields['main_info_link'])) {
        echo link_html($fields['main_info_link'] ?? [], 'card__btn btn');
    }
    get_template_part_var('global/card-socials', [
        'socials' => $fields['socials'] ?? []
    ]); ?>
</div>
