<?php

const DOMAIN = 'ainudegirls';
const FANVUE_URL = 'https://fanvue.com/';

if (!defined('POSTS_PER_PAGE')) {
    define('POSTS_PER_PAGE', get_option('posts_per_page') ?: 16);
}

$files = [
    'helper',
    'custom_functions',
    'api',
    'cpt',
    'sidebar',
    'setup',
    'ajax'
];

foreach ($files as $file) {
    require_once("$file.php");
}