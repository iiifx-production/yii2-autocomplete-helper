<?php
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2016
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

use Closure;
use Exception;

class Config extends \yii\base\Object
{
    /**
     * @var string
     */
    public $root;

    /**
     * @var mixed[]
     */
    public $files = [];

    /**
     * @var mixed[]
     */
    protected $config;

    /**
     * @var mixed[]
     */
    protected $components;

    /**
     * @return array
     */
    public function getComponents ()
    {
        if ( $this->components === null ) {
            $this->components = [];
            if ( $config = $this->readConfig() ) {
                foreach ( $this->files as $current ) {
                    if ( isset( $config[ $current ][ 'components' ] ) ) {
                        $components = $config[ $current ][ 'components' ];
                        if ( is_array( $components ) ) {
                            foreach ( $components as $name => $component ) {
                                if ( ( $class = $this->findClass( $component ) ) !== false ) {
                                    $this->components[ $name ][ $class ] = $class;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->components;
    }

    /**
     * @return mixed[]|false
     */
    protected function readConfig ()
    {
        if ( $this->config === null ) {
            if ( is_dir( $this->root ) ) {
                foreach ( $this->files as $file ) {
                    $path = $this->root . DIRECTORY_SEPARATOR . $file;
                    if ( is_file( $path ) ) {
                        try {
                            /** @noinspection PhpIncludeInspection */
                            $this->config[ $file ] = require $path;
                        } catch ( Exception $exception ) {
                            # Игнорируем
                        }
                    }
                }
            }
        }
        return $this->config;
    }

    /**
     * @param mixed $section
     *
     * @return string|false
     */
    protected function findClass ( $section )
    {
        try {
            if ( $section instanceof Closure ) {
                return get_class( $section() );
            } elseif ( is_object( $section ) ) {
                return get_class( $section );
            } elseif ( is_string( $section ) ) {
                return $section;
            } elseif ( is_array( $section ) && isset( $section[ 'class' ] ) ) {
                return $section[ 'class' ];
            }
        } catch ( Exception $exception ) {
            # Игнорируем
        }
        return false;
    }
}
