<?php
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2021
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

use yii\base\BaseObject;

class Builder extends BaseObject
{
    /**
     * @var string
     */
    public $template;

    /**
     * @var array
     */
    public $components = [];

    /**
     * @var string|null
     */
    public $webAppClass = null;

    /**
     * @var string|null
     */
    public $consoleAppClass = null;

    /**
     * @param string|false $file
     *
     * @return bool|string
     */
    public function build($file = null)
    {
        $prepared = preg_replace_callback('/%.*%/U', function ($m) {
            if ($m[0] === '%phpdoc%') {
                $string = '/**';
                foreach ($this->components as $name => $classes) {
                    $string .= PHP_EOL . ' * @property ' . implode('|', $classes) . ' $' . $name;
                }
                $string .= PHP_EOL . ' */';
                return $string;
            }

            if ($m[0] === '%webapp%') {
                return $this->webAppClass ?? '\yii\web\Application';
            }

            if ($m[0] === '%consoleapp%') {
                return $this->consoleAppClass ?? '\yii\console\Application';
            }

            return $m[0];
        }, $this->template);
        if ($file === null) {
            return $prepared;
        }
        return (bool)file_put_contents($file, $prepared);
    }
}
