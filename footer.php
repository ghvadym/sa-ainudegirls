<?php
wp_footer();

if (is_category() || is_tag()) {
    $term = get_queried_object();
    if (!empty($term->term_id)) {
        get_template_part_var('global/archive-seo-text', [
            'term_id' => $term->term_id
        ]);
    }
}

$socials = get_field('socials', 'options');
?>

</main>

<footer id="footer" class="footer">
    <div class="container">
        <div class="footer__body">
            <div class="footer__row footer__row_main">
                <div class="footer__col">
                    <div class="footer__content">
                        <div class="footer__logo logo">
                            <?php logo(); ?>
                        </div>
                        <?php if ($footer_text = get_field('footer_text', 'options')) { ?>
                            <div class="footer__text">
                                <?php echo $footer_text; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php get_widgets([
                    'Footer nav 1',
                    'Footer nav 2',
                ]); ?>
                <?php if (!empty($socials)) { ?>
                    <div class="footer__col footer__socials desktop">
                        <div class="socials__title">
                            <?php _e('Follow us', DOMAIN); ?>
                        </div>
                        <?php get_template_part_var('global/socials', [
                            'socials' => $socials
                        ]); ?>
                    </div>
                <?php } ?>
            </div>
            <div class="footer__row">
                <h3 class="footer__title">
                    <?php _e('Categories', DOMAIN); ?>
                </h3>
                <div class="footer__menu">
                    <?php get_widgets([
                        'Footer nav 3'
                    ]); ?>
                </div>
            </div>
            <div class="footer__row">
                <h3 class="footer__title">
                    <?php _e('Nude AI Girls', DOMAIN); ?>
                </h3>
                <div class="footer__menu">
                    <?php get_widgets([
                        'Footer nav 4'
                    ]); ?>
                </div>
                <?php if (!empty($socials)) { ?>
                    <div class="footer__col footer__socials mobile">
                        <?php get_template_part_var('global/socials', [
                            'socials' => $socials
                        ]); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
