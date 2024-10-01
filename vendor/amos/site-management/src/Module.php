<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement
 * @category   CategoryName
 */

namespace amos\sitemanagement;

use open20\amos\core\interfaces\CmsModuleInterface;
use amos\sitemanagement\models\SiteManagementLanding;
use amos\sitemanagement\models\SiteManagementLandingPubblication;
use amos\sitemanagement\utility\SiteManagementUtility;
use amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard;
use amos\sitemanagement\widgets\icons\WidgetIconSiteManagementMetadata;
use amos\sitemanagement\widgets\icons\WidgetIconSiteManagementPageContent;
use open20\amos\core\controllers\AmosController;
use open20\amos\core\controllers\BaseController;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\helpers\Url;

/**
 * Class Module
 * @package amos\sitemanagement
 */
class Module extends AmosModule implements ModuleInterface, BootstrapInterface, CmsModuleInterface {

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';
    public $defaultSliderView = '@vendor/amos/site-management/src/widgets/views/slider';
    public $defaultContainerView = '@vendor/amos/site-management/src/widgets/views/elements_container';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'amos\sitemanagement\controllers';
    public $newFileMode = 0666;
    public $name = 'Gestione';

    /** Used to define the classes of  Users / ROLES that che  see a pubblicated element
     * @var array
     */
    public $blackListClasses = [];
    public $whiteListClasses = ['BASIC_USER', 'VALIDATED_BASIC_USER', 'SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR'];

    /** Used to define the classes of  Users / ROLES that che  see a pubblicated element
     * @var array
     */
    public $blackListModuleRoutes = [];

    /**
     * @var array used in the landing advertising
     */
    public $whiteListModuleRoutes = [];

    /**
     * @var int
     */
    public $expireDaysLandingCookie = 1;
    public $contentModelsEnabled = [
        'news' => 'open20\amos\news\models\News',
//        'discussioni' => 'open20\amos\discussioni\models\DiscussioniTopic',
//        'events' => 'open20\amos\events\models\Event',
//        'documenti' => 'open20\amos\documenti\models\Documenti'
    ];
    public $urlDetailModelsEnabled = [
        'news' => '/site/news-detail',
    ];

    /** @var bool  */
    public $enableFixedContainers = false;

    /**
     * @var string
     */
    public $directoryForUploadVideo = '@frontend/web/slider_videos';

    /**
     * @var bool
     */
    public $enableUploadVideoSlider = true;

    /**
     * Switch for all secondary image/video fileds rendering
     * 
     * @var bool
     */
    public $enableInstagramVideoSlider = true;

    /**
     * @var bool
     */
    public $enableTextSlider = true;
    
    /**
     * 
     * @var bool
     */
    public $enableFieldForCms = false;

    /**
     * Secondary fields are configurables
     * for now only the rendering in the form
     *
     * @var array
     */
    public $secondaryImagesFieldListConfiguration = [
        'link' => [
            'render' => true,
        ],
        'text_position' => [
            'render' => true,
        ],
        'description' => [
            'render' => true,
        ],
    ];

    /**
     * Secondary fields are configurables
     * for now only the rendering in the form
     *
     * @var array
     */
    public $secondaryVideosFieldListConfiguration = [
        'link' => [
            'render' => true,
        ],
        'text_position' => [
            'render' => true,
        ],
        'description' => [
            'render' => true,
        ],
    ];

    /**
     * @var array
     * // example 'PERMISSION' => 'Label'
     * [
     *    'ADMIN' => 'Admin',
     *    'SITE_MANAGEMENT_REDACTOR' => 'Redactor',
     * ]
     */
    public $enabledPermissionsForUpdate = [];

    /**
     * Order the contents starting from the last insert
     * @var type $orderContentByLastInsert
     */
    public $orderContentByLastInsert = false;

    /**
     * @example [[['fileImage'], 'required'], ...]
     * @var array
     */
    public $siteManagementSliderElemAddRules = [];

    /**
     * @inheritdoc
     */
    public static function getModuleName() {
        return 'sitemanagement';
    }

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        \Yii::setAlias('@amos/' . static::getModuleName() . '/controllers/', __DIR__ . '/controllers/');
        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons() {
        return [
            WidgetIconSiteManagementDashboard::className(),
            WidgetIconSiteManagementPageContent::className(),
            WidgetIconSiteManagementMetadata::className(),
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
            'PageContent' => __NAMESPACE__ . '\\' . 'models\PageContent',
            'Metadata' => __NAMESPACE__ . '\\' . 'models\Metadata',
            'MetadataType' => __NAMESPACE__ . '\\' . 'models\MetadataType',
            'PageContentSearch' => __NAMESPACE__ . '\\' . 'models\search\PageContentSearch',
            'MetadataSearch' => __NAMESPACE__ . '\\' . 'models\search\MetadataSearch',
            'SiteManagementSliderSearch' => __NAMESPACE__ . '\\' . 'models\search\SiteManagementSliderSearch',
        ];
    }

    /**
     * This method return the session key that must be used to add in session
     * the url from the user have started the content creation.
     * @return string
     */
    public static function beginCreateNewSessionKey() {
        return 'beginCreateNewUrl_' . self::getModuleName();
    }

    /**
     * This method return the session key that must be used to add in session
     * the url date and time creation from the user have started the content creation.
     * @return string
     */
    public static function beginCreateNewSessionKeyDateTime() {
        return 'beginCreateNewUrlDateTime_' . self::getModuleName();
    }

    /**
     * @return string
     */
    public static function externalPreviousUrlSessionKey() {
        return 'externalPreviousUrlSessionKey_' . self::getModuleName();
    }

    /**
     * @return string
     */
    public static function externalPreviousTitleSessionKey() {
        return 'externalPreviousTitleSessionKey_' . self::getModuleName();
    }

    /**
     * @param string $previousUrl
     * @param string $previousTitle
     */
    public static function setExternalPreviousSessionKeys($previousUrl, $previousTitle) {
        \Yii::$app->session->set(self::externalPreviousTitleSessionKey(), $previousTitle);
        \Yii::$app->session->set(self::externalPreviousUrlSessionKey(), $previousUrl);
    }

    /**
     * This method register the metadata in the application.
     */
    public function registerMetadata() {
        SiteManagementUtility::registerMetadata();
    }

    /**
     * @return array
     */
    public function getAvailableContentModels() {
        $modelClasses = $this->contentModelsEnabled;
        $availableModels = [];
        foreach ($modelClasses as $moduleName => $className) {
            if (!empty(\Yii::$app->getModule($moduleName))) {
                $availableModels [$className] = $className;
            };
        }
        return $availableModels;
    }

    /**
     * @param $classContentModel
     * @return null|\yii\base\Module
     */
    public function getModuleOfContentModel($classContentModel) {
        $key = array_search($classContentModel, $this->contentModelsEnabled);
        if ($key) {
            return \Yii::$app->getModule($key);
        }
        return null;
    }

    public function bootstrap($app) {
        Event::on(AmosController::className(), AmosController::EVENT_BEFORE_ACTION, [$this, 'redirectToLanding']);
    }

    public function redirectToLanding($event) {
        $actual_link = "/" . \Yii::$app->controller->action->getUniqueId();

        $pubbl = SiteManagementLandingPubblication::find()->innerJoinWith('landing')->andWhere(['site_management_landing_pubblication.url' => $actual_link])->one();
        if (empty($pubbl) && strpos($actual_link, 'index') > 0) {
            $actual_link = str_replace('/index', '', $actual_link);
            $pubbl = SiteManagementLandingPubblication::find()->innerJoinWith('landing')->andWhere(['site_management_landing_pubblication.url' => $actual_link])->one();
        }
        if ($pubbl && $this->checkPubblicationDate($pubbl)) {
            $cookies = \Yii::$app->request->cookies;
            $cookies->readOnly = false;
            $cookie = $cookies->get('sm-adv-' . $pubbl->id);
            if (empty($cookie)) {
                $responseCookies = \Yii::$app->response->cookies;
                $responseCookies->add(new \yii\web\Cookie([
                            'name' => 'sm-adv-' . $pubbl->id,
                            'value' => 'true',
//                    'expire' => time()+60
                            'expire' => time() + 60 * 60 * 24 * $pubbl->landing->expire_days_cookie
                ]));

                if (strpos($pubbl->landing->view_path, '@') >= 0) {
                    return \Yii::$app->controller->redirect(['/sitemanagement/landing-page/index', 'id' => $pubbl->landing->id]);
                } else if (!empty($pubbl->landing->view_path)) {
                    return \Yii::$app->controller->redirect([$pubbl->landing->view_path]);
                }
            }
        }
    }

    /**
     * @param $pubblication
     * @return bool
     *
     */
    public function checkPubblicationDate($pubblication) {
        $today = new \DateTime();
        $startDate = null;
        $endDate = null;

        if ($pubblication->start_date) {
            $startDate = new \DateTime($pubblication->start_date);
        }

        if ($pubblication->end_date) {
            $endDate = new \DateTime($pubblication->end_date);
        }

        // if is not set a pubblication date is always visible
        // if is set only the start_date is visible from the date selected onward
        // if is set also the end-date check the complete interval
        if ($startDate && $today >= $startDate) {
            // if the hoour of pubblication is not inside the time interval you connot visualize the element
            if ($pubblication->start_time && $pubblication->end_time && !(date('H:i:s') >= $pubblication->start_time && date('H:i:s') <= $pubblication->end_time)) {
                return false;
            }
            if ($endDate) {
                return ($today <= $endDate);
            }
            return true;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabledFixedContainer() {
        if (\Yii::$app->user->can('SITE_MANAGEMENT_TEMPLATE_CREATOR')) {
            return false;
        } else {
            return $this->enableFixedContainers;
        }
    }

    /**
     * @return mixed
     */
    public function getFileNamesDirectoryForUploadVideo() {
        $path = \Yii::getAlias($this->directoryForUploadVideo);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $directoryWeb = '';
        $explode = explode('/', $path);
        if (count($explode) > 0) {
            $directoryWeb = '/' . end($explode);
        }
        $files = [];
        $filesFromDirectry = \yii\helpers\FileHelper::findFiles($path);
        foreach ($filesFromDirectry as $key => $file) {
            $filename = str_replace($path . '\\', '', $file);
            $files[$directoryWeb . '/' . $filename] = $filename;
        }
        return $files;
    }

    /**
     * @inheritdoc
     */
    public static function getModelClassName() {
        return models\SiteManagementSection::className();
    }

    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName() {
        return models\search\SiteManagementSectionSearch::className();
    }

}
