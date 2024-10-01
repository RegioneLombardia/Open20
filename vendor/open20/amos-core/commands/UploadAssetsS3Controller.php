<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\commands
 * @category   CategoryName
 */

namespace open20\amos\core\commands;

use open20\amos\core\module\AmosModule;
use lajax\translatemanager\models\Language;
use lajax\translatemanager\models\LanguageSource;
use lajax\translatemanager\models\LanguageTranslate;
use luya\Boot;
use Yii;
use yii\console\Controller;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\log\Logger;

/**
 * Class LanguageSourceController
 * @package open20\amos\core\commands
 */
class UploadAssetsS3Controller extends Controller
{
    //       CONFIGURA CONTROLLER MAP per poterlo eseguire
    //       aggiungere controllerMap in console/config/main
    //      'uploadassetss3' => [
    //            'class' => 'open20\amos\core\commands\UploadAssetsS3Controller',
    //        ],

    /**
     * @return int
     */
    public function actionUploadAssets()
    {
        $assets = $this->getAssetsToUpload();
        error_reporting(0);
        chdir(\Yii::getAlias('@app') . '/runtime');
        if (!empty(\Yii::$app->get('assetManager')) && !empty(\Yii::$app->assetManager->filesystem)) {
            foreach ($assets as $asset) {
                print_r("#### Inizio generazione: " . $asset . "\n");
                \Yii::$app->assetManager->loadBundleConsole($asset);
                print_r("Fine generazione ##### \n");
            }
        }

        return 0;
    }

    public function getAssetsFromPlatform()
    {
        $directories = [
            \Yii::getAlias('@frontend'),
            \Yii::getAlias('@backend'),
            \Yii::getAlias('@common'),
        ];

        $files = [];
        foreach ($directories as $directoryPath) {

            $Directory = new \RecursiveDirectoryIterator($directoryPath);
            $Iterator = new \RecursiveIteratorIterator($Directory);
            $Regex = new \RegexIterator($Iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
            foreach ($Regex as $info) {
                if (!empty($info[0])) {
                    if (strpos($info[0], 'Asset') !== false) {
                        $explode = explode('/', $info[0]);
                        if (!empty($explode)) {
                            $classname = end($explode);
                            $classname = str_replace('.php', '', $classname);

                            $content = file_get_contents($info[0]);
                            $pattern = '/namespace[ ]+(?<namespace>[a-zA-z\\\\]+)/m';
                            $matches = [];
                            // Esegui il preg_match per trovare il namespace
                            if (preg_match($pattern, $content, $matches)) {
                                if (!empty($matches[1])) {
                                    $namespace = $matches[1];
                                    $files[$namespace . "\\" . $classname] = $info[0];
                                    if(!class_exists($namespace . "\\" . $classname)) {
                                        require $info[0];
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
        return $files;
//        Console::stdout(VarDumper::dumpAsString($files, 5) . PHP_EOL);
//        die;
    }

    /**
     * Use Autoload to get all classes anche choose the assets to upload to aws s3
     * @return array
     */
    public function getAssetsToUpload()
    {
        $extra = [];
        $errors = [];
        $assets = [];
        $j = 0;
        $i = 0;
        Yii::setAlias('@luya', $this->getCoreBasePath());
        $classes = require \Yii::getAlias('@vendor') . '/composer/autoload_classmap.php';
        $classesPlatform = $this->getAssetsFromPlatform();
        $classes = ArrayHelper::merge($classes, $classesPlatform);

//        Console::stdout(VarDumper::dumpAsString($classes, 5) . PHP_EOL);
//die;
        // cycle all classes
        foreach ($classes as $class => $path) {
            Console::stdout($class . PHP_EOL);

            $i++;
            try {
                // select only classes with 'Asset' in their name
                if (strpos($class, 'Asset') !== false) {
                    $parentClass = get_parent_class($class);
                    // if the class parent is AssetBundle is ok

                    if ($parentClass == 'yii\web\AssetBundle' || $parentClass == 'luya\web\Asset') {
                        $j++;
                        $assets [] = $class;
                        Console::stdout($j . ' - ' . $class . PHP_EOL);
                        // if the parent is not direct parent initialize the object and check if extends the AssetBundle class
                    } else {
                        try {
                            $newClass = new $class();
                            if (is_subclass_of($newClass, 'yii\web\AssetBundle') || is_subclass_of($newClass, 'luya\web\Asset')) {
                                $j++;
                                $assets [] = $class;
                                Console::stdout($j . '##########' . $class . PHP_EOL);
                            }
                        } catch (\yii\base\InvalidConfigException $e) {
                            $extra[] = $class;
                            $errors[$class] = $e->getMessage();
                        } catch (\yii\base\InvalidArgumentException $e) {
                            $extra[] = $class;
                            $errors[$class] = $e->getMessage();
                        } catch (\yii\base\Exception $e) {
                            $extra[] = $class;
                            $errors[$class] = $e->getMessage();
                        }
                    }
                }
            } catch (\Error $e) {
                $extra[] = $class;
                $errors[$class] = $e->getMessage();
            } catch (\Exception $e) {
                $extra[] = $class;
                $errors[$class] = $e->getMessage();
            }
        }

        Console::stdout('-------------- TOT ' . $j . ' / ' . $i . PHP_EOL);
        Console::stdout(' ------------------------------------ ' . PHP_EOL);
        Console::stdout(' ------------------------------------ ' . PHP_EOL);
        Console::stdout(' ERROR IN CLASSES ' . PHP_EOL);
        foreach ($extra as $k => $c) {
            Console::stdout($k . ' - ' . $c . ' -> ' . get_parent_class($c) . PHP_EOL);
            if (!empty($errors[$c])) {
                Console::stdout('#' . $errors[$c] . PHP_EOL);
            }
        }
        Console::stdout(' ------------------------------------ ' . PHP_EOL);
        return $assets;
    }

    /**
     * @return string
     */
    public function getCoreBasePath()
    {
        $reflector = new \ReflectionClass(Boot::class);
        return dirname($reflector->getFileName());
    }
}
