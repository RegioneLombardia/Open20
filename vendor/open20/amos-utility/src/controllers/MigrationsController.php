<?php

namespace open20\amos\utility\controllers;


/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

use open20\amos\core\migration\MigrateController;
use Yii;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\web\Controller;
use yii\filters\AccessControl;

class MigrationsController extends MigrateController
{
    private function setupFakeConsole()
    {
        $configFiles = [
            Yii::getAlias('@vendor/../console/config/migrations.php'),
            Yii::getAlias('@vendor/../console/config/migrations-amos.php'),
            Yii::getAlias('@vendor/../console/config/migrations-others.php')
        ];

        foreach($configFiles as $config) {
            if(!file_exists($config)) {
                continue;
            }

            $this->migrationLookup = ArrayHelper::merge(
                $this->migrationLookup,
                require($config)
            );
        }

        $this->migrationLookup[] = Yii::getAlias('@vendor/../console/migrations');

        defined('STDOUT') || define('STDOUT', fopen('php://stdin', 'r'));
        defined('STDIN') || define('STDIN', fopen('php://stdin', 'r'));

        $this->beforeAction(Yii::$app->controller->action);
    }

    public function getMigrations()
    {
        $this->setupFakeConsole();
        return $this->getNewMigrations();
    }

    protected function createMigration($class)
    {
        $file = $this->getMigrationFiles()[$class];
        require_once($file);

        if(class_exists($class)) {
            return new $class(['db' => $this->db]);
        } else {
            throw new \Exception("The class {$class} does not exists");
        }
    }

    public function applyMigrations()
    {
        $result = "";

        ob_start();
        $this->setupFakeConsole();

        $migrations = $this->getMigrations();


        foreach ($migrations as $migration) {
            $ms = null;
            echo "<p>Running {$migration}</p>";

            try {
                $ms = $this->migrateUp($migration);
            } catch (\Exception $e) {
                $result = $e->getMessage();
            }

            if (!$ms) {
                $this->stdout("\n$applied from $n " . ($applied === 1 ? 'migration was' : 'migrations were') . " applied.\n", Console::FG_RED);
                $this->stdout("\nMigration failed. The rest of the migrations are canceled.\n", Console::FG_RED);
            }
            $applied++;
        }

        $result .= ob_get_clean();


        return $result;
    }
}