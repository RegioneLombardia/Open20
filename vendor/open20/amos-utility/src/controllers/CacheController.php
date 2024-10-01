<?php

namespace open20\amos\utility\controllers;

use open20\amos\core\commands\LanguageSourceController;
use yii\caching\ApcCache;
use yii\caching\Cache;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;
use Yii;
use open20\amos\utility\Module;

class CacheController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'clean',
                            'clean-assets',
                            'translate'
                        ],
                        'roles' => ['ACCESS_THE_BASEMENT']
                    ],
                ],
            ],
        ];
    }

    public $layout = "main";
    private $dirs = [];

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->setUpLayout();
        $this->setAssetDirs();
        // custom initialization code goes here
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null) {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            $this->layout = '@vendor/open20/amos-core/views/layouts/' . (!empty($layout) ? $layout : $this->layout);
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        return true;
    }

    public function actionClean() {
        return $this->render('index');
    }

    public function actionCleanAssets() {
        return $this->render('index', ['cleanAssets' => true]);
    }

    public function actionTranslate() {
        //New instance of language source controller with update of strings forced
        $languageSource = new LanguageSourceController('language-source', $this->module, ['forceUpdate' => true]);

        return $this->render('translate', [
                    'languageSource' => $languageSource
        ]);
        //./yii language/load-files --forceUpdate
    }

    /**
     * set asset dirs
     */
    public function setAssetDirs() {
        $module = Module::instance();
        if (!$module->disableDeleteFrontendAssets) {
            array_push($this->dirs, Yii::getAlias('@vendor/../frontend/web/assets'));
        }

        if (!$module->disableDeleteBackendAssets) {
            array_push($this->dirs, Yii::getAlias('@vendor/../backend/web/assets'));
        }

        array_push($this->dirs, Yii::getAlias('@vendor/../frontend/runtime/cache'));
        array_push($this->dirs, Yii::getAlias('@vendor/../backend/runtime/cache'));
        array_push($this->dirs, Yii::getAlias('@vendor/../console/runtime/cache'));
    }

    /**
     * clean all asset dirs
     */
    public function cleanAssetDirs() {
        $nbr_cleaned = 0;

        foreach ($this->dirs as $asset_dir) {
            if (!is_dir($asset_dir)) {
                echo '<p>Did not find ' . $asset_dir . '/ .. skipping</p>';
                continue;
            }
            echo '<p>Checking ' . $asset_dir . '/ to remove old caches .. </p>';

            $nbr_cleaned += self::cleanAssetDir($asset_dir);
        }
        echo '<p>Finished</p>';

        return $nbr_cleaned;
    }

    /**
     * clean asset dir
     * may remove subdirs in asset dir
     *
     * @param string $asset_dir
     * @return int
     */
    public function cleanAssetDir($asset_dir) {
        $now = time();

        $asset_temp_dirs = glob($asset_dir . '/*', GLOB_ONLYDIR);

        // check if less than want to keep
        if (!count($asset_temp_dirs)) {
            return 0;
        }

        // get all dirs and sort by modified
        $modified = [];
        foreach ($asset_temp_dirs as $asset_temp_dir) {
            $modified[$asset_temp_dir] = filemtime($asset_temp_dir);
        }
        asort($modified);
        $nbr_dirs = count($modified);

        // keep last dirs
        for ($i = min($nbr_dirs, 0); $i > 0; $i--) {
            array_pop($modified);
        }

        // remove dirs
        foreach ($modified as $dir => $mod) {
            echo '<p>removed ' . $dir . '</p>';
            FileHelper::removeDirectory($dir);
        }

        return $nbr_dirs;
    }

    public function cleanBaseCache() {
        $caches = $this->findCaches();
        $count = 0;

        if (empty($caches)) {
            return "<br>No Cache components....";
        }

        foreach ($caches as $name => $class) {
            $status = $this->canBeFlushed($class) ? Yii::$app->get($name)->flush() : false;

            if ($status) {
                echo '<br>Flushed ' . $name;
                $count++;
            } else {
                echo '<br>Unable to flush ' . $name;
            }
        }

        return $count;
    }

    /**
     * Returns array of caches in the system, keys are cache components names, values are class names.
     * @param array $cachesNames caches to be found
     * @return array
     */
    private function findCaches(array $cachesNames = []) {
        $caches = [];
        $components = Yii::$app->getComponents();
        $findAll = ($cachesNames === []);

        foreach ($components as $name => $component) {
            if (!$findAll && !in_array($name, $cachesNames, true)) {
                continue;
            }

            if ($component instanceof Cache) {
                $caches[$name] = get_class($component);
            } elseif (is_array($component) && isset($component['class']) && $this->isCacheClass($component['class'])) {
                $caches[$name] = $component['class'];
            } elseif (is_string($component) && $this->isCacheClass($component)) {
                $caches[$name] = $component;
            }
        }

        return $caches;
    }

    /**
     * Checks if given class is a Cache class.
     * @param string $className class name.
     * @return bool
     */
    private function isCacheClass($className) {
        return is_subclass_of($className, Cache::className());
    }

    /**
     * Checks if cache of a certain class can be flushed
     * @param string $className class name.
     * @return bool
     */
    private function canBeFlushed($className) {
        return !is_a($className, ApcCache::className(), true) || php_sapi_name() !== "cli";
    }

}
