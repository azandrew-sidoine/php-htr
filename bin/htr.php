<?php

require $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

use Drewlabs\Htr\Console;
use Drewlabs\Htr\Project;
use Drewlabs\Htr\ProjectTests;
use Drewlabs\Htr\RandomID;

// #region IO
/**
 * Recursively create directory if the later does not exists
 * 
 * @param string $dirname 
 * @return void 
 */
function htr_create_directory_if_not_exists(string $dirname)
{
    // Create the path directory if not exists
    if (!is_dir($dirname)) {
        mkdir($dirname, 0777, true);
    }
}

function htr_resolve_parent_directory_path(string $path, string $base = __DIR__)
{
    $substr = substr($path, 0, 3);
    // Handle relative path
    if (('../' === $substr) || ('..\\' === $substr)) {
        $directory = $base;
        $_substr = substr($path, 0, 3);
        while (('../' === $_substr) || ('..\\' === $_substr)) {
            $directory = dirname($directory);
            $path = substr($path, 3);
            $_substr = substr($path, 0, 3);
        }
        $path = $directory . DIRECTORY_SEPARATOR . $path;
    }

    return $path;
}

function htr_resolve_relative_path($path, string $base = __DIR__)
{
    $substr = substr($path, 0, 2);
    // Handle relative path
    if (('./' === $substr) || ('.\\' === $substr)) {
        $path = $base . DIRECTORY_SEPARATOR . substr($path, 2);
    }
    return $path;
}

function htr_resolve_path(string $path, string $base = __DIR__)
{
    $substr = substr($path, 0, 3);
    $path = ('../' === $substr) || ('..\\' === $substr) ? htr_resolve_parent_directory_path($path, $base) : (($subsustr = substr($substr, 0, 2)) && (('./' === $subsustr) || ('.\\' === $subsustr)) ? htr_resolve_relative_path($path, $base) : $path);
    // If the path does not starts with '/' we append the current 
    if ('/' !== substr($path, 0, 1)) {
        $path = $base . DIRECTORY_SEPARATOR . $path;
    }
    return $path;
}
// #endregion IO

// #region Command line utilities
/**
 * Resolve command options from command arguments
 * 
 * @param array $args 
 * @return array<array-key, mixed> 
 */
function htr_cmd_options(array $args)
{
    return array_values(array_map(function ($item) {
        return str_replace('-', '', $item);
    }, array_filter($args, function ($arg) {
        return substr($arg, 0, 1) === '-' || substr($arg, 0, 2) === '--';
    })));
}

/**
 * Resolve command arguments from command parameters
 * 
 * @param array $args 
 * @return array 
 */
function htr_cmd_arguments(array $args)
{
    return array_values(array_filter($args, function ($arg) {
        return substr($arg, 0, 1) !== '-' && substr($arg, 0, 2) !== '--';
    }));
}
// #endregion Command line utilities

/**
 * Main program
 * 
 * @param array $args 
 * @return void 
 * @throws Error 
 * @throws RuntimeException 
 */
function main(array $args = [])
{
    echo Console::normal("HTr - HTTP Request Test Runner!", null, 'blue');

    // #region Load command line arguments and options
    $arguments = htr_cmd_options($args);
    $options = htr_cmd_options($args);
    // #endregion Load command line arguments and options

    if (null === ($path = $arguments[0] ?? null)) {
        echo Console::normal("Program requires path to the configuration file", null, 'red');
    }
    // Resolve the realpath to the path specified by the program user
    $path = @htr_resolve_path($path, __DIR__);

    $attributes = isset($options['json']) ? @json_decode(file_get_contents($path)) : \yaml_parse(file_get_contents($path));

    print_r($attributes);
    die();
    // Set the memory limit for the current script execution
    ini_set('memory_limit', '-1');
    set_time_limit(0);

    // #region Create the output function
    $then = in_array('log', $options) ? function ($output) {
        if (is_string($output)) {
            $write_path = @htr_resolve_path(RandomID::new()->__invoke());
            file_get_contents($write_path, $output);
            echo Console::green(sprintf("Execution completed. Please see the complete request log located at %s for more info", $write_path));
        }
    } : function () {
        echo Console::green(sprintf("Execution completed. Thanks for using the program"));
    };
    // #endregion Create the output function

    // We invoke Requests Test execution object to execute the requests
    ProjectTests::new(Project::fromAttributes($attributes))->execute($then);
}

main(array_slice($argv, 1));
