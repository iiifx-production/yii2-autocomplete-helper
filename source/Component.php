<?php

namespace iiifx\Yii2\Autocomplete;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\console\Controller;

class Component extends \yii\base\Component implements BootstrapInterface
{
    /**
     * Окружение, для которого разрешена работа компонента
     *
     * @var string
     */
    public $environment = 'dev';

    /**
     * @var array
     */
    public $controllerMap = [
        'ide-components' => \iiifx\Yii2\Autocomplete\Controller::class,
    ];

    /**
     * @inheritdoc
     */
    public function bootstrap ( $app )
    {
        if ( $app instanceof \yii\console\Application && $this->isActive() ) {
            $this->updateControllerMap( $app );
        }
    }

    /**
     * @return bool
     */
    public function isActive ()
    {
        return defined( 'YII_ENV' ) && YII_ENV === $this->environment;
    }

    /**
     * @param Application $app
     *
     * @return int
     */
    public function updateControllerMap ( Application $app )
    {
        $count = 0;
        if ( is_array( $this->controllerMap ) ) {
            foreach ( $this->controllerMap as $name => $controller ) {
                if ( is_subclass_of( $controller, \yii\console\Controller::class ) ) {
                    $app->controllerMap[ $name ] = $controller;
                    $count++;
                }
            }
        }
        return $count;
    }
}
