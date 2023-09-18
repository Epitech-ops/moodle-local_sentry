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
$CFG->sentry_autoload_path = "/srv/composer/vendor/autoload.php";
$CFG->sentry_dsn = 'https://id-string@sentry.server.si/project-id';
$CFG->sentry_tracing_hosts = ['localhost'];
```


