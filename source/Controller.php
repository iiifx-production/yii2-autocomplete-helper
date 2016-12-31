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
     * @var string
     */
    public $component = 'autocomplete';

    /**
     * @var string
     */
    public $config;

    /**
     * @inheritdoc
     */
    public function options ( $actionID = null )
    {
        return [ 'component', 'config' ];
    }

    /**
     * @inheritdoc
     */
    public function optionAliases ()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function init ()
    {
        echo "Yii2 IDE Autocomplete Helper\n";
        echo "Vitaliy IIIFX Khomenko (c) 2016\n";
    }

    /**
     * Точка входа
     */
    public function actionIndex ()
    {
        try {
            if ( isset( Yii::$app->{$this->component} ) && Yii::$app->{$this->component} instanceof Component ) {
                $component = Yii::$app->{$this->component};
                # Определяем тип приложения и конфигурационные файлы
                $detector = new Detector( [
                    'app' => Yii::$app,
                ] );
                if ( $component->config === null ) {
                    # В компоненте не указаны файлы конфигурации
                    if ( $detector->detect() === false ) {
                        throw new InvalidCallException( 'Unable to determine application type' );
                    }
                    $configList = $detector->getConfig();
                } else {
                    # Файлы конфигурации указаны
                    if ( $this->config === null ) {
                        if ( isset( $component->config[ 0 ] ) ) {
                            $configList = $component->config;
                        } else {
                            throw new InvalidCallException( "Default config list not found in component config data" );
                        }
                    } else {
                        if ( isset( $component->config[ $this->config ] ) ) {
                            $configList = $component->config[ $this->config ];
                        } else {
                            throw new InvalidCallException( "Scope '{$this->config}' not found in component config data" );
                        }
                    }
                }
                # Читаем конфигурационные файлы
                $config = new Config( [
                    'files' => $configList,
                ] );
                $builder = new Builder( [
                    'components' => $config->getComponents(),
                    'template' => require __DIR__ . '/template.php',
                ] );
                if ( $component->result === null ) {
                    $component->result = ( $detector->detect() === 'basic' ) ?
                        '@app/_ide_components.php' :
                        '@console/../_ide_components.php';
                }
                $result = Yii::getAlias( $component->result );
                $result = FileHelper::normalizePath( $result );
                if ( $builder->build( $result ) ) {
                    echo "\nSuccess: {$result}";
                } else {
                    echo "\nFail!";
                }
            } else {
                echo "\nComponent '{$name}' not found in Yii::\$app";
                echo "\nPlease read package documentation: ";
                echo "https://github.com/iiifx-production/yii2-autocomplete-helper\n";
            }
        } catch ( Exception $exception ) {
            echo "\n" . $exception->getMessage();
            echo "\nPlease read package documentation: ";
            echo "https://github.com/iiifx-production/yii2-autocomplete-helper\n";
        }
    }
}
