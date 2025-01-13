<?php

defined('MOODLE_INTERNAL') || die();

$callbacks = [
    [
        'hook' => \core\hook\output\before_standard_head_html_generation::class,
        'callback' => '\local_sentry\hook_callbacks::before_standard_head_html_generation',
        'priority' => 0,
    ],
    [
        'hook' => \core\hook\output\before_footer_html_generation::class,
        'callback' => '\local_sentry\hook_callbacks::before_footer_html_generation',
        'priority' => 0,
    ],
];