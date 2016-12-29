<?php

namespace iiifx\Yii2\Autocomplete\Console;

use iiifx\Yii2\Autocomplete\Builder;
use iiifx\Yii2\Autocomplete\Component;
use iiifx\Yii2\Autocomplete\Config;
use Yii;
use \Exception;
use yii\base\InvalidCallException;
use yii\helpers\VarDumper;

class Controller extends \yii\console\Controller
{
    /**
     * @param string $name
     */
    public function actionIndex ( $name = 'autocomplete' )
    {
        echo "Yii2 Autocomplete Helper\n";
        echo "Vitaliy IIIFX Khomenko (c) 2016\n";
        try {
            if ( isset( Yii::$app->{$name} ) && Yii::$app->{$name} instanceof Component ) {
                $component = Yii::$app->{$name};
                # Определяем тип приложения и конфигурационные файлы
                if ( $component->config === null ) {
                    $component->config = [];
                    $detector = new Detector( [
                        'app' => Yii::$app,
                    ] );
                    if ( $detector->detect() === false ) {
                        throw new InvalidCallException( 'Unable to determine application type' );
                    }
                    $component->config = $detector->getConfig();
                }
                # Читаем конфигурационные файлы
                $config = new Config( [
                    'root' => Yii::getAlias( '@app' ),
                    'files' => $component->config,
                ] );
                $builder = new Builder( [
                    'components' => $config->getComponents(),
                ] );
                $builder->build( $component->result );
            } else {
                echo "\nComponent '{$name}' not found in Yii::\$app";
                echo "\nPlease read how to configure the package";
                echo "\nhttps://github.com/iiifx-production/yii2-autocomplete-helper\n";
            }
        } catch ( Exception $exception ) {
            echo "\n" . $exception->getMessage();
            echo "\nPlease read how to configure the package";
            echo "\nhttps://github.com/iiifx-production/yii2-autocomplete-helper\n";
        }


        # Найти и прочитать конфигурацию
        # Сгенерировать и сохранить
    }
}
