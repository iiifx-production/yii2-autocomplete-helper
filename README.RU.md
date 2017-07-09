# Yii2 IDE Autocomplete Helper

Генератор автодополнения для пользовательских компонентов в Yii2.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/38baa1e0-54e8-4cf8-bd30-3c76e8a44d9b/big.png)](https://insight.sensiolabs.com/projects/38baa1e0-54e8-4cf8-bd30-3c76e8a44d9b)

[![Latest Version on Packagist][ico-version]][link-packagist] [![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

[[English documentation](README.md)] [Документация на русском] 

По умолчанию в Yii2 не работает автодополнение для пользовательских компонентов. IDE не видит добавленные компоненты и это создает неудобства в процессе работы.

Это расширение позволяет автоматически генерировать файл автодополнения c PHPDoc-блоками, с помощью которого IDE будет распознавать **все компоненты** в конфигурации приложения.

## Установка

Используя Composer:

```bash
composer require "iiifx-production/yii2-autocomplete-helper:^1.2"
```

## Настройка

После установки нужно разово настроить компонент для работы.

Для Yii2 Basic, в **@app/config/console.php**:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
        ],
        # ...
    ]
```

Для Yii2 Advanced, в **@console/config/main.php**:
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

Для генерации автодополнения в консоли выполнить:
```bash
php yii ide-components
```

Детектор генератора автоматически распознает тип приложения, прочитает все конфигурационные файлы и сгенерирует для них файл автодополнения, который будет сохранен в корне приложения.
```bash
Yii2 IDE Autocomplete Helper
Vitaliy IIIFX Khomenko (c) 2016

Success: /domains/project.local/_ide_components.php
```

**Важно:** Чтобы IDE не ругался на два экземпляра класса Yii необходимо файл основного класса Yii пометить как текстовый документ - [пример](images/mark-as-plain-text.png).
Файл основного класса расположен по пути: **@vendor/yiisoft/yii2/Yii.php**

## Расширенная настройка

Иногда структура приложения отличается от стандартной и нужно изменить поведение компонента.

Ниже приведены примеры возможных вариантов конфигурации.

### Изменение названия компонента

Если вам нужно изменить название с **autocomplete** на другое, то это делается достаточно просто:
```php
    'bootstrap' => ['log', 'new-component-name'], # <-- новое название
    'components' => [
        'new-component-name' => [ # <-- новое название
            'class' => 'iiifx\Yii2\Autocomplete\Component',
        ],
        # ...
    ]
```

При запуске генератора в консоли нужно передать правильное название компонента:
```bash
php yii ide-components --component=new-component-name
```

### Изменение окружения

По умолчанию запуск генератора возможен только для YII_ENV = "dev" окружения.

Вы можете изменить окружение на любое другое:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'environment' => 'local', # <-- ваше окружение
        ],
        # ...
    ]
```

### Изменение контроллера генератора

По умолчанию генератор использует свой консольный контроллер для создания автодополнения.

Вы можете заменить контроллер по умолчанию, расширить его или даже добавить свои собственные реализации:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'controllerMap' => [
                'ide-components' => 'iiifx\Yii2\Autocomplete\Controller', # <-- контроллер генератора по умолчанию
                'my-custom-generator' => 'path\to\your\custom\Controller', # <-- ваш особый контроллер
            ],
        ],
        # ...
    ]
```

Теперь можно запустить ваш особый контроллер:
```bash
php yii my-custom-generator
```

Ссылка на контроллер по умолчанию: [source/Controller.php](source/Controller.php).

### Изменение файла автодополнения

По умолчанию сгенерированный файл автодополнения будет назван **_ide_components.php** и будет размещен в корне приложения.
 
Вы можете изменить название и расположение файла:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'result' => '@app/new-file-name.php' # <-- другое название и путь
        ],
        # ...
    ]
```

Путь к файлу должен быть указан относительно алиасов фреймворка. Пример: **@common/../new-file-name.php**.

### Особые файлы конфигурации

Иногда нужно указать вручную файлы конфигурации приложения, с которых необходимо сгенерировать автодополнение.

В этом случае детектор генератора не будет искать конфигурацию, генератор сразу использует указанный список.

Для Yii2 Advanced:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'config' => [
                '@common/config/main.php', # <-- список файлов конфигурации
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

Для Yii2 Basic:
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'config' => [
                '@app/config/console.php', # <-- список файлов конфигурации
                '@app/config/web.php',
            ],
        ],
        # ...
    ]
```

### Группы файлов конфигурации

В крупных проектах иногда нужно иметь возможность генерировать разные файлы автодополнения в зависимости от этапа разработки.

Вы можете сгруппировать файлы конфигурации и генерировать автодополнение лишь для определенной группы.
```php
    'bootstrap' => ['log', 'autocomplete'],
    'components' => [
        'autocomplete' => [
            'class' => 'iiifx\Yii2\Autocomplete\Component',
            'config' => [
                'frontend' => [
                    '@common/config/main.php', # <-- группа для frontend
                    '@common/config/main-local.php',
                    '@frontend/config/main.php',
                    '@frontend/config/main-local.php',
                ],
                'backend' => [
                    '@common/config/main.php', # <-- группа для backend
                    '@common/config/main-local.php',
                    '@backend/config/main.php',
                    '@backend/config/main-local.php',
                ],
                'api' => [
                    '@common/config/main.php', # <-- группа для api
                    '@common/config/main-local.php',
                    '@common/../api/config/main.php',
                    '@common/../api/config/main-local.php',
                ],
            ],
        ],
        # ...
    ]
```

Теперь можно сгенерировать автодополнение для нужной группы:
```bash
php yii ide-components --config=api
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
