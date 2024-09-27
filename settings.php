<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
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
 * Adds admin settings for the plugin.
 *
 * @package     local_sentry
 * @category    admin
 * @copyright   2020 Your Name <email@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_category('local_sentry_settings', new lang_string('pluginname', 'local_sentry')));
    $settingspage = new admin_settingpage('managesentry', new lang_string('managesentry', 'local_sentry'));

    if ($ADMIN->fulltree) {
        $settingspage->add(new admin_setting_configtext(
            'local_sentry/autoload_path',
            new lang_string('autoload_path', 'local_sentry'),
            new lang_string('autoload_path_desc', 'local_sentry'),
            '/srv/composer/vendor/autoload.php'
        ));
        $settingspage->add(new admin_setting_configtext(
            'local_sentry/dsn',
            new lang_string('dsn', 'local_sentry'),
            new lang_string('dsn_desc', 'local_sentry'),
            'https://id-string@localhost/1'
        ));
        $settingspage->add(new admin_setting_configtext(
            'local_sentry/environment',
            new lang_string('environment', 'local_sentry'),
            new lang_string('environment_desc', 'local_sentry'),
            'testing'
        ));
        $settingspage->add(new admin_setting_configtext(
            'local_sentry/release',
            new lang_string('release', 'local_sentry'),
            new lang_string('release_desc', 'local_sentry'),
            '1'
        ));
        $settingspage->add(new admin_setting_configcheckbox(
            'local_sentry/tracking_javascript',
            new lang_string('tracking_javascript', 'local_sentry'),
            new lang_string('tracking_javascript_desc', 'local_sentry'),
            1
        ));
        $settingspage->add(new admin_setting_configcheckbox(
            'local_sentry/tracking_php',
            new lang_string('tracking_php', 'local_sentry'),
            new lang_string('tracking_php_desc', 'local_sentry'),
            1
        ));
        $settingspage->add(new admin_setting_configcheckbox(
            'local_sentry/tracing_db',
            new lang_string('tracing_db', 'local_sentry'),
            new lang_string('tracing_db_desc', 'local_sentry'),
            1
        ));
        $settingspage->add(new admin_setting_configtext(
            'local_sentry/tracing_hosts',
            new lang_string('tracing_hosts', 'local_sentry'),
            new lang_string('tracing_hosts_desc', 'local_sentry'),
            '*'
        ));
        $settingspage->add(new admin_setting_configcheckbox(
            'local_sentry/include_user_data',
            new lang_string('include_user_data', 'local_sentry'),
            new lang_string('include_user_data_desc', 'local_sentry'),
            0
        ));
    }

    $ADMIN->add('localplugins', $settingspage);
}