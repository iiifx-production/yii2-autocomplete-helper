<?php declare(strict_types=1);
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2021
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
    public ?Application $app = null;
    public array $ids = [
        'basic-console' => 'basic',
        'app-console' => 'advanced',
    ];
    public array $configs = [
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

    public function detect(): bool|string
    {
        $application = $this->getApplication();

        if (isset($this->ids[$application->id])) {
            return $this->ids[$application->id];
        }

        return false;
    }

    public function getConfig(): array
    {
        if ($type = $this->detect()) {
            return $this->configs[$type] ?? [];
        }

        return [];
    }

    protected function getApplication(): Application
    {
        if (!$this->app instanceof Application) {
            $this->app = Yii::$app;
        }

        return $this->app;
    }
}
