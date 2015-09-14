<?php

namespace iiifx\yii2\autocomplete;

use Yii;
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
        ( new self() )->connectYii2()->check();
    }

    /**
     * @return $this
     */
    protected function connectYii2 ()
    {
        /** @noinspection PhpIncludeInspection */
        require( FileHelper::normalizePath( $this->getAppPath() . '/vendor/yiisoft/yii2/Yii.php' ) );
        return $this;
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
        $config = require( $path );
        if ( isset( $config[ 'components' ] ) ) {
            foreach ( (array) $config[ 'components' ] as $component => $params ) {
                if ( is_array( $params ) && isset( $params[ 'class' ] ) ) {
                    $this->componentMap[ $component ] = $params[ 'class' ];
                } elseif ( is_string( $params ) ) {
                    $this->componentMap[ $component ] = $params;
                }
            }
        }
    }

    /**
     *
     */
    protected function buildAutocomplete ()
    {
        if ( !$this->componentMap ) return;
        $template = <<<'PHP'
<?php

class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication
     */
    public static $app;
}

/**
%propertyList%
 */
abstract class BaseApplication extends \yii\base\Application {}

/**
%propertyList%
 */
class WebApplication extends \yii\web\Application {}

/**
%propertyList%
 */
class ConsoleApplication extends \yii\console\Application {}

PHP;
        $propertyList = '';
        foreach ( $this->componentMap as $name => $class ) {
            $propertyList .= " * @property {$class} ${$name}" . PHP_EOL;
        }
        $propertyList = rtrim( $propertyList, PHP_EOL );
        $content = str_replace( '%propertyList%', $propertyList, $template );
        file_put_contents( FileHelper::normalizePath( $this->getAppPath() . '/autocomplete.php' ), $content );
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
