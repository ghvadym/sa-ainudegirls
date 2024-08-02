<section class="search">
    <div class="container-sm">
        <?php banner_field($fields['search_adv_banner'] ?? [], 'banner_full_width'); ?>
        <?php if (!empty($fields['search_title']) || !empty($fields['search_text'])) { ?>
            <div class="search__head">
                <?php if (!empty($fields['search_title'])) { ?>
                    <h2 class="search__title title">
                        <?php echo $fields['search_title']; ?>
                    </h2>
                <?php } ?>
                <?php if (!empty($fields['search_text'])) { ?>
                    <div class="search__subtitle subtitle">
                        <?php echo $fields['search_text']; ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <form role="search" method="get" id="searchform" class="search__form" action="<?php echo home_url(); ?>">
            <div class="search__form_body">
                <input type="text" value="" name="s" id="s" class="search__input" placeholder="<?php _e('Search', DOMAIN); ?>">
                <button class="search__btn">
                    <?php get_svg('arrow-top'); ?>
                </button>
            </div>
        </form>
        <?php if (!empty($fields['search_buttons'])) { ?>
            <div class="search__buttons">
                <?php foreach ($fields['search_buttons'] as $button) {
                    echo link_html($button['link'] ?? [], 'search__button btn');
                } ?>
            </div>
        <?php } ?>
    </div>
</section>