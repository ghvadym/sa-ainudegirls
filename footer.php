<?php
wp_footer();

if (is_category() || is_tag()) {
    $term = get_queried_object();
    if (!empty($term->term_id)) {
        get_template_part_var('global/faq', [
            'faq_list' => get_field('faq', 'term_'.$term->term_id)
        ]);
    }
}

$socials = get_field('socials', 'options');
?>

</main>

<footer id="footer" class="footer">
    <div class="container">
        <div class="footer__body">
            <div class="footer__row">
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
                    <div class="footer__col footer__socials">
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
                <?php get_widgets([
                    'Footer nav 3',
                    'Footer nav 4',
                    'Footer nav 5',
                ]); ?>
                <?php if (!empty($socials)) { ?>
                    <div class="footer__col footer__socials">
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
