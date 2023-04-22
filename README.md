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


### Configuration

`HTr` client application works with configuration files written in either `JSON (Javascript Object Notation)` format or `YAML` format. Below is a sample configuration file:

```yaml
version: 0.1.0
# $schema: http://json-schema.org/id/<SCHEMA_ID>
name: BIOSECU ADMIN API TESTS

# Environment definitions
# Note: Environemnts are used by the runner to customize requests
env:
  _host: http://127.0.0.1:12300
  _apiVersion: "api"
  _postId: 2
  _commentId: 2

components:
  # The part below defines a request group or directory
  - name: "posts"
    description: Defines post management REST interfaces"
    items:
      - url: "[_host]/[_apiVersion]/posts"
        method: "GET"
        authorization:
          name: "bearer"
          value: "[_bearerToken]"
        body:
        params:
          page: 1
          per_page: 50
        tests:
          - "[status] eq 200" # Asser that request response status code == 200
      - url: "[_host]/[_apiVersion]/post"
        method: "POST"
        authorization:
          name: "bearer"
          value: "[_bearerToken]"
        body:
          title: "Environments"
          content: "This is an environment post"
        tests:
          - "[body].title eq Environment" #  Assert that request response body is has title field == Environments
          - "[status] eq 200" # Assert that request must be completed with status code 200
      - url: "[_host]/[_apiVersion]/post/:[_postId]"
        method: "PUT"
        authorization:
          name: "bearer"
          value: "[_bearerToken]"
        body:
        # Pass request body
        tests:
          - "[status] eq 422" # Assert that request response status code is 422

  # Comments requests directory
  - name: "comments"
    description: "Defines comments management REST interfaces"
    items:
      # Here we difines a request configuration that sends a POST request to http://127.0.0.1:12300/api/comments
      - url: "[_host]/[_apiVersion]/posts"
        method: "GET"
        authorization:
          name: "bearer"
          value: "[_bearerToken]"
        # body:
        params:
          page: 1
          per_page: 50
        tests:
          - "[status] eq 200" # Assert that request response status code == 200
      - url: "[_host]/[_apiVersion]/comments"
        method: "POST"
        authorization:
          name: "bearer"
          value: "[_bearerToken]"
        body:
          post_id: "[_postId]"
          content: "My Comment"
        tests:
          - "[status] eq 200" # Assert that request must be completed with status code 200
      - url: "[_host]/[_apiVersion]/post/:[_commentId]"
        method: "PUT"
        authorization:
          name: "bearer"
          value: "[_bearerToken]"
        body:
        # Pass request body
        tests:
          - "[status] eq 422" # Assert that request response status code is 422
```

**Note** Based on the configuration above requests can be customized using environment variables. Environment variables must be enclosed in `[variable]`.

### Testing

Test assertion use a natural language to make it easy to write test. Below is the syntax to write test:

- `left op right` -> For simple assertions
- `left op righ and condition_2_left and condition_2_right` -> For composed assertions

Suported operator for testing are:

- Logical operators
  - `and` : For joining 2 or more conditions and return `true` only if all conditions return `true`
  - `or` : For joining 2 o more condition and return `true` if one of the condittions is `true`
- Comparison operations
  - `lt` or `>` : Stand for value `less than`
  - `lte` or `<=` : For `less than or equals to comparison`
  - `gt` or `>` : For `greater than comparison`
  - `gte` or `>=` : For `greater than or equals to comparison`
  - `eq` or `ne` : Respectively `equals` or `not equals`
  - `has` : Check if an array has a given key or attribute
  - `in` : Checks if a value exists in a list of value. Ex: `mango in banana,orange,pinneaple`

We use `[]` to denote properties of the response object:

- `[status]` denote response status code
- `[body]` denote response body
- `[headers]` denote response headers.

To Access properties of response body, we use [body] concatenated with the property name using `.` symbol as follow;

- `[body].title` -> To access the title property of the response body
- `[body].address.email` -> Can be used for instance to access inner property of responser body object

### Command Line

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

  Similary, to execute specific request directories, you can use `-d` or `-directory` flag. The `HTr` client will only execute tests for requests in specified directory

```bash
>./vendor/bin/htr $(pwd)/htr.yml -d="directory name"
// For running tests in a single directory
// or
> ./vendor/bin/htr $(pwd)/htr.yml -d="directory1" -d="directory2"

For running tests in multiple directories
```

- Json
  By default the `HTr` client support `YAML` based configuration files. In order to use `JSON` configuration file instead, simply use the `--json` flag as follow:

  ```
  > ./vendor/bin/htr $(pwd)/htr.json --json

  ```
