# HTr (HTTP Service Test Runner)

`HTr` is a PHP based utility library that allow application (espacially HTTP REST services) developper to easily test their RESTful service using a a single configuration file writtern in `YAML` or `JSON`.

- Why `YAML` or `JSON`
  In modern web standard, `JSON` and `YAML` entity definition languages have become the defacto standard for modeling configuration that are easily portable between programming tools and languages. Therefore instead of creating a new language, we leverage existing ones that are known amoung developper community.

## Installation

The library is a PHP based library therefore a `PHP` binary is required along side the powerful `PHP` libraries package manager `composer`. 

To install the library simply run the command below:

```bash
> composer require drewlabs/htr
```

## Usage

The library provides a command line interface for testing your HTTP REST Service based on a configuration file. Below is the command to run a basic test command using a configuration file located in the root of your project:

```bash
> ./vendor/bin/htr $(pwd)/htr.yml
```

The command above assune you have a file named `htr.yml` in the root of your project that contains a valid `HTr` test configuration.

- Debug
  By default the `cli` script will run tests in silent mode and write output to a uniquely generated file at the root of your project. To run the `HTr cli` in debug mode, execute the command below:
  ```bash
  > ./vendor/bin/htr $(pwd)/htr.yml --verbose
  ```

    Running the client application in debug mode allow developper ro see live running request with each request parameters output to the terminal.

- Command output
  To override the output file name path were command logs are written simple use the `--output` flag as below:

  ```bash
  > ./vendor/bin/htr $(pwd)/htr.yml --output="$(pwd)/htr.log"
  ```
- Request filtering

  Sometimes developper might want to execute specific requests or specific request directory components. For such cases, `HTr` provides developpers with `--request` or `--req` for filtering by request name or id.

  ```bash
  > ./vendor/bin/htr $(pwd)/htr.yml -req="request-name"
  // For running single request
  // or
  > ../vendor/bin/htr $(pwd)/htr.yml -req="request1" --req="request2"
  // For running multiple request matching the provided name
  ```

    Similary, to execute specific request directories, you can use`-d` or `-directory` flag. The `HTr` client will only execute tests for requests in specified directory

```bash
>./vendor/bin/htr $(pwd)/htr.yml -d="directory name"
// For running tests in a single directory
// or
> ./vendor/bin/htr $(pwd)/htr.yml -d="directory1" -d="directory2"

For running tests in multiple directories
```
