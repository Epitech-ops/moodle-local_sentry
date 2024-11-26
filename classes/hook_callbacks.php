<?php

namespace local_sentry;

class hook_callbacks {

    /**
     * Runs before HTTP headers.
     *
     * @param \core\hook\output\before_standard_head_html_generation $hook
     */
    public static function before_standard_head_html_generation(\core\hook\output\before_standard_head_html_generation $hook): string {
        if(!empty(\local_sentry\sentry::get_config('tracking_javascript'))) {
            $hook->add_html(\local_sentry\sentry::get_js_loader_script_html());
        }
        return '';
    }

    /**
     * Runs before HTTP footers.
     *
     * @param \core\hook\output\before_footer_html_generation $hook
     */
    public static function before_footer_html_generation(\core\hook\output\before_footer_html_generation $hook): void {
        \local_sentry\sentry::finish_main_transaction();
    }
}