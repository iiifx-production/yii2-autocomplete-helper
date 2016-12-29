# Yii2 IDE Autocomplete Helper

Генератор автодополнения для пользовательских компонентов в Yii2.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/38baa1e0-54e8-4cf8-bd30-3c76e8a44d9b/big.png)](https://insight.sensiolabs.com/projects/38baa1e0-54e8-4cf8-bd30-3c76e8a44d9b)

[![Latest Version on Packagist][ico-version]][link-packagist] [![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads] 

По умолчанию в Yii2 не работает автодополнение для пользовательских компонентов. IDE не видит добаленные комененты и это создает неудобства в процессе работы.

Это расширение позволяет автоматически генерировать файл автодополнения c PHPDoc-блоками, с помощью которого IDE будет распознавать _все компоненты_ в конфигурации приложения.

## Установка

Используя Composer:

``` bash
$ composer require "iiifx-production/yii2-autocomplete-helper:v1.*"
```

## Настройка

После установки нужно разово настроить компонент для работы.

Для Yii2 Basic, в файле config/console.php:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
        ],
        # ...
    ]
```

Для Yii2 Advanced, в файле console/config/main.php:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
        ],
        # ...
    ]
```

## Использование

В любой момент в консоли выполнить:
``` bash
$ php yii ide-components
```

Детектор автоматически распознает тип приложения, считает все конфигурационные файлы и сгенерирует для них файл автодополнения, который будет сохранен в корне приложения.
```bash
Yii2 Autocomplete Helper
Vitaliy IIIFX Khomenko (c) 2016

Success: /domains/project.local/_ide_components.php
```

Чтобы IDE не ругался на два экземпляра класса Yii необходимо файл основного класса Yii пометить как текстовый документ - [пример](images/mark-as-plain-text.png).
Файл основного класса расположен по пути: vendor/yiisoft/yii2/Yii.php

## Особая настройка

Иногда структура приложения отличается от стандартной и нужно изменить поведение компонента.

Ниже приведены примеры возможных вариантов конфигурации.

### Изменение названия компонента

Если вам нужно изменить название с 'autocomplete' на другое, то это делается достаточно просто:
```php
    'bootstrap' => ['log', 'new_name'], # <-- новое название
    'components' => [
        'new_name' => [ # <-- новое название
            'class' => 'iiifx\Yii2\Autocomplete\Component',
        ],
        # ...
    ]
```

При запуске генератора в консоли нужно передать правильное название компонента:
``` bash
$ php yii ide-components new_name
```

### Изменение окружения

По умолчанию запуск генератора возможен только для DEV-окружения.
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'environment' => 'rc', # <-- ваше окружение
        ],
        # ...
    ]
```

### Изменение контроллера генератора

Вы можете заменить контроллер генератора на свою собственную реализацию или изменить его:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'controllerMap' => [
                'ide-components' => 'iiifx\Yii2\Autocomplete\Controller', # <-- контроллер генератора
                'my-generator' => 'path\to\your\Controller', # <-- ваш особый контроллер
            ],
        ],
        # ...
    ]
```

Так можно разместить несколько генераторов и запускать их в нужный момент:
``` bash
$ php yii my-generator
```

### Изменение названия файла автодополнения

По умолчанию файл автодополнения будет назван "_ide_components.php", но это можно изменить:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'result' => '_new_name.php' # <-- другое название
        ],
        # ...
    ]
```

Важно понимать, что название файла всегда используется относитьно пути @app в фреймворке, потому для Yii2 Advanced приложения файл должен начинаться с "..". Пример: "../_new_name.php".

### Особые файлы конфигурации

Иногда нужно указать вручную файлы конфигурации приложения, с которых необходимо сгенерировать автодополнение.

В этом случае детектор не будет искать конфигурацию, генератор сразу использует указанный список.

Для Yii2 Advanced:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'config' => [
                '../common/config/main.php', # <-- список файлов конфигурации
                '../console/config/main.php',
                '../backend/config/main.php',
                '../frontend/config/main.php',
            ],
        ],
        # ...
    ]
```

Обратите внимание, что для Yii2 Advanced все файлы должны начинаться с "..".

Для Yii2 Basic:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'config' => [
                'config/console.php', # <-- список файлов конфигурации
                'config/web.php',
            ],
        ],
        # ...
    ]
```

## Тесты

[![Build Status][ico-travis]][link-travis] [![Code Coverage][ico-codecoverage]][link-scrutinizer]

## Лицензия

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
