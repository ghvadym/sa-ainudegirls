<?php

$attr = [
    'show_all'           => false,
    'end_size'           => 1,
    'mid_size'           => 1,
    'prev_next'          => true,
    'prev_text'          => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 4L6 12L14 20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    'next_text'          => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 4L18 12L10 20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    'type'               => 'plain',
    'add_args'           => false,
    'before_page_number' => '',
    'after_page_number'  => ''
];

if (!empty($current)) {
    $attr['current'] = $current;
}

if (!empty($total)) {
    $attr['total'] = $total;
}

$pagination_output = paginate_links($attr);

if ($pagination_output != '') { ?>
    <div class="pagination-container">
        <nav class="pagination" aria-label="Page navigation">
            <?php echo $pagination_output; ?>
        </nav>
    </div>
<?php }