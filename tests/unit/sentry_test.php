<?php
// This file is part of Sentry plugin for Moodle - http://moodle.org/
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
 * Sentry tests
 *
 * @package    local_sentry
 * @category   test
 * @copyright  2024 ARNES
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_sentry;

require_once( __DIR__ . '/../../../../lib/dml/tests/dml_test.php' );
require_once( __DIR__ . '/../../classes/sentry_moodle_database.php' );
require_once( __DIR__ . '/../../classes/sentry.php' );

use dml_exception;
use dml_missing_record_exception;
use dml_multiple_records_exception;
use moodle_database;
use moodle_transaction;
use xmldb_key;
use xmldb_table;

defined('MOODLE_INTERNAL') || die();

global $CFG;

/**
 * DML layer tests.
 *
 * @package    core
 * @category   test
 * @copyright  2008 Nicolas Connault
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @coversDefaultClass \moodle_database
 */
class sentry_test extends \advanced_testcase {

    protected $_config_off = [
        'autoload_path' => '',
        'environment' => 'testing',
        'release' => '1',
        'dsn' => '',
        'tracking_javascript' => 0,
        'tracking_php' => 0,
        'tracing_db' => 0,
        'tracing_hosts' => '',
        'include_user_data' => 0
    ];

    protected function setUp(): void {
        global $DB, $CFG;

        // $DB = new sentry_moodle_database(false, $DB);
        // $this->setup_cfg([]);
        $this->resetAfterTest();
        sentry::reset_config();
        $this->setAdminUser();
        // parent::setUp();
    }

    protected function setup_cfg(array $settings): void {
        global $CFG;
        // unset_config('forced_plugin_settings');
        foreach($this->_config_off as $name => $offval) {
            if(isset($settings[$name])) {
                // set_config($name, $settings[$name], 'local_sentry');
                $CFG->forced_plugin_settings['local_sentry'][$name] = $settings[$name];
            } else {
                // set_config($name, $offval, 'local_sentry');
                $CFG->forced_plugin_settings['local_sentry'][$name] = $offval;
            }
        }
    }

    public function test_get_config_off() {
        global $CFG;
        $this->resetAfterTest(true);
        $this->setup_cfg([]);
        $this->assertEquals(0, sentry::get_config('tracking_php'));
        $this->assertEquals(0, sentry::get_config('tracking_javascript'));
        $this->assertEquals(0, sentry::get_config('tracing_db'));
        $this->assertEquals(0, sentry::get_config('include_user_data'));
        $this->assertEquals([''], sentry::get_config('tracing_hosts'));
    }

    public function test_get_config() {
        global $CFG;
        $this->resetAfterTest(true);
        $this->setup_cfg([
            'tracking_php' => 1,
            'tracking_javascript' => 1,
            'tracing_db' => 1,
            'include_user_data' => 1,
            'tracing_hosts' => 'localhost,localhost.localdomain'
        ]);
        $this->assertEquals(1, sentry::get_config('tracking_php'));
        $this->assertEquals(1, sentry::get_config('tracking_javascript'));
        $this->assertEquals(1, sentry::get_config('tracing_db'));
        $this->assertEquals(1, sentry::get_config('include_user_data'));
        $this->assertEquals([
            'localhost',
            'localhost.localdomain'
        ], sentry::get_config('tracing_hosts'));
    }

    /**
     * Test Sentry bundle javascript location URL
     *
     * @return string
     */
    public function test_get_js_bundle_url() {
        $url = sentry::get_js_bundle_url();
        $this->assertEquals($url, new \moodle_url('/local/sentry/assets/js/sentry.bundle.min.js'));
    }

    /**
     * Test tracing_enabled function
     *
     * @return string
     */
    public function test_tracing_enabled() {
        $url = sentry::get_js_bundle_url();
        $this->setup_cfg([
            'tracing_db' => 1,
            'tracing_hosts' => 'localhost,localhost.localdomain'
        ]);
        $this->assertFalse(sentry::tracing_enabled());

        sentry::reset_config();
        $this->setup_cfg([
            'tracing_db' => 1,
            'tracing_hosts' => 'some-host'
        ]);
        $this->assertFalse(sentry::tracing_enabled());

        sentry::reset_config();
        $this->setup_cfg([
            'tracing_db' => 1,
            'tracing_hosts' => 'some-host,'.php_uname('n')
        ]);
        $this->assertTrue(sentry::tracing_enabled());

        sentry::reset_config();
        $this->setup_cfg([
            'tracing_db' => 0,
            'tracing_hosts' => 'some-host,'.php_uname('n')
        ]);
        $this->assertFalse(sentry::tracing_enabled());

        sentry::reset_config();
        $this->setup_cfg([
            'tracing_db' => 1,
            'tracing_hosts' => '*'
        ]);
        $this->assertTrue(sentry::tracing_enabled());

        sentry::reset_config();
        $this->setup_cfg([
            'tracing_db' => 0,
            'tracing_hosts' => '*'
        ]);
        $this->assertFalse(sentry::tracing_enabled());
    }
    public function test_dsn_is_set() {
        sentry::reset_config();
        $this->setup_cfg([
            'dsn' => ''
        ]);
        $this->assertFalse(sentry::dsn_is_set());

        sentry::reset_config();
        $this->setup_cfg([
            'dsn' => 'http://localhost.localdomain'
        ]);
        $this->assertTrue(sentry::dsn_is_set());
    }

    public function test_get_js_loader_url() {
        sentry::reset_config();
        $this->setup_cfg([
            'dsn' => ''
        ]);
        $this->assertEquals('', sentry::get_js_loader_url());

        sentry::reset_config();
        $this->setup_cfg([
            'dsn' => 'http://localhost.localdomain'
        ]);
        $this->assertEquals('https://www.example.com/moodle/pluginfile.php/1/local_sentry/sentry/sentry.loader.js', sentry::get_js_loader_url());
    }

    public function test_get_js_loader_script_html() {
        sentry::reset_config();
        $this->setup_cfg([
            'dsn' => ''
        ]);
        $this->assertEquals('', sentry::get_js_loader_script_html());

        sentry::reset_config();
        $this->setup_cfg([
            'dsn' => 'http://localhost.localdomain'
        ]);
        $this->assertEquals('<script src="https://www.example.com/moodle/pluginfile.php/1/local_sentry/sentry/sentry.loader.js"></script>'.PHP_EOL, sentry::get_js_loader_script_html());
    }

}