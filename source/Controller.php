<?php declare(strict_types=1);
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2021
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
    public string $component = 'autocomplete';
    public string $config;
    protected Detector $detector;

    /**
     * @inheritdoc
     */
    public function options($actionID = null): array
    {
        return [
            'component',
            'config',
        ];
    }

    /**
     * @inheritdoc
     */
    public function optionAliases(): array
    {
        return [];
    }

    public function showDescription(): void
    {
        $this->stdout("Yii2 IDE auto-completion helper\n");
        $this->stdout("Vitaliy IIIFX Khomenko, 2021\n\n");
    }

    /**
     * Generate IDE auto-completion file
     */
    public function actionIndex(): void
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

            if (null === $component->result) {
                $component->result = ($this->getDetector()->detect() === 'basic')
                    ? '@app/_ide_components.php'
                    : '@console/../_ide_components.php';
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
    protected function getComponent(): Component
    {
        if (isset(Yii::$app->{$this->component}) && Yii::$app->{$this->component} instanceof Component) {
            return Yii::$app->{$this->component};
        }

        throw new InvalidConfigException(sprintf('Component "%s" not found in Yii::$app', $this->component));
    }

    protected function getDetector(): Detector
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
    protected function getConfig(Component $component): array
    {
        if (null === $component->config) {
            if ($this->getDetector()->detect() === false) {
                throw new InvalidCallException('Unable to determine application type');
            }

            $configList = $this->getDetector()->getConfig();
        } else {
            if (null === $this->config) {
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
