# Json 2 DTO

[Spatie's Data Transfer Object](https://github.com/spatie/data-transfer-object) library is awesome, but typing out DTOs
can quicky become a chore. Inspired by Json2Typescript style tools, we built one for PHP DTOs.

## Web Version

Try it out at [https://json2dto.atymic.dev](https://json2dto.atymic.dev)  
The web version has all of the same tools as the cli version in an easy to use GUI. When generating nested DTOs, the 
tool will create a zip file.

[![](https://repository-images.githubusercontent.com/231837839/14ed4680-2fb2-11ea-81fe-f06c5038b0dd)](https://json2dto.atymic.dev)

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
