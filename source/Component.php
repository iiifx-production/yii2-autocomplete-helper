<?php declare(strict_types=1);
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2021
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;

class Component extends BaseObject implements BootstrapInterface
{
    public string $environment = 'dev';
    public array $controllerMap = [
        'ide-components' => Controller::class,
    ];
    public ?string $result = null;
    public array $config = [];

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application && $this->isActive()) {
            $this->updateControllerMap($app);
        }
    }

    public function isActive(): bool
    {
        return defined('YII_ENV') && YII_ENV === $this->environment;
    }

    protected function updateControllerMap(Application $app): void
    {
        if (is_array($this->controllerMap)) {
            foreach ($this->controllerMap as $name => $controller) {
                if (is_subclass_of($controller, \yii\console\Controller::class)) {
                    $app->controllerMap[$name] = $controller;
                }
            }
        }
    }
}
