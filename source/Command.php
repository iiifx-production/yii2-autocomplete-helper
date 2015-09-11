<?php

namespace iiifx\yii2\autocomplete;

use yii\helpers\FileHelper;

class Command
{
    /**
     *
     */
    const APP_SIMPLE = 1;
    /**
     *
     */
    const APP_ADVANCED = 2;

    /**
     * @var mixed[]
     */
    public static $configList = [
        self::APP_SIMPLE => [ ],
        self::APP_ADVANCED => [
            'backend/config/main.php',
            'backend/config/main-local.php',
            'common/config/main.php',
            'common/config/main-local.php',
            'frontend/config/main.php',
            'frontend/config/main-local.php',
        ],
    ];

    /**
     * @var string[]
     */
    protected $componentMap = [ ];

    /**
     *
     */
    public static function execute ()
    {
        ( new self() )->check();
    }

    /**
     *
     */
    protected function check ()
    {
        foreach ( $this->getSimpleList() as $path ) {
            $this->readConfigFile( $path );
        }
        foreach ( $this->getAdvancedList() as $path ) {
            $this->readConfigFile( $path );
        }
        $this->buildAutocomplete();
    }

    /**
     * @return string[]
     */
    protected function getSimpleList ()
    {
        $list = [ ];
        if ( isset( self::$configList[ self::APP_SIMPLE ] ) ) {
            foreach ( (array) self::$configList[ self::APP_SIMPLE ] as $file ) {
                if ( ( $file = $this->checkPath( $file ) ) ) {
                    $list[] = $file;
                }
            }
        }
        return $list;
    }

    /**
     * @return string[]
     */
    protected function getAdvancedList ()
    {
        $list = [ ];
        if ( isset( self::$configList[ self::APP_ADVANCED ] ) ) {
            foreach ( (array) self::$configList[ self::APP_ADVANCED ] as $file ) {
                if ( ( $file = $this->checkPath( $file ) ) ) {
                    $list[] = $file;
                }
            }
        }
        return $list;
    }

    /**
     * @param $path
     */
    protected function readConfigFile ( $path )
    {
        /** @noinspection PhpIncludeInspection */
        $content = require( $path );

        var_export( $content ); die();
    }

    /**
     *
     */
    protected function buildAutocomplete ()
    {

    }

    /**
     * @param string $path
     *
     * @return string|bool
     */
    protected function checkPath ( $path )
    {
        $path = FileHelper::normalizePath( $this->getAppPath() . '/' . ltrim( $path, '\/' ) );
        return ( is_file( $path ) ) ? $path : FALSE;
    }

    /**
     * @return string
     */
    protected function getAppPath ()
    {
        return FileHelper::normalizePath( dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) ); # TODO LazyInit
    }
}

/*
    "scripts": {
        "yii2-autocomplete": "iiifx\\yii2\\autocomplete\\Command::execute"
    }
*/
