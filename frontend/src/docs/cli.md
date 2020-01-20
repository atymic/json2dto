## CLI Tool

Prefer to use the tool locally? You can install `json2dto` via composer and generate files directly from json files.

```bash
composer global require atymic/json2dto # Install Globally

composer require atymic/json2dto --dev # Install locally in a project
```

### Usage

The tool accepts json input either as a filename (second argument) or via `stdin`.  
You should run the tool in the root of your project (where your `composer.json` is located) as it will resolve namespaces
based on your PSR4 autoloading config. If you aren't using PSR4, your generated folder structure might not match.

#### Examples

```bash
# Generate PHP 7.4 typed DTO
./vendor/bin/json2dto generate "App\DTO" test.json "Namespace\Subnamespace" -name "Test" --typed

# Generate a flexible DTO (with nested DTOs)
./vendor/bin/json2dto generate "App\DTO" test.json "Atymic\Test" -name "Test" --nested --flexible

# Generate a DTO from stdin
wget http://example.com/cat.json | ./vendor/bin/json2dto generate "App\DTO" -name Cat
```

#### Usage
```
json2dto generate [options] [--] <namespace> [<json>]

Arguments:
  namespace                       Namespace to generate the class(es) in
  json                            File containing the json string

Options:
      -name, --classname[=CLASSNAME]  Class name of the new DTO [default: "NewDto"]
      --nested                        Generate nested DTOs
      --typed                         Generate PHP >= 7.4 strict typing
      --flexible                      Generate a flexible DTO
      --dry                           Dry run, print generated files
```
