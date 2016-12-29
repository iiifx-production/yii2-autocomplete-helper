<?php
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2016
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

use Yii;
use \Exception;
use yii\base\InvalidCallException;
use yii\helpers\FileHelper;

class Controller extends \yii\console\Controller
{
    /**
     * @inheritdoc
     */
    public function init ()
    {
        echo "Yii2 IDE Autocomplete Helper\n";
        echo "Vitaliy IIIFX Khomenko (c) 2016\n";
    }

    /**
     * @param string $name
     */
    public function actionIndex ( $name = 'autocomplete' )
    {
        try {
            if ( isset( Yii::$app->{$name} ) && Yii::$app->{$name} instanceof Component ) {
                $component = Yii::$app->{$name};
                # Определяем тип приложения и конфигурационные файлы
                $detector = new Detector( [
                    'app' => Yii::$app,
                ] );
                if ( $component->config === null ) {
                    $component->config = [];
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
                    'template' => require __DIR__ . '/template.php',
                ] );
                if ( $component->result === null ) {
                    $component->result = ( $detector->detect() === 'basic' ) ? '_ide_components.php' : '../_ide_components.php';
                }
                $result = FileHelper::normalizePath(
                    Yii::getAlias( '@app/' . $component->result )
                );
                if ( $builder->build( $result ) ) {
                    echo "\nSuccess: {$result}";
                } else {
                    echo "\nFail!";
                }
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
    }
}
