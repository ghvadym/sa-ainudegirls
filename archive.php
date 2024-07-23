<?php
get_header();
$term = get_queried_object();
?>

    <section class="archive">
        <div class="container">
            <h1 class="archive__title title_main">
                <?php echo $term->name; ?>
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
                    <h3 class="posts-none">
                        <?php _e('Sorry, no content matched your search criteria', DOMAIN); ?>
                    </h3>
                <?php } ?>
            </div>
            <?php get_template_part_var('global/pagination'); ?>
        </div>
    </section>

<?php
get_footer();

