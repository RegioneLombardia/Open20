<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\news;

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\interfaces\BreadcrumbInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews;
use open20\amos\news\widgets\icons\WidgetIconAllNews;
use open20\amos\news\widgets\icons\WidgetIconNews;
use open20\amos\news\widgets\icons\WidgetIconNewsCategorie;
use open20\amos\news\widgets\icons\WidgetIconNewsCreatedBy;
use open20\amos\news\widgets\icons\WidgetIconNewsDashboard;
use open20\amos\news\widgets\icons\WidgetIconNewsDaValidare;
use yii\console\Application;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;

/**
 * Class AmosNews
 * @package open20\amos\news
 */
class AmosNews extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface, BreadcrumbInterface
{
    const MAX_LAST_NEWS_ON_DASHBOARD = 3;

    public static $CONFIG_FOLDER = 'config';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     * @var string $name
     */
    public $name = 'Notizie';

    /**
     * @var string $controllerNamespace
     */
    public $controllerNamespace = 'open20\amos\news\controllers';

    /**
     * If this attribute is true the validation of the publication date is active
     * @var boolean $validatePublicationDate
     */
    public $validatePublicationDate = true;

    /**
     * @var bool|false $filterCategoriesByRole - if true, enables category role check via table news_category_roles_mm
     */
    public $filterCategoriesByRole = false;

    /**
     * @var array
     */
    public $whiteListRolesCategories = ['ADMIN', 'BASIC_USER'];

    /**
     * @var bool|false $hidePubblicationDate
     */
    public $hidePubblicationDate = false;
    
    /**
     * 
     * @var bool|false
     */
    public $requirePubblicationDate = false;

    /**
     * Hide the Option wheel in the graphic widget
     * @var bool|false $hideWidgetGraphicsActions
     */
    public $hideWidgetGraphicsActions = false;
    
    /**
     * If set to true it enables the link to the users profile 
     * @var bool Default true
     */
    public $enableLinkProfile = true;

    /**
     * @var array $newsRequiredFields - mandatory fields in News form
     */
    public $newsRequiredFields = [
        'news_categorie_id',
        'titolo',
        'status',
        'descrizione',
    ];
    
    /**
     * This param enables the search by tags
     * @var bool $searchByTags
     */
    
    public $searchByTags = false;
	
	
    /**
     * disable the paste_as_text in thexeditor for news description
     * @var bool|false $textEditorClientOptions
     */
    public $textEditorClientOptions = false;
    
    /**
     * disable the paste_as_text in thexeditor for news description only for the list of id in the array
     * @var bool|false $arrayTextEditorClients
     */
    
    public $arrayTextEditorClients = [];

    /**
     * disable menage categories and colum on table
     * @var bool $showCategory
     */
    public $showCategory = true;

     /**
     * The default value for signature
     * @var bool $showSignature
     */
    public $showSignature = true;
    
    /**
     * The ID of the default category pre-selected for the new News
     * @var integer
     */
    public $defaultCategory;

    /**
     * The default value for enable comments
     * @var integer
     */
    public $defaultEnableComments = 1;

    /**
     * @var bool $hideDataRimozioneView
     */
    public $hideDataRimozioneView = false;

    /**
     * @var array $defaultListViews This set the default order for the views in lists
     */
    public $defaultListViews = ['icon', 'grid'];

    /**
     * This set the auto update of the publication date on the save if the news is published
     * @var boolean $autoUpdatePublicationDate
     */
    public $autoUpdatePublicationDate = false;
    
    /**
     * @var string
     */
    public $defaultWidgetIndexUrl = '/news/news/all-news';

    /**
     * @var bool
     */
    public $enableCategoriesForCommunity = false;

    /**
     * @var bool
     */
    public $showAllCategoriesForCommunity = true;
    
    
    /**
     * @var bool
     */
    public $EnableCategoryFilter = true;
    
    /**
     * @var bool
     */
    public $addTags = false;

    /**
     * @var array
     */
    public $viewPathEmailSummary = [
        'open20\amos\news\models\News' => '@vendor/open20/amos-news/src/views/email/notify_summary'
    ];
    
    /**
     * @var string[] $viewPathEmailSummaryNetwork
     */
    public $viewPathEmailSummaryNetwork = [
        'open20\amos\news\models\News' => '@vendor/open20/amos-news/src/views/email/notify_summary_network'
    ];


    /**
     * @var string[]
     */
    public $viewPathCachedEmailSummaryNetwork = [
        'open20\amos\news\models\News' => '@vendor/open20/amos-news/src/views/email/_item_notify_summary_network'
    ];

    /**
     * @var string[]
     */
    public $viewPathCachedEmailSummary = [
        'open20\amos\news\models\News' => '@vendor/open20/amos-news/src/views/email/_item_notify_summary'
    ];


    /*
     * @var bool disableStandardWorkflow Disable standard worflow, direct publish
     */
    public $disableStandardWorkflow = false;

    /**
     * active agid fields
     *
     * @var boolean
     */
    public $enableAgid = false;
    
    /**
     * Enable/Disable correspondent select on _form page
     * @var bool $enableAgidNewsRelatedDocumenti
     */
    public $enableAgidNewsRelatedNews = true;

    /**
     * Enable/Disable correspondent select on _form page
     * @var bool $enableAgidNewsRelatedDocumenti
     */
    public $enableAgidNewsRelatedDocumenti = true;
    
    /**
     * Enable/Disable correspondent select on _form page
     * @var bool $enableAgidNewsRelatedAgidService
     */
    public $enableAgidNewsRelatedAgidService = true;
    
    /**
     * Enable/Disable correspondent select on _form page
     * @var bool $enableAgidReferenti
     */
    public $enableAgidReferenti = true;
    
    /**
     * @var bool $enableAgidAllegati
     */
    public $enableAgidAllegati = true;
    
    /**
     * @var bool $enableAgidNewsContentType
     */
    public $enableAgidNewsContentType = true;
    
    /**
     * Enable/Disable notification on News model
     * @var bool $newsModelsendNotification
     */
    public $newsModelsendNotification = true;

    /*
     * @var int $numberListTag 10 default
     */
    public $numberListTag = 10;

    /**
     * Enable editor plugins
     * @var array
     */
    public $rtePlugins = ['paste link'];

    /**
     * Enable the toolbar buttons
     * @var string
     */
    public $rteToolbar = 'undo redo | link';
    
    /**
     * @var bool
     */
    public $request_publish_on_hp = false;

    /**
     * hide block on _form relative to seo module even if it is present
     * @var bool
     */
    public $hideSeoModule = false;

    /**
     * @var bool
     */
    public $enableOtherNewsCategories = false;

    /**
     * limit of news show in WidgetGraphicsCmsUltimeNews on dashboardEngine case
     *
     * @var integer
     */
    public $wgCmsUltimeNewsDashboardEngineNewLimit = 12;
    
    /**
     * @var bool $disableBefeControllerRules Enable this property to disable the BEFE rules in controller behaviors.
     */
    public $disableBefeControllerRules = false;
    
    /**
     * @var bool $enableCustomOrderFields Enable this property to let user to choose which order fields to show
     * 
     * list of fields aviable are the same in params - fields
     * 
     */
    public $enableCustomOrderFields = false;

    /**
     * @var bool
     */
    public $enablePrimoPianoOnCommunity = false;

    /**
     * @var bool
     */
    public $disableFormParamPrimoPiano = false;

    /**
     * @var bool
     */
    public $enableOnlyRedactional = false;
    
    /**
     * 
     * @var bool
     */
    public $enableFreeTags = false;

     /** Enable/Disable correspondent select on _form page
     * @var bool $enableGalleryAttachment
     */
    public $enableGalleryAttachment = false;

    /**
     * Enable/Disable correspondent select on _form page
     * @var bool $enableRelateEvents
     */
    public $enableRelateEvents = false;

    /**
     * Format of the date used for publication range (start + end)
     * @var string $dateFormatNews
     */
    public $dateFormatNews = DateControl::FORMAT_DATE;

    /**
     * @var bool $enableLikeWidget
     */
    public $enableLikeWidget = true;

    /**
     * @var bool $enableCustomStatusLabel
     */
    public $enableCustomStatusLabel = false;

    /**
     * Enable news download in console with plugin open20/taskmanager instead of standard download with ExportMenu.
     * Useful if you have a lot of news to download and ExportMenu goes in timeout.
     * In order to work, you have to install open20/taskmanager plugin
     * and configure $backgroundDownloadOptions array param if you need to change options.
     * @var bool $enableCustomActivities
     */
    public $enableBackgroundDownload = false;

    /**
     * Options for background download.
     * @var array $backgroundDownloadOptions
     */
    public $backgroundDownloadOptions = [
        'exportAction' => '/news/news/extract-news',
        'redirectUrl' => '/taskmanager',
    ];

    /**
     * Hide download button in redaction index view.
     * @var bool $hideDownloadRedactionIndex
     */
    public $hideDownloadRedactionIndex = true;

    /**
     * Enable the function to clone platform news into communities from the community open-join view.
     * @var bool $enableCloningIntoCommunities
     */
    public $enableCloningIntoCommunities = false;

    /**
     * Hide download button in index view for guests.
     * @var bool $hideDownloadIndexForGuests
     */
    public $hideDownloadIndexForGuests = false;

    /**
     * Hide change view type button in index view for guests.
     * @var bool $hideChangeViewTypeIndexForGuests
     */
    public $hideChangeViewTypeIndexForGuests = false;

    /**
     * Enable news creation with point of interest and search by point of interest.
     * @var bool $enableMap
     */
    public $enableMap = false;

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */ 
    public static function getModelSearchClassName()
    {
        return AmosNews::instance()->model('NewsSearch');
    }

    /**
     * @inheritdoc
     */
    public static function getModelClassName()
    {
        return AmosNews::instance()->model('News');
    }

    /**
     * @inheritdoc
     */
    public static function getModuleIconName()
    {
        return 'feed';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers');

        //Configuration: merge default module configurations loaded from config.php with module configurations set by the application
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
        \Yii::configure($this, ArrayHelper::merge($config, $this));
        if (\Yii::$app instanceof Application) {
            $this->controllerNamespace = 'open20\amos\news\commands\controllers';
        }
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [
            WidgetIconNews::class,
            WidgetIconNewsCategorie::class,
            WidgetIconNewsCreatedBy::class,
            WidgetIconNewsDaValidare::class,
            WidgetIconNewsDashboard::class,
            WidgetIconAllNews::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return [
            WidgetGraphicsUltimeNews::class,
        ];
    }

    /**
     * Get default model classes
     */
    protected function getDefaultModels()
    {
        return [
            'News' => __NAMESPACE__ . '\\' . 'models\News',
            'NewsCategorie' => __NAMESPACE__ . '\\' . 'models\NewsCategorie',
            'NewsSearch' => __NAMESPACE__ . '\\' . 'models\search\NewsSearch',
        ];
    }

    /**
     * This method return the session key that must be used to add in session
     * the url from the user have started the content creation.
     * @return string
     */
    public static function beginCreateNewSessionKey()
    {
        return 'beginCreateNewUrl_' . self::getModuleName();
    }

    /**
     *
     * @return string
     */
    public function getFrontEndMenu($dept = 1)
    {
        $menu = parent::getFrontEndMenu();
        $app  = \Yii::$app;
        if (!$app->user->isGuest && (\Yii::$app->user->can('LETTORE_NEWS')||\Yii::$app->user->can('REDACTOR_NEWS')) ) {
            $menu .= $this->addFrontEndMenu(AmosNews::t('amosnews','#menu_front_news'), AmosNews::toUrlModule('/news/all-news'),$dept);
        }
        return $menu;
    }


    /**
     * @return array
     */
    public function getIndexActions()
    {
        return [
            'news/index',
            'news-categorie/index',
            'news/all-news',
            'news/redaction-all-news',
            'news/own-news',
            'news/admin-all-news',
            'news/to-validate-news',
            'news/own-interest-news'
        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRoute()
    {
        return [
            'news' => '/news/news/own-interest-news',
        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRouteSlogged()
    {
        return [
            'news' => '/news/news/all-news',
        ];
    }

    /**
     * @return array
     */
    public function getControllerNames()
    {
        $names = [
            'news' => self::t('amosnews', "News"),
            'news-categorie' => self::t('amosnews', "Categorie notizie"),
        ];

        return $names;
    }
}
