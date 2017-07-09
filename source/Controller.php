<?php
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2017
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

use Exception;
use Yii;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

/**
 * Class Controller
 *
 * @package iiifx\Yii2\Autocomplete
 */
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
     * @var Detector
     */
    protected $detector;

    /**
     * @inheritdoc
     */
    public function options ( $actionID = null )
    {
        return [
            'component',
            'config',
        ];
    }

    /**
     * @inheritdoc
     */
    public function optionAliases ()
    {
        return [];
    }

    public function echoInfo ()
    {
        echo 'Yii2 IDE Autocomplete Helper' . PHP_EOL . 'Vitaliy IIIFX Khomenko, 2017' . PHP_EOL;
    }

    /**
     * Точка входа
     */
    public function actionIndex ()
    {
        $this->echoInfo();
        try {
            $component = $this->getComponent();
            $configList = $this->getConfig( $component );
            $config = new Config( [
                'files' => $configList,
            ] );
            $builder = new Builder( [
                'components' => $config->getComponents(),
                'template' => require __DIR__ . '/template.php',
            ] );
            if ( $component->result === null ) {
                $component->result = ( $this->getDetector()->detect() === 'basic' ) ?
                    '@app/_ide_components.php' :
                    '@console/../_ide_components.php';
            }
            $result = Yii::getAlias( $component->result );
            $result = FileHelper::normalizePath( $result );
            if ( $builder->build( $result ) ) {
                echo PHP_EOL . 'Success: ' . $result;
            } else {
                echo PHP_EOL . 'Fail!';
            }
        } catch ( Exception $exception ) {
            echo PHP_EOL . $exception->getMessage() .
                PHP_EOL . 'Please read the package documentation: https://github.com/iiifx-production/yii2-autocomplete-helper' .
                PHP_EOL;
        }
    }

    /**
     * @return Component
     *
     * @throws InvalidConfigException
     */
    protected function getComponent ()
    {
        if ( isset( Yii::$app->{$this->component} ) && Yii::$app->{$this->component} instanceof Component ) {
            return Yii::$app->{$this->component};
        }
        throw new InvalidConfigException( "Component '{$this->component}' not found in Yii::\$app" );
    }

    /**
     * @return Detector
     */
    protected function getDetector ()
    {
        if ( $this->detector === null ) {
            $this->detector = new Detector( [
                'app' => Yii::$app,
            ] );
        }
        return $this->detector;
    }

    /**
     * @param Component $component
     *
     * @return array
     *
     * @throws InvalidCallException
     */
    protected function getConfig ( Component $component )
    {
        if ( $component->config === null ) {
            if ( $this->getDetector()->detect() === false ) {
                throw new InvalidCallException( 'Unable to determine application type' );
            }
            $configList = $this->getDetector()->getConfig();
        } else {
            if ( $this->config === null ) {
                if ( isset( $component->config[ 0 ] ) ) {
                    $configList = $component->config;
                } else {
                    throw new InvalidCallException( 'Default config list not found in component config data' );
                }
            } else {
                if ( isset( $component->config[ $this->config ] ) ) {
                    $configList = $component->config[ $this->config ];
                } else {
                    throw new InvalidCallException( "Scope '{$this->config}' not found in component config data" );
                }
            }
        }
        return $configList;
    }
}
