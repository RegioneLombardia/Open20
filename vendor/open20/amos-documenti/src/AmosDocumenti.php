<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti
 * @category   CategoryName
 */

namespace open20\amos\documenti;

use open20\amos\core\exceptions\AmosException;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocuments;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti;
use open20\amos\documenti\widgets\icons\WidgetIconAdminAllDocumenti;
use open20\amos\documenti\widgets\icons\WidgetIconAllDocumenti;
use open20\amos\documenti\widgets\icons\WidgetIconDocumenti;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiCategorie;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiCreatedBy;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiDaValidare;
use kartik\datecontrol\DateControl;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\BreadcrumbInterface;

/**
 * Class AmosDocumenti
 * @package open20\amos\documenti
 */
class AmosDocumenti extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface, BreadcrumbInterface {

    /**
     *
     */
    public static $CONFIG_FOLDER = 'config';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     *
     */
    public $name = 'Documenti';

    /**
     *
     */
    public $controllerNamespace = 'open20\amos\documenti\controllers';

    /**
     * @var bool|false if document foldering is enabled or not
     */
    public $enableFolders = false;

    /**
     * @var bool|true if document categories are enabled or not
     */
    public $enableCategories = true;

    /**
     * @var bool Show categories in document view
     */
    public $showCategoriesInView = false;

    /**
     * @var array
     */
    public $whiteListRolesCategories = ['ADMIN', 'BASIC_USER'];

    /**
     * @var bool $enableDocumentVersioning If true enable the versioning of the documents. The folders aren't versioned.
     */
    public $enableDocumentVersioning = false;
    
    
    /**
     * 
     * @var bool $enableDragAndDrop If true enable the drag and drop of the file documents.
     */
    public $enableDragAndDrop = true;

    /**
     * @var array $defaultRequired - default required fields in document form
     */
    public $defaultRequired = [
        'titolo',
        'status',
        'descrizione_breve',       
    ];
    
    /**
     * @var array $documentMainFile - default required fields in document form
     */
    public $documentMainFileRequired = true;

    /**
     * @var array $documentExtraRequiredFields - extra mandatory fields in document form
     */
    public $documentExtraRequiredFields = [];

    /**
     * @var string List of the allowed extensions for the upload of files.
     */
    public $whiteListFilesExtensions = 'csv,doc,docx,pdf,rtf,txt,xls,xlsx,odt';

    /**
     * @var bool
     */
    public $enableExtensionFilter = true;

    /**
     * @var bool Enables folder search
     */
    public $enableFoldersFilter = true;

    /**
     * @var string List of the allowed mime types.
     */
    public $mimeTypes = 'text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,
	application/pdf,application/rtf,text/rtf,application/x-rtf,text/richtext,text/plain,application/vnd.ms-excel,application/x-excel,application/excel,
	application/x-msexcel,application/vnd.ms-office,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.text,application/vnd.openxmlformats-officedocument.presentationml.presentation';

    /**
     * 
     * @var bool $enableCheckMimeType
     */
    public $enableCheckMimeType = true;
    /**
     * @var bool|false $hidePubblicationDate
     */
    public $hidePubblicationDate = false;

    /**
     * @var bool|false $hideSearchPubblicationDates
     */
    public $hideSearchPubblicationDates = false;

    /**
     * @var bool|false $hideSearchPubblicationFromTo
     */
    public $hideSearchPubblicationFromTo = true;

    /**
     * @var array $defaultListViews This set the default order for the views in lists
     */
    public $defaultListViews = ['list', 'grid'];

    /**
     * @var array $viewPathEmailSummary
     */
    public $viewPathEmailSummary = [
        'open20\amos\documenti\models\Documenti' => '@vendor/open20/amos-documenti/src/views/email/notify_summary'
    ];

    /**
     * @var array $viewPathEmailSummaryNetwork
     */
    public $viewPathEmailSummaryNetwork = [
        'open20\amos\documenti\models\Documenti' => '@vendor/open20/amos-documenti/src/views/email/notify_summary_network'
    ];

    /**
     * @var string $defaultView Set the default view for module
     */
    public $defaultView = 'list';

    /**
     * @var array
     */
    public $layoutPublishedByWidget = [
        'layout' => '{publisher}{targetAdv}{category}',
        //'layoutAdmin' => '{publisher}{targetAdv}{category}{status}{pubblicationdates}'
        'layoutAdmin' => '{publisherSection}{targetAdvSection}{categorySection}{statusSection}{pubblicationdatesSection}'
    ];

    /**
     * @var bool
     */
    public $showCountDocumentRecursive = false;

    /**
     * @var bool|false $enableGroupNotification
     */
    public $enableGroupNotification = false;

    /**
     * @var bool|false $hideWizard
     */
    public $hideWizard = true;

    /**
     * @var string
     */
    public $defaultWidgetIndexUrl = '/documenti/documenti/own-interest-documents';

    /**
     * @var bool
     */
    public $enableCategoriesForCommunity = false;

    /**
     * @var bool force input file only for the document
     */
    public $mainFileOnly = false;

    /**
     * @var bool
     */
    public $filterCategoriesByRole = false;
    public $showAllCategoriesForCommunity = true;

    /**
     * @var bool disableStandardWorkflow Disable standard worflow, direct publish
     */
    public $disableStandardWorkflow = false;

    /**
     * @var bool $alwaysLinkToViewWidgetGraphicLastDocs
     */
    public $alwaysLinkToViewWidgetGraphicLastDocs = false;

    /**
     * @var string new explorer view
     */
    public $viewExpl;

    /**
     * @var int used by uploader
     */
    public $timeout;

    /**
     * @var bool $documentsOnlyText If true the main document file and the external document link are not required at all.
     */
    public $documentsOnlyText = false;

    /**
     * This param enables the search by tags
     * @var bool $searchByTags
     */
    public $searchByTags = false;

    /**
     * @var bool
     */
    public $enableGoogleDrive = false;

    /**
     *
     */
    public $googleDriveConf = [
        'developerKey' => '',
        'clientId' => "",
        'clientSecret' => "",
        'appId' => "",
        'relativeRedirectUrl' => '',
        'relativePathCredential' => '',
        'emailServiceAccount' => '',
        'relativePathCredentialServiceAccount' => ''
    ];

    /**
     * @var bool $enableContentDuplication If true enable the content duplication on each row in table view
     */
    public $enableContentDuplication = false;

    /**
     * @var bool $enableCatImgInDocView If true replace the document icon with the category image in the document view and lists.
     */
    public $enableCatImgInDocView = false;

    /**
     * @var bool $cmsSync
     */
    public $cmsSync = false;

    /**
     * @var bool $enableMoveDoc
     */
    public $enableMoveDoc = true;

    /**
     *
     * @var string
     */
    public $cmsBaseFolder = 'Documenti';

    /**
     *
     * @var boolean
     */
    public $enableAgid = false;

    /**
     *
     * @var boolean
     */
    public $requireModalMoveFile = true;

    /**
     *
     * @var boolean
     */
    public $openInFrontEnd = false;

    /**
     * Enable/Disable notification on Documenti model
     * @var bool $documentiModelsendNotification
     */
    public $documnetiModelsendNotification = true;

    /**
     * hide block on _form relative to seo module even if it is present
     * @var type
     */
    public $hideSeoModule = false;

    /**
     * hide block on _form relative to tag module even if it is present
     * @var type
     */
    public $hideInterestArea = false;

    /**
     * If enabled the routes "/documenti/documenti/own-interest-documents" and "/documenti/documenti/all-documents"
     * will be disabled and redirected to "/documenti/documenti/explore-documents"
     * @var bool $enableExploreDocumentsInIndex
     */
    public $enableExploreDocumentsInIndex = false;

    /**
     * This parameter hide new button in widget graphics WidgetGraphicsCmsUltimiDocumenti
     * @var bool
     */
    public $hideNewButtonInWGCmsUltimiDocumenti = false;

    /**
     * This parameter exclude folders in widget graphics WidgetGraphicsCmsUltimiDocumenti
     * @var bool
     */
    public $excludeFoldersInWGCmsUltimiDocumenti = false;

    /**
     * hide create button on explore documents action
     * @var bool
     */
    public $hideCreateOnExploreDocuments = false;

    /**
     * hide second action button on explore documents action
     * @var bool
     */
    public $hideSecondActionOnExploreDocuments = false;

    /**
     * The ID of the default category pre-selected for the new News
     * @var integer
     */
    public $defaultCategory;

    /**
     * @var int $explorerLastDocsToShow The number of documents to show in the documents explorer.
     */
    public $explorerLastDocsToShow = 3;

    /**
     * @var int $maxFileSize Maximum file size for upload. No limits if null.
     */
    public $maxFileSize = null;

    /**
     * @var bool
     */
    public $enablePublishUnpublishFolder = true;

    /**
     * @var bool
     */
    public $showAllStatusesAllDocument = true;

    /**
     * @var int $truncateGetPath param for string truncation on and off in the getPath function in model DocumentiCartellePath
     */
    public $truncateGetPath = false;
    
    /**
     * @var bool $enableAnnualArchiving param for annual archiving options
     */
    public $enableAnnualArchiving = false;
    
    /**
     * @var bool $enableCountdownUnpublishing param for auto unpublish a document
     */
    public $enableCountdownUnpublishing = false;
    
    /**
     * @var bool $yearsCountdownUnpublishing param for auto unpublish a document after 5 (default) years
     */
    public $yearsCountdownUnpublishing = 5;

    /**
     * Format of the date used for publication range (start + end)
     * @var string $dateFormatDocumenti
     */
    public $dateFormatDocumenti = DateControl::FORMAT_DATE;

    /** If enabled, the main file will have a category equal of the document that contains it
     * @var bool $enableAutomaticDocumentMainFileCategory
     */
    public $enableAutomaticDocumentMainFileCategory = false;

    /**
     * @inheritdoc
     */
    public static function getModuleName() {
        return "documenti";
    }

    public static function getModelSearchClassName() {
        return AmosDocumenti::instance()->model('DocumentiSearch');
    }

    public static function getModuleIconName() {
        return 'file-text-o';
    }

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers/');

        //Configuration: merge default module configurations loaded from config.php with module configurations set by the application
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
        Yii::configure($this, ArrayHelper::merge($config, $this));

        if (!is_array($this->defaultListViews)) {
            throw new AmosException(self::t('amosdocumenti', '#exception_msg_defaultlistviews_not_array'));
        }
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons() {
        return [
            WidgetIconAdminAllDocumenti::className(),
            WidgetIconAllDocumenti::className(),
            WidgetIconDocumenti::className(),
            WidgetIconDocumentiCategorie::className(),
            WidgetIconDocumentiCreatedBy::className(),
            WidgetIconDocumentiDashboard::className(),
            WidgetIconDocumentiDaValidare::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics() {
        return [
            WidgetGraphicsHierarchicalDocuments::className(),
            WidgetGraphicsUltimiDocumenti::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels() {
        return [
            'Documenti' => __NAMESPACE__ . '\models\Documenti',
            'DocumentiSearch' => __NAMESPACE__ . '\models\search\DocumentiSearch',
            'DocumentiCategorie' => __NAMESPACE__ . '\models\DocumentiCategorie',
            'DocumentiCategorieSearch' => __NAMESPACE__ . '\models\search\DocumentiCategorieSearch',
            'DocumentiCategoryCommunityMm' => __NAMESPACE__ . '\models\DocumentiCategoryCommunityMm',
            'DocumentiCategoryRolesMm' => __NAMESPACE__ . '\models\DocumentiCategoryRolesMm',
            'ReportNode' => __NAMESPACE__ . '\models\ReportNode',
            'UploaderImportList' => __NAMESPACE__ . '\models\UploaderImportList',
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

    public static function getModelClassName() {
        return AmosDocumenti::instance()->model('Documenti');
    }

    /**
     *
     * @return string
     */
    public function getFrontEndMenu($dept = 1) {
        $menu = parent::getFrontEndMenu();
        $app = \Yii::$app;
        if (!$app->user->isGuest) {
            $menu .= $this->addFrontEndMenu(AmosDocumenti::t('amosdocumenti', '#menu_front_documenti'), AmosDocumenti::toUrlModule('/documenti/all-documents'), $dept);
        }
        return $menu;
    }

    /**
     * @return array
     */
    public function getIndexActions() {
        return [
            'documenti/index',
            'documenti-categorie/index',
            'documenti/all-documents',
            'documenti/own-documents',
            'documenti/own-interest-documents',
            'documenti/to-validate-documents',
            'documenti/admin-all-documents',
        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRoute() {
        $previousUrl = Yii::$app->session->get('previousUrl');

        $routes = [
            'admin-all-documents' => '/documenti/documenti/admin-all-documents',
            'to-validate-documents' => '/documenti/documenti/to-validate-documents',
            'own-documents' => '/documenti/documenti/own-documents',
            'all-documents' => '/documenti/documenti/all-documents',
        ];

        foreach ($routes as $actionId => $url) {
            if (strpos($previousUrl, '/documenti/documenti/'.$actionId) !== false) {
                return [
                    'documenti' => $url
                ];
            }
        }

        return [
            'documenti' => '/documenti/documenti/own-interest-documents'
        ];
    }


    /**
     * @return array
     */
    public function defaultControllerIndexRouteSlogged() {
        return [
            'documenti' => '/documenti/documenti/all-documents',
        ];
    }

    /**
     * @return array
     */
    public function getControllerNames() {
        $names = [
            'documenti' => self::t('amosdocumenti', "Documenti"),
            'documenti-categorie' => self::t('amosdocumenti', "Categorie documenti"),
        ];

        return $names;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getModuleOnlyOffice()
    {
        $moduleOnlyOffice = \Yii::$app->getModule('onlyoffice');

        return isset($moduleOnlyOffice) ? $moduleOnlyOffice : false;
    }

}
