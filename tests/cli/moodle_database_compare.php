<?php

/**
 * sentry_moodle_database must define all
 * methods that moodle_database defines.
 */

define( 'CLI_SCRIPT', true );

require_once( __DIR__ . '/../../../../config.php' );
require_once( $CFG->libdir.'/clilib.php' );
require_once( $CFG->dirroot . '/lib/dml/moodle_database.php' );
require_once( __DIR__ . '/../../classes/sentry_moodle_database.php' );

$longoptions = [
    'help' => false,
    'debug' => false,
    'compare' => false,
];
list($options, $unrecognized) = cli_get_params($longoptions, ['h' => 'help']);

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized), 2);
}

if ($options['help']) {
    // The indentation of this string is "wrong" but this is to avoid a extra whitespace in console output.
    $help = <<<EOF
Compares the current moodle_database class from lib/dml/moodle_database.php with sentry_moodle_database and outputs any differences.

Options:
-h, --help            Print out this help
    --debug           Increase verbosity
    --compare         Perform comparison

Example:
\$ sudo -u www-data /usr/bin/php local/sentry/scripts/moodle_database_compare.php --compare

EOF;

    echo $help;
    exit(0);
}

if(empty($options['compare'])) {
    echo "'--compare' not specified, exitting\n";
    exit(0);
}

// Comparison vars
$errors = [];

// Compare methods

$scopes = [
    'public',
    'protected',
    'private'
];
$statics = [
    true,
    false
];
$reflection = new \ReflectionClass('moodle_database');
$sentry_reflection = new \ReflectionClass('local_sentry\sentry_moodle_database');
$return = [];
foreach($statics as $static) {
    foreach($scopes as $scope) {
            $pass = false;
        switch ($scope) {
            case 'public': $pass = \ReflectionMethod::IS_PUBLIC;
                break;
            case 'protected': $pass = \ReflectionMethod::IS_PROTECTED;
                break;
            case 'private': $pass = \ReflectionMethod::IS_PRIVATE;
                break;
        }
        if ($pass) {
            $moodle_methods = $reflection->getMethods($pass);
            $sentry_methods = $sentry_reflection->getMethods($pass);
            foreach ($moodle_methods as $method) {
                $isStatic = $method->isStatic();
                if (($static && !$isStatic) ||
                        (!$static && $isStatic)) {
                    continue;
                }
                $found = false;
                foreach($sentry_methods as $sentry_method) {
                    if($sentry_method->name === $method->name) {
                        if ($sentry_method->class === $sentry_reflection->getName()) {
                            $found = true;
                        }
                        break;
                    }
                }
                if ($method->class === $reflection->getName()) {
                    $pre = $static ? "static " : '';
                    if(!$found) {
                        $errors[] = $pre . "$scope method {$method->name} not found in sentry_moodle_database";
                        continue;
                    }
                    if($options['debug']) {
                        echo "processed " . $pre . "$scope method {$method->name}\n";
                    }
                    continue;
                }
            }
        }
    }
}

if (!empty($errors)) {
    echo "=====" . PHP_EOL;
    echo "ERRORS:" . PHP_EOL;
    foreach($errors as $error) {
        echo "- $error" . PHP_EOL;
    }
    exit(1);
}

echo "All methods match" . PHP_EOL;
exit(0);