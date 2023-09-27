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
 * @package    local_arnes_sentry
 * @copyright  2023, ARNES
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

$string['pluginname'] = 'Sentry Tracking';
$string['menuname'] = 'Sentry Tracking';

// Settings
$string['adminmenutitle'] = 'Sentry Tracking Settings';
$string['managesentry'] = 'Manage Sentry';
$string['autoload_path'] = 'Autoload path';
$string['autoload_path_desc'] = 'Path to the Composer autoload file, eg. <i>/srv/composer/vendor/autoload.php</i>';
$string['environment'] = 'Environment';
$string['environment_desc'] = 'Name of this environment, as to be tracked in Sentry';
$string['release'] = 'Release';
$string['release_desc'] = 'Current build/release number to be tracked in Sentry';
$string['tracking_javascript'] = 'Track Javascript';
$string['tracking_javascript_desc'] = 'Enable tracking Javascript errors';
$string['tracking_php'] = 'Track PHP';
$string['tracking_php_desc'] = 'Enable tracking PHP errors';
$string['dsn'] = 'Sentry DSN';
$string['dsn_desc'] = 'Project DSN, copied from Client Settings in Sentry';
$string['tracing_db'] = 'Database Tracing';
$string['tracing_db_desc'] = 'Enable tracing database operations';
$string['tracing_hosts'] = 'Tracing Hosts';
$string['tracing_hosts_desc'] = 'Enable tracing operations on a list of servers, separated by commas. Use "<i>*</i>" to enable tracing on all hosts.';
