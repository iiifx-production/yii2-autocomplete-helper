<?php
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2019
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

use Exception;
use Yii;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/**
 * Automatically generate IDE auto-completion file
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
    public function options($actionID = null)
    {
        return [
            'component',
            'config',
        ];
    }

    /**
     * @inheritdoc
     */
    public function optionAliases()
    {
        return [];
    }

    /**
     * Shows description
     */
    public function showDescription()
    {
        $this->stdout("Yii2 IDE auto-completion helper\n");
        $this->stdout("Vitaliy IIIFX Khomenko, 2019\n\n");
    }

    /**
     * Generate IDE auto-completion file
     */
    public function actionIndex()
    {
        $this->showDescription();
        try {
            $component = $this->getComponent();
            $configList = $this->getConfig($component);
            $config = new Config([
                'files' => $configList,
            ]);
            $builder = new Builder([
                'components' => $config->getComponents(),
                'template' => require __DIR__ . '/template.php',
            ]);
            if ($component->result === null) {
                $component->result = ($this->getDetector()->detect() === 'basic') ?
                    '@app/_ide_components.php' :
                    '@console/../_ide_components.php';
            }
            $result = Yii::getAlias($component->result);
            $result = FileHelper::normalizePath($result);
            if ($builder->build($result)) {
                $this->stdout("Success: {$result}\n", Console::FG_GREEN);
            } else {
                $this->stdout("Fail!\n", Console::FG_RED);
            }
        } catch (Exception $exception) {
            $this->stdout($exception->getMessage() . "\n\n", Console::FG_RED);
            $this->stdout("Please read the package documentation: https://github.com/iiifx-production/yii2-autocomplete-helper\n");
            $this->stdout("or create new issue: https://github.com/iiifx-production/yii2-autocomplete-helper/issues/new\n");
        }
    }

    /**
     * @return Component
     *
     * @throws InvalidConfigException
     */
    protected function getComponent()
    {
        if (isset(Yii::$app->{$this->component}) && Yii::$app->{$this->component} instanceof Component) {
            return Yii::$app->{$this->component};
        }
        throw new InvalidConfigException(sprintf('Component "%s" not found in Yii::$app', $this->component));
    }

    /**
     * @return Detector
     */
    protected function getDetector()
    {
        if ($this->detector === null) {
            $this->detector = new Detector([
                'app' => Yii::$app,
            ]);
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
    protected function getConfig(Component $component)
    {
        if ($component->config === null) {
            if ($this->getDetector()->detect() === false) {
                throw new InvalidCallException('Unable to determine application type');
            }
            $configList = $this->getDetector()->getConfig();
        } else {
            if ($this->config === null) {
                if (isset($component->config[0])) {
                    $configList = $component->config;
                } else {
                    throw new InvalidCallException('Default config list not found in component config data');
                }
            } else {
                if (isset($component->config[$this->config])) {
                    $configList = $component->config[$this->config];
                } else {
                    throw new InvalidCallException(sprintf('Scope "%s" not found in component config data', $this->config));
                }
            }
        }
        return $configList;
    }
}
