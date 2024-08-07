<?php

$posts = _get_posts([
    'numberposts' => POSTS_PER_PAGE
]);

if (empty($posts)) {
    return;
}
?>

<section class="top_accounts">
    <div class="container">
        <?php if (!empty($fields['models_title'])) { ?>
            <h2 class="title_main">
                <?php echo $fields['models_title']; ?>
            </h2>
        <?php } ?>
        <div class="articles">
            <?php foreach ($posts as $post) {
                get_template_part_var('cards/card-post', [
                    'post' => $post
                ]);
            } ?>
        </div>
        <?php if ($posts > POSTS_PER_PAGE) { ?>
            <div class="articles__btn">
                <span id="articles_load" class="btn" data-page="1">
                    <?php _e('Load more', DOMAIN); ?>
                </span>
            </div>
        <?php } ?>
    </div>
</section>
