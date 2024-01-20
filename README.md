# TinyBinder

A super lightweight templating class that merges content, and the results of custom functions, with an HTML file.

## Features

- **Variable Replacement**: Easily replace variable placeholders in the HTML content by passing in strings or an array.

- **Function Replacement**: Replace function placeholders with the results of custom functions stored in a separate file.

- **Debugging Mode**: Enable debugging mode to display any placeholders that failed to merge during processing.

- **File or Raw Input**: Load HTML content from a file or use raw HTML input.

## Installation

Clone the repository or download the `TinyBinder.php` file and include it in your project.

## How to Use the Class

Merge single values:

```php
<?php

$tinyBinder = new TinyBinder('path/to/template.html');
$tinyBinder->addAsset('name', 'John');
$tinyBinder->addAsset('age', '25');
$result = $tinyBinder->getHtml();
```

Merge multiple values:

```php
<?php

$tinyBinder = new TinyBinder('path/to/template.html');
$tinyBinder->addAssets(['name' => 'John', 'age' => 25]);
$result = $tinyBinder->getHtml();
```

Debug:

```php
<?php

$tinyBinder = new TinyBinder('path/to/template.html');
$tinyBinder->debug();
```

Save time by using the static shorthand. This is equivalent to calling `addAssets`, `getHtml` and `debug`:

```php
<?php

$result = TinyBinder::make('path/to/template.html', ['name' => 'John', 'age' => 25], true);
```

## How to Create a Template

**1) Add a variable placeholder**

Wrap a variable placeholder in double curly braces. In the example below, `{{ $name }}` will be replaced with the content of the variable `$name` that you pass in using either the `addAsset` or `addAssets` method. The variable name in your HTML template must start with a `$` character.

```html
<html>

    {{ $name }}

</html>
```

**2) Add a function placeholder**

Wrap a function placeholder in double curly braces. In the example below, `{{ @time }}` will be replaced with the result of the `$time` closure in the `functions.php` file. Functions are merged automatically. The function name in your HTML template must start with a `@` character.

```html
<html>

    {{ @time }}

</html>
```

## How to Add a Custom Function

Add a new closure to the included `functions.php` file:

```php
$time = function() {
    return date('H:i:s');
};
```

The name of the variable that holds the closure result must match the function placeholder name. The function name in the template must start with a `@` character.

```html
<html>

    {{ @time }}

</html>
```

## Methods

- `make($path, $values, $debug = false)`: Creates a new template instance and initialises it with the provided path and values.

- `debug($debug = true)`: Enables or disables debugging mode.

- `addAsset($name, $value)`: Adds a single asset to the template.

- `addAssets($assets)`: Adds multiple assets to the template.

- `getHtml()`: Gets the processed HTML content after variable and function replacement.

## Contributions

Feel free to contribute or fix any issues. Create a pull request or open an issue for discussion.

## License

This project is licensed under the [MIT License](LICENSE).

