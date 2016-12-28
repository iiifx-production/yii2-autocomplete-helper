<?php
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2016
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

class Reader
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var mixed[]
     */
    protected $files = [ ];

    /**
     * @var mixed[]
     */
    protected $config;

    /**
     * @var mixed[]
     */
    protected $components;

    /**
     * @param string $params
     */
    public function __construct ( $params )
    {
        if ( isset( $params[ 'path' ] ) ) {
            $this->path = rtrim( $params[ 'path' ], '\\/' );
        } else {
            throw new \InvalidArgumentException( 'Parameter "path" is not found.' );
        }
        if ( isset( $params[ 'files' ] ) ) {
            foreach ( (array) $params[ 'files' ] as $file ) {
                $this->files[ ] = ltrim( $file, '\\/' );
            }
        } else {
            throw new \InvalidArgumentException( 'Parameter "files" is not found.' );
        }
    }

    /**
     * @return string
     */
    public function getPath ()
    {
        return $this->path;
    }

    /**
     * @return mixed[]|void
     */
    public function getConfig ()
    {
        if ( $this->config === null ) {
            $this->config = $this->readConfig();
        }

        return $this->config;
    }

    /**
     * @return mixed[]|false
     */
    protected function readConfig ()
    {
        if ( is_dir( $this->getPath() ) ) {
            $__config = [ ];
            foreach ( $this->files as $__file ) {
                $__path = $this->getPath() . DIRECTORY_SEPARATOR . $__file;
                if ( is_file( $__path ) ) {
                    /** @noinspection PhpIncludeInspection */
                    $__config[ $__file ] = require( $__path );
                }
            }

            return $__config;
        }

        return false;
    }

    /**
     * @param mixed $section
     *
     * @return string|false
     */
    protected function findClass ( $section )
    {
        if ( is_string( $section ) ) {
            return $section;
        } elseif ( is_array( $section ) && isset( $section[ 'class' ] ) ) {
            return $section[ 'class' ];
        } elseif ( is_callable( $section ) ) {
            return $section();
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isValid ()
    {
        return (bool) $this->getComponents();
    }

    /**
     * @return mixed[]|false
     */
    public function getComponents ()
    {
        if ( $this->components === null ) {
            $this->components = $this->readComponents();
        }

        return $this->components;
    }

    /**
     * @return mixed[]|false
     */
    protected function readComponents ()
    {
        if ( ( $config = $this->getConfig() ) ) {
            $components = [ ];
            foreach ( $this->files as $current ) {
                if ( isset( $config[ $current ], $config[ $current ][ 'components' ] ) ) {
                    foreach ( $config[ $current ][ 'components' ] as $name => $component ) {
                        if ( ( $class = $this->findClass( $component ) ) !== false ) {
                            $components[ $name ][ $class ] = $class;
                        }
                    }
                }
            }

            return $components;
        }

        return false;
    }
}
