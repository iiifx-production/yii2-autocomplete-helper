<?php
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2017
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

use Closure;
use Exception;
use Yii;
use yii\base\Object;
use yii\helpers\FileHelper;

class Config extends Object
{
    /**
     * @var mixed[]
     */
    public $files = [];

    /**
     * @var mixed[]
     */
    protected $_config;

    /**
     * @var mixed[]
     */
    protected $_components;

    /**
     * @return mixed[]
     *
     * @throws \yii\base\InvalidParamException
     */
    public function getComponents ()
    {
        if ( $this->_components === null ) {
            $this->_components = [];
            if ( $config = $this->readConfig() ) {
                foreach ( $this->files as $current ) {
                    if ( isset( $config[ $current ][ 'components' ] ) ) {
                        /** @var mixed[] $components */
                        $components = $config[ $current ][ 'components' ];
                        if ( is_array( $components ) ) {
                            foreach ( $components as $name => $component ) {
                                if ( ( $class = $this->findClass( $component ) ) !== false ) {
                                    $this->_components[ $name ][ $class ] = $class;
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
     *
     * @throws \yii\base\InvalidParamException
     */
    protected function readConfig ()
    {
        if ( $this->_config === null ) {
            $this->_config = [];
            foreach ( $this->files as $file ) {
                $path = Yii::getAlias( $file );
                $path = FileHelper::normalizePath( $path );
                if ( is_file( $path ) ) {
                    try {
                        /** @noinspection PhpIncludeInspection */
                        $this->_config[ $file ] = require $path;
                    } catch ( Exception $exception ) {
                        # Игнорируем
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
    protected function findClass ( $section )
    {
        try {
            if ( $section instanceof Closure ) {
                return get_class( $section() );
            }
            if ( is_object( $section ) ) {
                return get_class( $section );
            }
            if ( is_string( $section ) ) {
                return $section;
            }
            if ( is_array( $section ) && isset( $section[ 'class' ] ) ) {
                return $section[ 'class' ];
            }
        } catch ( Exception $exception ) {
            # Игнорируем
        }
        return false;
    }
}
