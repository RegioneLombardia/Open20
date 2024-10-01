<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle
 * @category   CategoryName
 */

namespace open20\amos\moodle;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\moodle\widgets\SubscribeUserToFADCourseWidget;
use open20\amos\moodle\widgets\icons\WidgetIconMoodle;
use open20\amos\moodle\widgets\icons\WidgetMoodle;

use Yii;

/**
 * Class AmosMoodle
 * @package open20\amos\moodle
 */
class AmosMoodle
    extends AmosModule
    implements ModuleInterface, CmsModuleInterface
{
    /**
     * 
     */
    const MOODLE_ADMIN = 'MOODLE_ADMIN';                //
    const MOODLE_MANAGER = 'MOODLE_ADMIN';              // DUPLICATO USATO MoodleCategory, MoodleCourse, CourseController
    const MOODLE_STUDENT = 'MOODLE_STUDENT';            //
    const MOODLE_RESPONSABILE = 'MOODLE_RESPONSABILE';  //

    /**
     * Resource's type to retrieve from Moodle Platform
     */
    const MOODLE_MODNAME_CERTIFICATE = 'certificate';
    const MOODLE_MODNAME_RESOURCE = 'resource';
    const MOODLE_MODNAME_SCORM = 'scorm';
    const MOODLE_MODNAME_QUIZ = 'quiz';
    const MOODLE_MODNAME_CUSTOMCERT = 'customcert';
    const MOODLE_MODNAME_QUESTIONNAIRE = 'questionnaire';
    const MOODLE_MODNAME_PAGE = 'page';

    /**
     * When click on a Moodle course user will be redirect automatically
     * on the relative Moodle installation, bypass completly the amos-moodle
     * functionality
     * 
     * @var type
     */
    public $skipPlatformGotoMoodleDirectly = false;

    /**
     * @var type 
     */
    public static $CONFIG_FOLDER = 'config';
    
    /**
     * @var string|boolean the layout that should be applied for views within this module.
     *      This refers to a view name relative to [[layoutPath]].
     *      If this is not set, it means the layout value of the [[module|parent module]]
     *      will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     *
     * @var type 
     */
    public $name = 'Moodle';
    
    /**
     *
     * @var type 
     */
    public $controllerNamespace = 'open20\amos\moodle\controllers';
    
    /**
     *
     * @var type 
     */
    public $config = [];
    
    /**
     *
     * @var type 
     */
    public $bootstrapWhiteListRoute;

    /**
     * 
     * @var type
     */
    public $enableAddStudentRoleAfterLogin = false;
    
    // URL della piattaforma Moodle
    public $moodleUrl;
    public $moodleApiUrl;
    public $moodleUserTokenUrl;
    public $moodleOAuthUrl;
    
    // La chiave segreta configurata sul plugin di Moodle per le callback verso Open 2.0
    public $secretKey;
    
    // Lo username dell'utente amministratore Moodle
    public $adminUsername;
    
    // Il token amministrativo lato Moodle
    public $moodleAdministratorToken;
    
    // ID ruoli Moodle
    public $moodleOpen20baseRoleId;

    /**
     * @var bool
     */
    public $disableEnrolmentEmail = false;
    
    /**
     * If true enable a link on single user to invite he/she to a FAD Course
     * @var bool $enableInviteUserToFADCourse 
     */
    public $enableSubscribeUserToFADCourse = false;

    /**
     * If Moodle doesn't have the ranking module hide the relative widget
     * @var type 
     */
    public $disableRankingWidget = false;

    /**
     * If Moodle doesn't have the ranking module hide the relative widget
     * @var type 
     */
    public $disableParticipantWidget = false;
    
    /**
     * @var int $generalCategoryMoodleId This is the general moodle category id, default to 1.
     */
    public $generalCategoryMoodleId = 1;

    /**
     * Disable enrolment, only my own page course available
     * 
     * @var bool
     */
    public $disableEnrolmentOption = false;
    
    /**
     * List of all Moodle 
     * @var type 
     */
    public $resourcesWhiteList = [
        self::MOODLE_MODNAME_CERTIFICATE,
        self::MOODLE_MODNAME_RESOURCE,
        self::MOODLE_MODNAME_SCORM,
        self::MOODLE_MODNAME_QUIZ,
        self::MOODLE_MODNAME_CUSTOMCERT,
        self::MOODLE_MODNAME_QUESTIONNAIRE,
        self::MOODLE_MODNAME_PAGE,
    ];

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return "moodle";
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers/', __DIR__ . '/controllers/');
        \Yii::setAlias('@open20/amos/' . static::getModuleName() , __DIR__);
        
        // initialize the module with the configuration loaded from config.php
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
        
        $this->bootstrapWhiteListRoute = [
            "privileges/privileges/enable",
            "privileges/privileges/disable",
            "amministra-utenti/assignment/revoke",
            "amministra-utenti/assignment/assign"
        ];
        //$this->bootstrapWhiteListRoute="privileges/privileges/disable";
        
        Yii::configure($this,$config );
        
        //Configuro gli URL
        $this->moodleApiUrl = $this->moodleUrl."/webservice/rest/server.php";
        $this->moodleUserTokenUrl = $this->moodleUrl."/login/token.php";
        $this->moodleOAuthUrl = $this->moodleUrl."/local/open20integration/oauthify.php";
    }
    
    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [ 
             WidgetIconMoodle::class,
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
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
        
    /**
     * @param \open20\amos\admin\models\UserProfile $model
     * @param \open20\amos\organizzazioni\models\Profilo $organizzazione
     * @return type
     */
    public function getSubscribeUserToFADCourseWidget($userProfile = null, $organization = null)
    {
        return SubscribeUserToFADCourseWidget::widget([
            'userProfile' => $userProfile,
            'organization' => $organization
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName()
    {
        return AmosMoodle::instance()->model('MoodleCourseSearch');
    }

    /**
     * @inheritdoc
     */
    public static function getModelClassName()
    {
        return AmosMoodle::instance()->model('MoodleCourse');
    }

    /**
     * @inheritdoc
     */
    public static function getModuleIconName()
    {
        return 'feed';
    }

    /**
     *
     * @return string
     */
    public function getFrontEndMenu($dept = 1)
    {
        $menu = parent::getFrontEndMenu();
        $app  = \Yii::$app;

        $toUrl = ($this->disableEnrolmentOption == false)
            ? AmosMoodle::toUrlModule('/course/index')
            : AmosMoodle::toUrlModule('/course/own-courses');
        
        if (!$app->user->isGuest && (
            Yii::$app->user->can(self::MOODLE_STUDENT)
            || Yii::$app->user->can(self::MOODLE_MANAGER)
            || Yii::$app->user->can(self::MOODLE_ADMIN)
            || Yii::$app->user->can(self::MOODLE_RESPONSABILE)
        ) ) {
            $menu .= $this->addFrontEndMenu(
                self::_t('#menu_front_moodle'),
                $toUrl,
                $dept
            );
        }

        return $menu;
    }

    /**
     * 
     * @param type $message
     * @param type $category
     * @param type $params
     * @param type $language
     * @return type
     */
    public static function _t($message, $params = [], $language = null)
    {
        return parent::t('amosmoodle', $message, $params, $language);
    }

    /**
     * 
     * @param type $message
     * @param type $category
     * @param type $params
     * @param type $language
     * @return type
     */
    public static function _tHtml($message, $params = array(), $language = null)
    {
        return parent::tHtml('amosmoodle', $message, $params, $language);
    }

}
