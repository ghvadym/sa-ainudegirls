<?php
get_header();
?>

<section class="search">
    <div class="container">
        <div class="search__label">
            <?php _e('Search result for', DOMAIN); ?>:
        </div>
        <h1 class="search__title">
            <?php echo htmlspecialchars($_GET['s']); ?>
        </h1>
        <div class="archive__posts">
            <?php if (have_posts()) { ?>
                <div class="articles">
                    <?php while (have_posts()) { the_post();
                        get_template_part_var('cards/card-post', [
                            'post' => get_post(),
                        ]);
                    } ?>
                </div>
            <?php } else { ?>
                <h3 class="no-posts-message">
                    <?php _e('Sorry, no content matched your search criteria', DOMAIN); ?>
                </h3>
            <?php } ?>
        </div>
        <?php get_template_part_var('global/pagination'); ?>
    </div>
</section>

<?php
get_footer();

