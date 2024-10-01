<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\helpers\base;

use open20\amos\core\module\AmosModule;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\base\ModelConfig;
use hanneskod\classtools\Iterator\ClassIterator;
use Symfony\Component\Finder\Finder;
use yii\helpers\ArrayHelper;
use Yii;
use yii\log\Logger;

/**
 *
 */
class BaseEntitiesHelper
{
    /**
     * 
     */
    const INTERFACE_CONTENT = '\open20\amos\core\interfaces\ContentModelInterface';
    const INTERFACE_NETWORK = '\open20\amos\cwh\base\ModelNetworkInterface';

    /**
     * Get classname listed by module name that implements {{$interfaceClassname}}
     *
     * @param $interfaceClassname
     * @return array
     */
    public static function getEntities($interfaceClassname)
    {
        $entities = [];
        $excludeNetwork = false;
        if ($interfaceClassname == self::INTERFACE_CONTENT) {
            $excludeNetwork = true;
        }

        /**
         * TODO
         * bonificare anche i seguenti plugin
         */
        // $pluginBlacklisted = [
        //     'admin', //non perché sia da bonificare dagli errori ma perché non da considerare come contenitore modelli di rete/ di contenuti
        //     'upload',
        //     'aliases',
        //     'file',
        //     'myactivities',
        //     'proposte_collaborazione',
        //     'uikit'
        // ];

        $pluginBlacklisted = AmosCwh::instance()->pluginBlacklisted;

        foreach (\Yii::$app->getModules() as $moduleName => $module) {

            if (ArrayHelper::isIn($moduleName, $pluginBlacklisted)) {
                continue;
            }

            /**@var AmosModule $module */
            if (!$module instanceof AmosModule) {
                // TBD, Luya modules and view create an issue to parse
                \Yii::getLogger()->log(['NOT AmosModule!', $module], Logger::LEVEL_ERROR);
                continue;
                // $module = \Yii::$app->getModule($moduleName);
            }

            try {
                $finder = new Finder();
                $iter = new ClassIterator(
                    $finder
                        ->name('*.php')
                        ->notPath('migration')
                        ->notPath('views')
                        ->notPath('widgets')
                        ->contains($moduleName)
                        ->notContains('\search;')
                        ->in($module->getBasePath())
                );

                foreach ($iter->getClassMap() as $classname => $splFileInfo) {
                    if (class_exists($classname)) {
                        $refClass = new \ReflectionClass($classname);
                        if (
                            !$refClass->isInterface()
                            && $refClass->implementsInterface($interfaceClassname)
                            && !$refClass->isAbstract()
                        ) {
                            if (
                                !$excludeNetwork
                                || !$refClass->implementsInterface(self::INTERFACE_NETWORK)
                            ) {
                                $entities[] = new ModelConfig([
                                    'classname' => $classname,
                                    'tablename' => $classname::tableName(),
                                    'label' => $refClass->getShortName(),
                                    'module_id' => $moduleName,
                                    'base_url_config' => self::retreiveUrlConfigClass($interfaceClassname),
                                    'config_class' => self::retreiveConfigClass($interfaceClassname),
                                ]);
                            }
                        }
                    }
                }
            } catch(Exception $ex) {
                Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
            }
        }

        return $entities;
    }

    /**
     *
     * @param [type] $interfaceClassname
     * @return void
     */
    private static function retreiveUrlConfigClass($interfaceClassname)
    {
        if ($interfaceClassname == self::INTERFACE_CONTENT) {
            return '/cwh/configuration/content';
        }
        if ($interfaceClassname == self::INTERFACE_NETWORK) {
            return '/cwh/configuration/network';
        }

        return null;
    }

    /**
     *
     * @param [type] $interfaceClassname
     * @return void
     */
    private static function retreiveConfigClass($interfaceClassname)
    {
        if ($interfaceClassname == self::INTERFACE_CONTENT) {
            return '\open20\amos\cwh\models\CwhConfigContents';
        }

        if ($interfaceClassname == self::INTERFACE_NETWORK) {
            return '\open20\amos\cwh\models\CwhConfig';
        }

        return null;
    }
}
