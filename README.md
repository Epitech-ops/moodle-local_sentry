# Moodle Sentry integration plugin

This plugin enables error tracking and (as of now) database performance tracking with Sentry.

Prerequisites:
- Sentry library (`composer require sentry/sentry`)
- config.php sentry options:
```php
//=========================================================================
// SENTRY SETUP
//=========================================================================
//
$CFG->forced_plugin_settings['local_sentry']['autoload_path'] = '/srv/composer/vendor/autoload.php';
$CFG->forced_plugin_settings['local_sentry']['dsn'] = 'https://id-string@localhost/1';
$CFG->forced_plugin_settings['local_sentry']['tracking_javascript'] = 1;
$CFG->forced_plugin_settings['local_sentry']['tracking_php'] = 1;
$CFG->forced_plugin_settings['local_sentry']['tracing_db'] = 1;
$CFG->forced_plugin_settings['local_sentry']['tracing_hosts'] = '*';
$CFG->forced_plugin_settings['local_sentry']['environment'] = 'testing';
$CFG->forced_plugin_settings['local_sentry']['release'] = '1';
```


