# Yii2 IDE Autocomplete Helper

Autocompletion generator for custom components in Yii2.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/38baa1e0-54e8-4cf8-bd30-3c76e8a44d9b/big.png)](https://insight.sensiolabs.com/projects/38baa1e0-54e8-4cf8-bd30-3c76e8a44d9b)

[![Latest Version on Packagist][ico-version]][link-packagist] [![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads] 

[English documentation] [[Документация на русском](README.RU.md)] 

By default in Yii2 not working autocompletion for custom components. IDE sees no added components and this causes inconvenience in operation.

This extension allows you to automatically generate a file with the autocomplete PHPDoc blocks with which the IDE will recognize **all of the components** in the application configuration.

## Installation

Using Composer:

```bash
composer require "iiifx-production/yii2-autocomplete-helper:^1.2"
```

## Configuration

After installation, you need to one-time set up component to work.

For Yii2 Basic, in **@app/config/console.php**:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
        ],
        # ...
    ]
```

For Yii2 Advanced, in **@console/config/main.php**:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
        ],
        # ...
    ]
```

## Using

To generate autocompletion in the console:
```bash
php yii ide-components
```

Generator automatically detects the type of application, read all configuration files and generate the autocomplete file to the application root.
```bash
Yii2 IDE Autocomplete Helper
Vitaliy IIIFX Khomenko (c) 2016

Success: /domains/project.local/_ide_components.php
```
**Important:** For IDE did not swear on the two copies of the Yii class must be main Yii class file marked as a text document - [example](images/mark-as-plain-text.png).
The main class is located on the way: **@vendor/yiisoft/yii2/Yii.php**

## Advanced customization

Sometimes the structure of the application differs from the standard and the need to change the generator behavior.

The following are examples of possible configuration options.

### Changing the name of the component

If you need to change the name of a **autocomplete** to another, it is quite simple:
```php
    'bootstrap' => ['log', 'new-component-name'], # <-- new component name
    'components' => [
        'new-component-name' => [ # <-- new component name
            'class' => 'iiifx\Yii2\Autocomplete\Component',
        ],
        # ...
    ]
```

When the generator run in the console you need to pass the correct component name:
```bash
php yii ide-components --component=new-component-name
```

### Changing environment

By default, a generator start is only possible for YII_ENV = "dev" environment.

You can change the environment:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'environment' => 'local', # <-- environment
        ],
        # ...
    ]
```

### Changing the generator controller

By default, the generator uses a console controller to create autocompletion.

You can replace the default controller, extend it, or add your own implementation:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'controllerMap' => [
                'ide-components' => 'iiifx\Yii2\Autocomplete\Controller', # <-- default controller
                'my-custom-generator' => 'path\to\your\custom\Controller', # <-- your controller
            ],
        ],
        # ...
    ]
```

Now you can run your controller:
```bash
php yii my-custom-generator
```

Link to the controller by default: [source/Controller.php](source/Controller.php).

### Changing the autocompletion file 

By default, autocompletion file will be named **_ide_components.php** and will be placed in the application root.

You can change the name and location of the file:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'result' => '@app/new-file-name.php' # <-- name and path
        ],
        # ...
    ]
```

The file path must be relative to aliases framework. Example: **@common/../new-file-name.php**.

### Special configuration files

Sometimes you need to manually specify the application configuration files from which you want to generate autocompletion.

In this case, the generator will not seek configuration, the generator immediately uses this list.

For Yii2 Advanced:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'config' => [
                '@common/config/main.php', # <-- config list
                '@common/config/main-local.php',
                '@console/config/main.php',
                '@console/config/main-local.php',
                '@backend/config/main.php',
                '@backend/config/main-local.php',
                '@frontend/config/main.php',
                '@frontend/config/main-local.php',
            ],
        ],
        # ...
    ]
```

For Yii2 Basic:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'config' => [
                '@app/config/console.php', # <-- config list
                '@app/config/web.php',
            ],
        ],
        # ...
    ]
```

### Configuration groups

In big projects sometimes need to be able to generate different autocomplete files depending on the stage of development.

You can group configuration files and generate autocompletion only for a specific group.
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'config' => [
                'frontend' => [
                    '@common/config/main.php', # <-- frontend group
                    '@common/config/main-local.php',
                    '@frontend/config/main.php',
                    '@frontend/config/main-local.php',
                ],
                'backend' => [
                    '@common/config/main.php', # <-- backend group
                    '@common/config/main-local.php',
                    '@backend/config/main.php',
                    '@backend/config/main-local.php',
                ],
                'api' => [
                    '@common/config/main.php', # <-- api group
                    '@common/config/main-local.php',
                    '@common/../api/config/main.php',
                    '@common/../api/config/main-local.php',
                ],
            ],
        ],
        # ...
    ]
```

Now you can generate autocompletion for the desired group:
```bash
php yii ide-components --config=api
```

## Tests

[![Build Status][ico-travis]][link-travis] [![Code Coverage][ico-codecoverage]][link-scrutinizer]

## License

[![Software License][ico-license]](LICENSE.md)


[ico-version]: https://img.shields.io/packagist/v/iiifx-production/yii2-autocomplete-helper.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-downloads]: https://img.shields.io/packagist/dt/iiifx-production/yii2-autocomplete-helper.svg
[ico-travis]: https://travis-ci.org/iiifx-production/yii2-autocomplete-helper.svg
[ico-scrutinizer]: https://scrutinizer-ci.com/g/iiifx-production/yii2-autocomplete-helper/badges/quality-score.png?b=master
[ico-codecoverage]: https://scrutinizer-ci.com/g/iiifx-production/yii2-autocomplete-helper/badges/coverage.png?b=master

[link-packagist]: https://packagist.org/packages/iiifx-production/yii2-autocomplete-helper
[link-downloads]: https://packagist.org/packages/iiifx-production/yii2-autocomplete-helper
[link-travis]: https://travis-ci.org/iiifx-production/yii2-autocomplete-helper
[link-scrutinizer]: https://scrutinizer-ci.com/g/iiifx-production/yii2-autocomplete-helper/?branch=master
