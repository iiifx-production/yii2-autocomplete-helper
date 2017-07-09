<?php
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2017
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

return '<?php
/*
 * Yii2 IDE Autocomplete Helper
 *
 * @author  Vitaliy IIIFX Khomenko (c) 2017
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication
     */
    public static $app;
}

%phpdoc%
abstract class BaseApplication extends \yii\base\Application {}

%phpdoc%
class WebApplication extends \yii\web\Application {}

%phpdoc%
class ConsoleApplication extends \yii\console\Application {}
';
