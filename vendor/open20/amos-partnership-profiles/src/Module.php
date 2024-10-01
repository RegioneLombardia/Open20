<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles;

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\BreadcrumbInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestAllAdmin;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestCreatedBy;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestDashboard;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestReceived;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntDashboard;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesAll;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesAllAdmin;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesArchived;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesClosed;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesCreatedBy;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesOwnInterest;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesToValidate;
use yii\console\Application;

/**
 * Class Module
 * @package open20\amos\partnershipprofiles
 */
class Module extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface, BreadcrumbInterface
{
    const MAX_LAST_PARTNERSHIP_ON_DASHBOARD = 3;


    const PARTNERSHIPPROFILESADMIN = 'partnershipprofilesadmin';
    const PARTNERSHIPPROFILES = 'partnershipprofiles';
    const EXPRESSIONSOFINTEREST = 'expressionsofinterest';
    const PARTNERPROFEXPROFINT = 'partnerprofexprofint';

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
    public $controllerNamespace = 'open20\amos\partnershipprofiles\controllers';

    public $newFileMode = 0666;

    public $name = 'Partnership Profiles';


    public $hidefacilitator = false;

    /**
     * @inheritdoc
     */
    public $db_fields_translation = [
        [
            'namespace' => 'open20\amos\partnershipprofiles\models\PartnershipProfilesType',
            'attributes' => ['name'],
            'category' => 'amospartnershipprofiles',
        ],
    ];

    /**
     * @var bool
     */
    public $communityOfReferenceRequired = true;

    /**
     * @var bool $disablePartProfLongStringFieldsLimits
     */
    public $disablePartProfLongStringFieldsLimits = false;

    /**
     * @var bool
     */
    public $enableOnlyOneOrganization = false;

    public $fieldsCommunityConfigurations = [];

    public $fieldsConfigurations = [
        'required' => [
            'title',
            'short_description',
            'extended_description',
            'advantages_innovative_aspects',
            'expected_contribution',
            'partnership_profile_date',
            'expiration_in_months',
            'attrPartnershipProfilesTypesMm'
        ],
        'tabs' => [
            'tab-more-information' => true,
            'tab-attachments' => true
        ],
        'fields' => [
            //tab general
            'title' => true,
            'short_description' => true,
            'extended_description' => true,
            'advantages_innovative_aspects' => true,
            'expected_contribution' => true,
            'partnership_profile_date' => true,
            'expiration_in_months' => true,
            'attrPartnershipProfilesTypesMm' => true,
            'other_prospect_desired_collab' => true,
            'contact_person' => true,
            // tab other information
            'english_title' => true,
            'english_short_description' => true,
            'english_extended_description' => true,
            'attrPartnershipProfilesCountriesMm' => true,
            'willingness_foreign_partners' => true,
            'work_language_id' => true,
            'other_work_language' => true,
            'development_stage_id' => true,
            'other_development_stage' => true,
            'intellectual_property_id' => true,
            'other_intellectual_property' => true
        ],
    ];

    /**
     * @var array $defaultListViewsPartnerProf This set the default order for the views in lists
     */
    public $defaultListViewsPartnerProf = ['list', 'grid'];

    /**
     * @var array $defaultListViewsExprOfInt This set the default order for the views in lists
     */
    public $defaultListViewsExprOfInt = ['list', 'grid'];

    /**
     * @var bool $hideAdminsInPartProfFacilitatorSelection If true, when the user select the facilitator for a partnership profiles, all ADMIN users are hidden.
     */
    public $hideAdminsInPartProfFacilitatorSelection = false;

    /**
     * @var string $pluginCustomIcon
     */
    public $pluginCustomIcon = '';

    public $enableCategories = false;
    /**
     * @var array
     */
    public $whiteListRolesCategories = ['ADMIN', 'BASIC_USER'];


    /**
     * @return string
     */
    public static function getModuleName()
    {
        return 'partnershipprofiles';
    }

    public static function getModelSearchClassName()
    {
        return __NAMESPACE__ . '\models\search\PartnershipProfilesSearch';
    }

    public static function getModuleIconName()
    {
        $customIcon = Module::instance()->pluginCustomIcon;
        if (strlen($customIcon) > 0) {
            return $customIcon;
        } else if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            return 'propostecollaborazione';
        } else {
            return 'partnership-profiles';
        }
    }

    /**
     */
    public static function getModelClassName()
    {
        return __NAMESPACE__ . '\models\PartnershipProfiles';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers/', __DIR__ . '/controllers/');
        if (\Yii::$app instanceof Application) {
            $this->controllerNamespace = 'open20\amos\partnershipprofiles\console';
        }
        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [
            WidgetIconPartnershipProfilesAllAdmin::className(),
            WidgetIconPartnershipProfilesDashboard::className(),
            WidgetIconPartnershipProfilesOwnInterest::className(),
            WidgetIconPartnershipProfilesAll::className(),
            WidgetIconPartnershipProfilesCreatedBy::className(),
            WidgetIconPartnershipProfilesToValidate::className(),
            WidgetIconPartnershipProfilesArchived::className(),
            WidgetIconPartnershipProfilesClosed::className(),
            WidgetIconPartnerProfExprOfIntDashboard::className(),
            WidgetIconExpressionsOfInterestDashboard::className(),
            WidgetIconExpressionsOfInterestAllAdmin::className(),
            WidgetIconExpressionsOfInterestReceived::className(),
            WidgetIconExpressionsOfInterestCreatedBy::className()
        ];
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
    protected function getDefaultModels()
    {
        return [
            'PartnershipProfiles' => __NAMESPACE__ . '\\' . 'models\PartnershipProfiles',
            'PartnershipProfilesCountriesMm' => __NAMESPACE__ . '\\' . 'models\PartnershipProfilesCountriesMm',
            'PartnershipProfilesTypesMm' => __NAMESPACE__ . '\\' . 'models\PartnershipProfilesTypesMm',
            'PartnershipProfilesType' => __NAMESPACE__ . '\\' . 'models\PartnershipProfilesType',
            'DevelopmentStage' => __NAMESPACE__ . '\\' . 'models\DevelopmentStage',
            'ExpressionsOfInterest' => __NAMESPACE__ . '\\' . 'models\ExpressionsOfInterest',
            'IntellectualProperty' => __NAMESPACE__ . '\\' . 'models\IntellectualProperty',
            'WorkLanguage' => __NAMESPACE__ . '\\' . 'models\WorkLanguage',
            'PartnershipProfilesSearch' => __NAMESPACE__ . '\\' . 'models\search\PartnershipProfilesSearch',
            'PartnershipProfilesTypeSearch' => __NAMESPACE__ . '\\' . 'models\search\PartnershipProfilesTypeSearch',
            'DevelopmentStagesSearch' => __NAMESPACE__ . '\\' . 'models\search\DevelopmentStagesSearch',
            'ExpressionsOfInterestSearch' => __NAMESPACE__ . '\\' . 'models\search\ExpressionsOfInterestSearch',
            'IntellectualPropertySearch' => __NAMESPACE__ . '\\' . 'models\search\IntellectualPropertySearch',
            'WorkLanguageSearch' => __NAMESPACE__ . '\\' . 'models\search\WorkLanguageSearch'
        ];
    }

    /**
     * This method return the session key that must be used to add in session
     * the url from the user have started the content creation for partnership profiles.
     * @return string
     */
    public static function beginCreateNewSessionKeyPartnershipProfiles()
    {
        return 'beginCreateNewUrl_partnership_profiles';
    }

    /**
     * This method return the session key that must be used to add in session
     * the url date and time creation from the user have started the content creation.
     * @return string
     */
    public static function beginCreateNewSessionKeyPartnershipProfilesDateTime()
    {
        return 'beginCreateNewUrlDateTime_partnership_profiles';
    }

    /**
     * This method return the session key that must be used to add in session
     * the url from the user have started the content creation for expressions of interest.
     * @return string
     */
    public static function beginCreateNewSessionKeyExprOfInt()
    {
        return 'beginCreateNewUrl_expressions_of_interest';
    }

    /**
     * This method return the session key that must be used to add in session
     * the url date and time creation from the user have started the content creation.
     * @return string
     */
    public static function beginCreateNewSessionKeyExprOfIntDateTime()
    {
        return 'beginCreateNewUrlDateTime_expressions_of_interest';
    }

    /**
     * @return array
     */
    public function getIndexActions(){
        return [
            'partnership-profiles/index',
            'partnership-profiles/all-admin',
            'partnership-profiles/own-interest',
            'partnership-profiles/created-by',
            'partnership-profiles/closed',
            'partnership-profiles/to-validate',
            'partnership-profiles/facilitator-partnership-profiles',

            'expressions-of-interest/index',
            'expressions-of-interest/received',
            'expressions-of-interest/created-by',
            'expressions-of-interest/facilitator-expressions-of-interest',
            'expressions-of-interest/all',
            'expressions-of-interest/all-admin',
        ];
    }


    /**
     * @return array
     */
    public function defaultControllerIndexRoute()
    {
        return [
            'partnership-proposal' => '/partnershipprofiles/partnership-profiles/own-interest',
            'partnership-profiles-category' => '/partnershipprofiles/partnership-profiles-category/index',
            'expressions-of-interest' => '/partnershipprofiles/expressions-of-interest/created-by'

        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRouteSlogged()
    {
        return [
            'partnership-proposal' => '/partnershipprofiles/partnership-profiles/index',
            'expressions-of-interest' => '/partnershipprofiles/expressions-of-interest/created-by',
            'partnership-profiles-category' => '/partnershipprofiles/partnership-profiles/index'

        ];
    }


    /**
     * @return array
     */
    public function getControllerNames(){
        $names =  [
            'partnership-profiles' => self::t('amospartnershipprofiles', 'Partnership Profiles'),
            'expressions-of-interest'  => self::t('amospartnershipprofiles', 'Expressions Of Interest'),
            'partnership-profiles-category'  => self::t('amospartnershipprofiles', 'Categorie proposte di collaborazione'),
        ];

        return $names;
    }
}
