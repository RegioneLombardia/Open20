<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\favorites
 * @category   CategoryName
 */

namespace open20\amos\favorites;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\favorites\assets\AmosFavoriteAsset;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class AmosFavorites
 * @package open20\amos\favorites
 */
class AmosFavorites extends AmosModule implements ModuleInterface
{
    /**
     * @inheritdoc
     */
    public static $CONFIG_FOLDER = 'config';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\amos\favorites\controllers';

    /**
     * @inheritdoc
     */
    public $name = 'Favorites';

    /**
     * @var array $modelsEnabled
     */
    public $modelsEnabled = [];

    /**
     * @var bool
     */
    public $enableFavoritesUrl = true;

    /**
     * @var array
     */
    public $modelsEnabledFavoritesUrl = [
        'open20\amos\attachments\models\AttachDatabankFile',
        'open20\amos\attachments\models\AttachGalleryImage'
    ];




    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        AmosFavoriteAsset::register(Yii::$app->view);

        Yii::setAlias('@open20/amos/' . self::getModuleName() . '/controllers', __DIR__ . '/controllers/');

        /*
         * Configuration: merge default module configurations loaded from config.php
         * with module configurations set by the application
         */
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');

        Yii::configure($this, ArrayHelper::merge($config, $this));
    }
    /**
     * @return string
     */
    public static function getModuleName()
    {
        return 'favorites';
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [];
    }
}
