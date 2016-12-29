<?php

namespace iiifx\Yii2\Autocomplete\Console;

use Yii;
use yii\base\Application;
use yii\base\Object;

class Detector extends Object
{
    /**
     * @var Application
     */
    public $app;

    /**
     * @var array
     */
    public $ids = [
        'basic-console' => 'basic',
        'app-console' => 'advanced',
    ];

    /**
     * @var array
     */
    public $configs = [
        'basic' => [
            'config/console.php',
            'config/web.php',
        ],
        'advanced' => [
            '../common/config/main.php',
            '../console/config/main.php',
            '../backend/config/main.php',
            '../frontend/config/main.php',
        ],
    ];

    /**
     * @return string|false
     */
    public function detect ()
    {
        $application = $this->getApplication();
        if ( isset( $this->ids[ $application->id ] ) ) {
            return $this->ids[ $application->id ];
        }
        return false;
    }

    /**
     * @return array
     */
    public function getConfig ()
    {
        if ( $type = $this->detect() ) {
            if ( isset( $this->configs[ $type ] ) ) {
                return $this->configs[ $type ];
            }
        }
        return [];
    }

    /**
     * @return Application
     */
    protected function getApplication ()
    {
        if ( !$this->app instanceof Application ) {
            $this->app = Yii::$app;
        }
        return $this->app;
    }
}
