<?php
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2017
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

use Yii;
use yii\base\Application;
use yii\base\BaseObject;

class Detector extends BaseObject
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
            '@app/config/console.php',
            '@app/config/web.php',
        ],
        'advanced' => [
            '@common/config/main.php',
            '@common/config/main-local.php',
            '@console/config/main.php',
            '@console/config/main-local.php',
            '@backend/config/main.php',
            '@backend/config/main-local.php',
            '@frontend/config/main.php',
            '@frontend/config/main-local.php',
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
            return isset( $this->configs[ $type ] ) ? $this->configs[ $type ] : [];
        }
        return [];
    }

    /**
     * @return Application
     */
    protected function getApplication ()
    {
        if ( ! $this->app instanceof Application ) {
            $this->app = Yii::$app;
        }
        return $this->app;
    }
}
