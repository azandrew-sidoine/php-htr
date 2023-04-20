<?php

require $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

// #region Define the total supported options argument list
if (!defined('HTr_MAX_ARGV')) {
    define('HTr_MAX_ARGV', 1000);
}
// #endregion Define the total supported options argument list

use Drewlabs\Htr\Utilities\Console;
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
    $index = 0;
    $configs = array();
    while ($index < HTr_MAX_ARGV && isset($args[$index])) {
        if (preg_match('/^([^-\=]+.*)$/', $args[$index], $matches) === 1) {
            // not have ant -= prefix
            $configs[$matches[1]] = true;
        } else if (preg_match('/^-+(.+)$/', $args[$index], $matches) === 1) {
            // match prefix - with next parameter
            if (preg_match('/^-+(.+)\=(.+)$/', $args[$index], $subMatches) === 1) {
                $configs[$subMatches[1]] = $subMatches[2];
            } else if (isset($args[$index + 1]) && preg_match('/^[^-\=]+$/', $args[$index + 1]) === 1) {
                // have sub parameter
                $configs[$matches[1]] = $args[$index + 1];
                $index++;
            } elseif (strpos($matches[0], '--') === false) {
                for ($j = 0; $j < strlen($matches[1]); $j += 1) {
                    $configs[$matches[1][$j]] = true;
                }
            } else if (isset($args[$index + 1]) && preg_match('/^[^-].+$/', $args[$index + 1]) === 1) {
                $configs[$matches[1]] = $args[$index + 1];
                $index++;
            } else {
                $configs[$matches[1]] = true;
            }
        }
        $index++;
    }

    return $configs;
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
    echo Console::normal("HTr - HTTP Request Test Runner", null, 'blue') . PHP_EOL . PHP_EOL;

    // #region Load command line arguments and options
    // In case the list of arguments starts with - or --, the command input configuration is the last parameter, else it's the first parameter
    if (empty($args)) {
        list($optionsArgs, $path) = [[], null];
    } else if ('-' === substr(strval($args[0]), 0, 1)) {
        // Case the total list of argument is 1 or the last element starts with - or --, we do not treat the last argument as command argument
        $optionsArgs = array_slice($args, 0, ((count($args) === 1) || ('-' === substr(strval($args[count($args) - 1]), 0, 1)) ? null : count($args) - 1));
        $path = array_slice($args, count($optionsArgs))[0] ?? null;
    } else {
        $path = $args[0];
        $optionsArgs = array_slice($args, 1);
    }
    $options = htr_cmd_options($optionsArgs);
    // #endregion Load command line arguments and options

    if (null === $path) {
        echo Console::normal("Program requires path to the configuration file", null, 'red') . PHP_EOL;
        return;
    }
    // Resolve the realpath to the path specified by the program user
    $path = @htr_resolve_path($path, __DIR__);

    $attributes = isset($options['json']) ? @json_decode(file_get_contents($path)) : \yaml_parse(file_get_contents($path));

    // Set the memory limit for the current script execution
    ini_set('memory_limit', '-1');
    set_time_limit(0);

    // #region Create the output function
    $then = isset($options['output']) ? function ($output) use ($options) {
        if (is_string($output)) {
            $directoryExists = is_dir(dirname(strval($options['output'])));
            $outpath =  $directoryExists ? $options['output'] : @htr_resolve_path(RandomID::new()->__invoke());
            if (!$directoryExists) {
                // Log to the console, with file not exists error
                echo Console::yellow("Cannot locate output path at " . (strval($options['output'])) . " Writing output to $outpath") . PHP_EOL;
            }
            file_put_contents($outpath, $output);
            echo Console::green(sprintf("Execution completed. Please see the complete request log located at %s for more info", $outpath)) . PHP_EOL;
        }
    } : function () {
        echo Console::green(sprintf("Execution completed. Thanks for using the program")) . PHP_EOL;
    };
    // #endregion Create the output function

    // We invoke Requests Test execution object to execute the requests
    $client = isset($options['verbose']) && boolval($options['verbose']) ? ProjectTests::new(Project::fromAttributes($attributes))->debug() : ProjectTests::new(Project::fromAttributes($attributes));

    // Execute the propject requests
    $client->execute($then);
}

main(array_slice($argv ?? [], 1));
