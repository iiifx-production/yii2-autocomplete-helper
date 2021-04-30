<?php declare(strict_types=1);
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
    public ?string $template = null;
    public array $components = [];

    public function build(string|false $file = null): bool|string
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

            return $m[0];
        }, $this->template);

        if ($file === null) {
            return $prepared;
        }

        return (bool)file_put_contents($file, $prepared);
    }
}
