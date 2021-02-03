<?php declare(strict_types=1);
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2021
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

use Closure;
use Throwable;
use Yii;
use yii\base\BaseObject;
use yii\helpers\FileHelper;

class Config extends BaseObject
{
    public array $files = [];
    protected array $_config;
    protected array $_components;

    /**
     * @return mixed[]
     */
    public function getComponents(): array
    {
        if ($this->_components === null) {
            $this->_components = [];

            if ($config = $this->readConfig()) {
                foreach ($this->files as $current) {
                    if (isset($config[$current]['components'])) {
                        /** @var mixed[] $components */
                        $components = $config[$current]['components'];

                        if (is_array($components)) {
                            foreach ($components as $name => $component) {
                                if (($class = $this->findClass($component)) !== false) {
                                    $this->_components[$name][$class] = $class;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->_components;
    }

    /**
     * @return mixed[]
     */
    protected function readConfig(): array
    {
        if ($this->_config === null) {
            $this->_config = [];

            foreach ($this->files as $file) {
                $path = Yii::getAlias($file);
                $path = FileHelper::normalizePath($path);

                if (is_file($path)) {
                    try {
                        /** @noinspection PhpIncludeInspection */
                        $this->_config[$file] = require $path;
                    } catch (Throwable) {
                        # Ignore
                    }
                }
            }
        }

        return $this->_config;
    }

    /**
     * @param mixed $section
     *
     * @return string|false
     */
    protected function findClass(mixed $section): bool|string
    {
        try {
            if ($section instanceof Closure) {
                return get_class($section());
            }

            if (is_object($section)) {
                return get_class($section);
            }

            if (is_string($section)) {
                return $section;
            }

            if (is_array($section) && isset($section['class'])) {
                return $section['class'];
            }
        } catch (Throwable) {
            # Ignore
        }

        return false;
    }
}
