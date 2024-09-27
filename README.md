# Moodle Sentry integration plugin

This plugin enables PHP and Javascript error tracking and basic database performance tracing with Sentry.


## Prerequisites

- Sentry library (`composer require sentry/sentry`)

## Configuration

To avoid database calls and save on performance, you should set the options in config.php directly:
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
$CFG->forced_plugin_settings['local_sentry']['include_user_data'] = 1;
```

## Features

### PHP Error Tracking

PHP error tracking captures any exceptions and sends them to the Sentry server for tracking. Environment and release data is included.

### Javascript Error Tracking

Javascript error tracking injects the Sentry JS loader script inside the `<head>` element in HTML, calling the provided Sentry JS bundle upon any unhandled exception and sending the exception to the Sentry server for tracking.

### Database Tracing

Database tracing measures the execution time of database operations.



