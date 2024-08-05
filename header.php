<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <script defer data-domain="ainudegirls.io" src="https://plausible.io/js/script.js"></script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header id="header" class="header">
    <div class="container">
        <div class="header__row">
            <div class="header__logo logo">
                <?php logo(); ?>
            </div>
            <div class="header__menu">
                <?php wp_nav_menu([
                    'theme_location' => 'main_header'
                ]); ?>
            </div>
            <div class="header__burger">
                <img src="<?php img_url('Burger.svg') ?>" alt="Burger" class="header_burger_icon">
                <img src="<?php img_url('Close.svg') ?>" alt="Close" class="header_close_icon">
            </div>
        </div>
    </div>
</header>
<main class="main">
    <?php if (!is_home() && !is_front_page() && !is_search()) { ?>
        <div class="container">
            <?php breadcrumbs(); ?>
        </div>
    <?php } ?>

    <?php
    $advLinksTop = get_field('adv_links_top', 'options');
    if (!empty($advLinksTop)) { ?>
        <section class="adv_buttons">
            <div class="container">
                <div class="adv_buttons__row">
                    <?php foreach ($advLinksTop as $advLink) {
                        $link = $advLink['link'] ?? []; ?>

                        <a href="<?php echo esc_url($link['url']); ?>"
                           target="<?php echo $link['target'] ?? '_self'; ?>" class="btn">
                            <?php echo $link['title'] ?? '';?>
                        </a>

                        <?php
                    } ?>
                </div>
            </div>
        </section>
    <?php } ?>
