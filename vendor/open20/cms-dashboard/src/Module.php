<?php

namespace open20\cms\dashboard;

use open20\amos\core\interfaces\BreadcrumbInterface;
use open20\amos\core\module\AmosModule;

/**
 * Description of Module
 *
 */
class Module extends AmosModule implements BreadcrumbInterface {

    /**
     * 
     * @var int
     */
    public $defaultLayoutId = 1;

    /**
     * 
     * @var int
     */
    public $defaultContainerId;

    /**
     * 
     * @var type
     */
    public $enableContainerFilterForEditor = true;

    /**
     *  Elenco dei record che non permettono modifiche nei link (nemmeno dall'admin), nella sidebar redattore
     * @var int[]
     */
    public $sidebarItemBlacklist = [2, 10]; // Dashboard, Gestione portali

    /**
     * 
     * @var bool $enableProfile
     */
    public $enableProfile = false;

    /**
     * @var array[] $mapPermissionContainer
     */
    public $mapPermissionContainer = [
        'REDATTORECMS' => ['default', 'footer'],
        'CAPOREDATTORECMS' => ['default', 'footer', 'system'],
    ];

    /**
     * @var int $maxCmsPageLevel
     */
    public $maxCmsPageLevel = 5;

    /**
     * @var int $defaultTitlePageLength
     */
    public $defaultTitlePageLength = 175;

    /**
     * 
     * @return string
     */
    public static function getModuleName() {
        return 'dashboards';
    }

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        \Yii::setAlias('@open20/cms-dashboard/src/controllers/', __DIR__ . '/controllers/');
        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons() {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics() {
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels() {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getModelClassName() {
        return null;
    }

    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName() {
        return null;
    }

    /**
     * @param type $message
     * @param type $params
     * @return type
     */
    public static function txt($message, $params = []) {
        return \Yii::t('amosdashboards', $message, $params);
    }

    public function getDefaultContainerId() {
        if (empty($this->defaultContainerId)) {
            $container = \luya\cms\models\NavContainer::find()->andWhere(['alias' => 'default'])->andWhere(['is_deleted' => 0])->one();
            $this->defaultContainerId = $container->id;
        }
        return $this->defaultContainerId;
    }

    /**
     * @return array
     */
    public function getIndexActions() {
        return [
            'dashboards/cms-dash-sidebar-item/index',
        ];
    }

    /**
     * @return array
     */
    public function getControllerNames() {
        $names = [
            'cms-dash-sidebar-item' => self::t('amosdashboards', "Sidebar"),
        ];

        return $names;
    }
}
