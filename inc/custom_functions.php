<?php

function breadcrumbs()
{
    if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<p id="breadcrumbs" class="breadcrumbs">', '</p>');
    }
}

function logo()
{
    if (function_exists('the_custom_logo') && has_custom_logo()) {
        the_custom_logo();
    }

    if (!is_home() && !is_front_page()) { ?>
        <a class="logo__title" href="<?php echo esc_url(home_url()); ?>">
            <?php echo get_bloginfo('name'); ?>
        </a>
    <?php } else { ?>
        <span class="logo__title">
            <?php echo get_bloginfo('name'); ?>
        </span>
    <?php }
}

function api_request(array $args = [])
{
    if (empty($args)) {
        return [];
    }

    $curlUrl = $args['curl_url'] ?? '';
    $method = $args['method'] ?? 'GET';
    $postData = $args['data'] ?? [];
    $header = $args['headers'] ?? [];

    $curl = curl_init();

    $params = [
        CURLOPT_URL            => $curlUrl,
        CURLINFO_HEADER_OUT    => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $header,
        CURLOPT_CUSTOMREQUEST  => $method
    ];

    if ($method !== 'GET' && !empty($postData)) {
        $params[CURLOPT_POSTFIELDS] = json_encode($postData);
    }

    curl_setopt_array($curl, $params);

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode($response);
}