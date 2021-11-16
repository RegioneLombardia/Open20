<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle
 * @category   CategoryName
 */

namespace open20\amos\moodle;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\moodle\widgets\SubscribeUserToFADCourseWidget;
use open20\amos\moodle\widgets\icons\WidgetIconMoodle;
use open20\amos\moodle\widgets\icons\WidgetMoodle;

use Yii;

/**
 * Class AmosMoodle
 * @package open20\amos\moodle
 */
class AmosMoodle extends AmosModule implements ModuleInterface
{
    /**
     * 
     */
    const MOODLE_ADMIN = 'MOODLE_ADMIN';                //
    const MOODLE_MANAGER = 'MOODLE_ADMIN';              // DUPLICATO USATO MoodleCategory, MoodleCourse, CourseController
    const MOODLE_STUDENT = 'MOODLE_STUDENT';            //
    const MOODLE_RESPONSABILE = 'MOODLE_RESPONSABILE';  //

    /**
     * @var type 
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
     * If true enable a link on single user to invite he/she to a FAD Course
     * @var bool $enableInviteUserToFADCourse 
     */
    public $enableSubscribeUserToFADCourse = false;

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
        
        $this->bootstrapWhiteListRoute=["privileges/privileges/enable","privileges/privileges/disable", "amministra-utenti/assignment/revoke",
"amministra-utenti/assignment/assign"];
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
             WidgetIconMoodle::className(),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return [            
        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [            
        ];
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

}
