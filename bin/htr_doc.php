<?php

require $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

//#region Doc builder

use Drewlabs\Htr\Contracts\ComponentInterface;
use Drewlabs\Htr\Contracts\Descriptor;
use Drewlabs\Htr\Contracts\RequestInterface;
use Drewlabs\Htr\Markdown\Column;
use Drewlabs\Htr\Markdown\Table;
use Drewlabs\Htr\Project;
use Drewlabs\Htr\RandomID;
use Drewlabs\Htr\BodyPart;
use Drewlabs\Htr\Contracts\ResponseAware;
use Drewlabs\Htr\RequestDirectory;
use Drewlabs\Htr\Header;
use Drewlabs\Htr\Translator\Translations;
use Drewlabs\Htr\Utilities\Console;

/**
 * 
 * @param array $headers 
 * @return Descriptor[]|Header 
 * @throws InvalidArgumentException 
 */
function htr_doc_preprare_headers(array $headers)
{
    /**
     * @var Descriptor[]
     */
    $output = [];
    foreach ($headers as $key => $value) {
        if ($value instanceof Descriptor) {
            $output[] = $value;
            continue;
        }
        if (is_string($key) && !is_array($value)) {
            $output[] = Header::fromAttributes(['name' => $key, 'value' => $value]);
            continue;
        }
        $output = Header::fromAttributes($value);
        continue;
    }
    return $output;
}

/**
 * 
 * @param array $body 
 * @return Descriptor[]|Header 
 * @throws InvalidArgumentException 
 */
function htr_doc_preprare_body(array $body)
{
    $lines = [];
    $output = [];
    // Configure table
    $markdownTable = new Table();
    $markdownTable->addColumn('name', new Column('Attribute', Column::ALIGN_LEFT));
    $markdownTable->addColumn('required', new Column('Required', Column::ALIGN_LEFT));
    $markdownTable->addColumn('description', new Column('Description', Column::ALIGN_LEFT));
    // Configure table

    foreach ($body as $key => $value) {
        if ($value instanceof Descriptor) {
            $bodyPart = $value;
        } else if (is_string($key) && !is_array($value)) {
            $bodyPart = BodyPart::fromAttributes(['name' => $key, 'value' => $value]);
        } else {
            $bodyPart = BodyPart::fromAttributes($value);
        }
        $lines[] = ['name' => $bodyPart->getName(), 'required' => 'True', 'description' => ''];
    }
    foreach ($markdownTable->generate($lines) as $row) {
        $output[] = $row;
    }
    return implode(PHP_EOL, $output);
}


/**
 * 
 * @param string $name 
 * @param string $lang 
 * @return \Closure(...$args): string 
 */
function htr_doc_get_translation_factory(string $name, string $lang = 'en')
{
    return (new Translations($lang))->get($name);
}


/**
 * Create component documentation
 * 
 * @param ComponentInterface $component 
 * @return string 
 */
function htr_doc_create_component(ComponentInterface $component, string $header, array &$output, string $lang = 'en')
{
    // TODO: Generate name component
    $output[] = '';
    $output[] = sprintf("%s %s", $header, $component->getName());
    $output[] = '';

    // Generate headers component
    if ($component instanceof RequestDirectory) {
        $output[] = $component->getDescription() ?? '';
        // TODO: Review the line added after the description later
        $output[] = '';
        foreach ($component->getItems() as $item) {
            htr_doc_create_component($item, "$header#", $output, $lang);
        }
        return;
    }

    if ($component instanceof RequestInterface) {
        $output[] = !empty(trim($description = $component->getDescription())) ? $description : htr_doc_get_translation_factory('description', $lang)($component->getMethod(), $component->getUrl());

        // TODO: Generate output for request headers
        $requestHeaders = htr_doc_preprare_headers($component->getHeaders() ?? []);
        if (!empty($requestHeaders)) {
            $output[] = '';
            $output[] = 'Request Headers:';
            foreach ($requestHeaders as $value) {
                $output[] = sprintf("\t`%s: %s`", $value->getName(), $value->getValue());
            }
        }

        // TODO: Add output for authorization headers
        if ($authorization = $component->getAuthorization()) {
            $output[] = sprintf("\t`Authorization: %s %s`", ucfirst($authorization->getName()), $authorization->getValue());
        }
        // TODO: Generate output for request body
        if (!empty($requestBody = $component->getBody())) {
            $output[] = '';
            $output[] = 'Request Body:';
            $output[] = htr_doc_preprare_body($requestBody ?? []);
        }

        // TODO: Generate output for request response configuration
        if ($component instanceof ResponseAware) {
            if (empty($component->getResponseBody()) || empty($component->getResponseHeaders())) {
                return;
            }
            $output[] = '';
            $output[] = "Response:";
            $responseHeaders = htr_doc_preprare_headers($component->getHeaders() ?? []);
            if (!empty($responseHeaders)) {
                $output[] = "\tHeaders:";
                foreach ($responseHeaders as $value) {
                    $output[] = sprintf("\t\t`%s: %s`", $value->getName(), $value->getValue());
                }
            }

            if (!empty($responseBody = $component->getResponseBody())) {
                $output[] = '';
                $output[] = "\tBody:";
                $output[] = htr_doc_preprare_body($responseBody ?? []);
            }
        }
    }
}

/**
 * 
 * @param string $inputPath 
 * @param array $options 
 * @param string|null $outputPath 
 * @param string $baseDirectory 
 * @return void 
 * @throws Exception 
 * @throws InvalidArgumentException 
 */
function htr_doc_build(string $inputPath, array $options, string $outputPath = __DIR__, $baseDirectory = __DIR__)
{

    // Resolve the realpath to the path specified by the program user
    $path = @htr_resolve_path($inputPath, $baseDirectory);

    $attributes = isset($options['json']) && boolval($options['json'])  ? @json_decode(file_get_contents($path), true) : \yaml_parse(file_get_contents($path));

    if (!is_array($attributes) || empty($attributes)) {
        echo Console::white(sprintf("Failed to parse configuration file located at %s", $inputPath), null, 'red') . PHP_EOL . PHP_EOL;
        return;
    }

    // #region Create the output function
    $then = function ($output) use ($outputPath) {
        if (is_string($output)) {
            if (is_dir($outputPath)) {
                $outputPath = $outputPath . DIRECTORY_SEPARATOR . sprintf("%s.log", RandomID::new()->__invoke());
            }
            $directoryExists = is_dir(dirname(strval($outputPath)));
            $actualOuputPath =  $directoryExists ? $outputPath : @htr_resolve_path(RandomID::new()->__invoke());

            // Case the directory the directory does not exist we tell user where out will be written to
            if (!$directoryExists) {
                // Log to the console, with file not exists error
                echo Console::yellow("\nCannot locate output path at " . (strval($outputPath)) . " Writing output to $actualOuputPath") . PHP_EOL;
            }

            // Write tests log file
            file_put_contents($actualOuputPath, $output);

            echo Console::white("\nDocumentation written to $actualOuputPath", null, 'green') . PHP_EOL;
            echo PHP_EOL;
        }
    };
    // #endregion Create the output function
    $project = Project::fromAttributes($attributes);
    $header = "##";
    $output = [];
    
    // Set project header name
    $output[] = sprintf("# %s", $project->getName());

    // Add project component 
    foreach ($project->getComponents() as $component) {
        # code...
        htr_doc_create_component($component, $header, $output, $options['lang'] ?? 'en');
    }
    $output[] = '';

    // Execute the completion steps
    $then(implode(PHP_EOL, $output) . PHP_EOL);
}
//#endregion Doc builder
