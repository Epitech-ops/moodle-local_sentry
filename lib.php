<?php

// This file is part of the Sentry plugin for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 2 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * @package    local_sentry
 * @copyright  2023, ARNES
 */
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/classes/sentry.php');

function local_sentry_before_session_start() {
	global $CFG, $DB;
	\local_sentry\sentry::setup();
	\local_sentry\sentry::start_main_transaction();
}

function local_sentry_before_footer() {
	\local_sentry\sentry::finish_main_transaction();
}

/**
 * Serve the Sentry loader with defined parameters
 * when requested.
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function local_sentry_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
	return \local_sentry\sentry::pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, $options);
}

/**
 * Add Sentry loader Javascript to the HTML head.
 */
function local_sentry_before_standard_html_head() {
	if(!empty(\local_sentry\sentry::get_config('tracking_javascript'))) {
		return \local_sentry\sentry::get_js_loader_script_html();
	}
	return '';
}