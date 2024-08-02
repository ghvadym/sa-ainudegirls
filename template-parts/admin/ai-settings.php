<?php
$optionsFaq = get_field('faq_questions', 'options');
$posts = get_posts([
    'numberposts' => -1,
    'fields'      => 'ids',
    'meta_query'  => [
        'relation' => 'OR',
        [
            'key'     => 'faq',
            'compare' => 'NOT EXISTS'
        ],
        [
            'key'     => 'faq',
            'value'   => count($optionsFaq),
            'compare' => '<',
            'type'    => 'numeric'
        ]
    ],
]);
?>

<div class="ai-settings wrap">
    <h1>
        <?php _e('AI Settings', DOMAIN) ?>
    </h1>

    <h2>
        <?php _e('Generate FAQ Answers and Descriptions for Models', DOMAIN); ?>
    </h2>

    <?php if (!empty($posts)) { ?>
        <h3 id="count-of-models">
            <?php echo sprintf('<span>%s</span> Models don\'t have FAQ', count($posts)) ?>
        </h3>

        <div class="button button-primary" id="ai-tool-btn">
            <?php _e('Start Generating', DOMAIN); ?>
        </div>

        <div class="ai-results"></div>
        <div class="ai-error"></div>
    <?php } else { ?>
        <h3>
            <?php _e('All the Models have an FAQ', DOMAIN); ?>
        </h3>
    <?php } ?>

</div>