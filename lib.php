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

require_once(__DIR__.'/sentry_moodle_database.php');

function local_sentry_exception_handler($ex) {
	if(function_exists('\Sentry\captureException')) {
		\Sentry\captureException($ex);
	}
	// Use the default exception handler afterwards
	default_exception_handler($ex);
}

function local_sentry_init_sentry() {
	if(!function_exists('\Sentry\init')) {
		return;
	}

	// $CFG->sentry_autoload_path = "/srv/composer/vendor/autoload.php";
	// $CFG->sentry_dsn = 'https://id-string@sentry.server.si/project-id';
	// $CFG->sentry_tracing_hosts = ['moodle.server.si'];
	if(!defined('MDL_SENTRY_INITIALIZED')) {
    define('MDL_SENTRY_INITIALIZED', true);
		if(file_exists($CFG->sentry_autoload_path)) {
				$options = [
          'dsn' => $CFG->sentry_dsn,
          'environment' => 'testing',
          'release' => '123919',
          'sample_rate' => 1.0,
          'traces_sample_rate' => 1.0,
				];
				if(local_sentry_tracing_enabled()) {
						$options['sample_rate'] = 1.0;
						$options['traces_sample_rate'] => 1.0;
				}
        require $CFG->sentry_autoload_path;
        \Sentry\init($options);
    }
	}
}

function local_sentry_tracing_enabled() {
	global $CFG;
	return !empty($CFG->sentry_tracing_hosts) && in_array($_SERVER['SERVER_NAME'], $CFG->sentry_tracing_hosts);
}

function local_sentry_start_main_transaction() {
	global $sentry_transaction;

	if(!function_exists('\Sentry\startTransaction')) {
		// Sentry not loaded in config.php
		return;
	}

	$uri = $_SERVER['REQUEST_URI'];
	$uri = empty($uri) ? 'UNKNOWN' : $uri;
	$transactionContext = new \Sentry\Tracing\TransactionContext(
		$name = $uri,
		$parentSampled = false
	);
	$transactionContext->setSampled(true);

	$sentry_transaction = \Sentry\startTransaction($transactionContext);
	return $sentry_transaction;
}

function local_sentry_before_session_start() {
	global $CFG, $DB;
	if(!function_exists('\Sentry\captureException')) {
		// Sentry not loaded in config.php
		return;
	}
	set_exception_handler('local_sentry_exception_handler');
	
	if(!local_sentry_tracing_enabled()) {
		return;
	}

	$DB = new sentry_moodle_database(false, $DB);
	local_sentry_start_main_transaction();
}

function local_sentry_before_footer() {
	global $sentry_transaction;
	if(!empty($sentry_transaction)) {
		error_log('sentry traced the exception');
		$sentry_transaction->finish();
	}
}
