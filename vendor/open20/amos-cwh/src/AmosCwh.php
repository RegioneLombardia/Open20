<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh;

use open20\amos\core\module\AmosModule;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\cwh\base\ModelNetworkInterface;
use open20\amos\cwh\components\bootstrap\CheckConfigComponent;
use open20\amos\cwh\models\base\CwhConfigContents;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\CwhNodi;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm;
use open20\amos\cwh\models\CwhTagInterestMm;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use open20\amos\cwh\query\CwhActiveQuery;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use yii\web\Application;

/**
 * Class AmosCwh
 *
 * Collaboration Web House - This module provides management of rules, scope, relations and further more linking modules to the others
 *
 * @package open20\amos\cwh
 * @see
 */
class AmosCwh extends AmosModule implements BootstrapInterface
{
    /**
     * @var string
     */
    public $controllerNamespace = 'open20\amos\cwh\controllers';
    /**
     * @var string
     */
    public $postKey = 'Cwh';
    /**
     * @var array
     */
    public $modelsEnabled = [

    ];


    /**
     * @var array $validateOnStatus Configuration array: for each content type class type the attribute correspondent to status and the status list for validation
     *
     * how to fill :
     *  [
     *      'class' => '<the content type className>',
     *      'attribute' => 'status',
     *      'statuses' => [
     *          'BOZZA',
     *          '...'
     *      ]
     *  ]
     */
    public $validateOnStatus = [

    ];

    public $permissionPrefix = 'CWH_PERMISSION';
    public $userProfileClass = 'open20\admin\models\UserProfile';

    public $behaviors = [
        'cwhBehavior' => 'open20\amos\cwh\behaviors\CwhNetworkBehaviors'
    ];

    public $validatoriEnabled = true;
    public $destinatariEnabled = true;
    public $regolaPubblicazioneEnabled = true;

    /**
     * @var bool $regolaPubblicazioneFilter
     * if true publication rule 'PUBLIC' (to all users) only if the user has the specified role $regolaPubblicazioneFilterRole
     */
    public $regolaPubblicazioneFilter = false;
    /**
     * @var string $regolaPubblicazioneFilterRole - default VALIDATOR_PLUS role
     * if $regolaPubblicazioneFilter flag is setted only the specified role can view publication rule  1. PUBLIC - All users
     */
    public $regolaPubblicazioneFilterRole = 'VALIDATOR_PLUS';

    /** @var array  $scope The entities scope for which contents needs to be filtered */
    public $scope = [];

    public $userEntityRelationTable = [];

    public $cwhConfWizardEnabled = false;

    public $enableDestinatariFatherChildren = false;

    /**
     * @var bool
     */
    public $cached = false;

    public $cacheDuration = 86400;

    /**
     * @var bool $tagsMatchEachTree
     * set to true if a content is to be considered of user interest when there is tag-match of each tag tree
     * Default is false - at least one content tag matching user interest (any tree)
     */
    public $tagsMatchEachTree = false;

    private static $networkModels = null;
    private static $fullNetworkModels = null;

    /**
     * Chiave che verrà spedita in post
     *
     * @return string
     */
    public function getPostKey()
    {
        return $this->postKey;
    }

    /**
     * @param string $postKey
     */
    public function setPostKey($postKey)
    {
        $this->postKey = $postKey;
    }

    public function init()
    {
        $configContents = null;
        parent::init();

        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers');
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
        try {
            $configContents = CwhConfigContents::find()->all();
        }catch (\Exception $ex){
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        if(!is_null($configContents) && !empty($configContents)){
            /** @var CwhConfigContents $content */
            foreach($configContents as $content){
                $this->modelsEnabled[] = $content->classname;
                $this->validateOnStatus[$content->classname] = [
                    'attribute' => $content->status_attribute,
                    'statuses' => [
                        $content->status_value,
                    ]
                ];
            }
        }
        Record::$modulesChainBehavior[] = 'cwh';

    }

    /**
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'cwh';
    }

    public function getWidgetGraphics()
    {
        return [];
    }

    public function getWidgetIcons()
    {
        return [];
    }

    /**
     *
     * @return array
     */
    public function getDefaultModels()
    {
        return [
            'CwhAuthAssignment' => __NAMESPACE__ . '\\' . 'models\CwhAuthAssignment',
            'CwhConfig' => __NAMESPACE__ . '\\' . 'models\CwhConfig',
            'CwhNodi' => __NAMESPACE__ . '\\' . 'models\CwhNodi',
            'CwhPubblicazioni' => __NAMESPACE__ . '\\' . 'models\CwhPubblicazioni',
            'CwhPubblicazioniCwhNodiEditoriMm' => __NAMESPACE__ . '\\' . 'models\CwhPubblicazioniCwhNodiEditoriMm',
            'CwhPubblicazioniCwhNodiValidatoriMm' => __NAMESPACE__ . '\\' . 'models\CwhPubblicazioniCwhNodiValidatoriMm',
            'CwhRegolePubblicazione' => __NAMESPACE__ . '\\' . 'models\CwhRegolePubblicazione',
        ];
    }

    /**
     * set cwh scope to the value of cwh-scope param in session
     */
    public function setCwhScopeFromSession()
    {
        // It's a web application?
        if (isset(Yii::$app->session)) {
            $session = Yii::$app->session;
            if (isset($session["cwh-scope"])) {
                $this->scope = $session["cwh-scope"];
            }
            if (isset($session["cwh-relation-table"])) {
                $this->userEntityRelationTable = $session["cwh-relation-table"];
            }
        }
    }

    /**
     * set param cwh-scope in session
     * @param array $cwhScope The list of cwh scopes
     * @param array $cwhRelation relation table between users and entity, specifing the entity data
     *
     * call example
            $moduleCwh->setCwhScopeInSession([
                'community' => $id, // simple cwh scope for contents filtering, required
            ],
            [
            // cwhRelation array specifying name of relation table, name of entity field on relation table and entity id field ,
            // optional for compatibility with previous versions
                'mm_name' => 'community_user_mm',
                'entity_id_field' => 'community_id',
                'entity_id' => $id
            ]);
     */
    public function setCwhScopeInSession($cwhScope, $cwhRelation = null){

        $session = Yii::$app->session;
        $session["cwh-scope"] = $cwhScope;
        $session["cwh-relation-table"] = $cwhRelation;

    }

    /**
     * reset param cwh-scope in session to an empty array
     */
    public function resetCwhScopeInSession()
    {
        $session = Yii::$app->session;
        if (isset($session["cwh-scope"])) {
            $session["cwh-scope"] = [];
        }
        if (isset($session["cwh-relation-table"])) {
            $session["cwh-relation-table"] = [];
        }
    }

    public function getCwhScope(){
        $session = Yii::$app->session;
        if (isset($session["cwh-scope"])) {
            return $session["cwh-scope"];
        }
        return null;
    }

    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            if($this->cwhConfWizardEnabled) {
                $app->on(Application::EVENT_BEFORE_ACTION, [
                    (new CheckConfigComponent()),
                    'checkConf'
                ]);
            }

            Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_DELETE, [$this, 'afterSaveModelDelCache']);
            Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterSaveModelDelCache']);
            Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterSaveModelDelCache']);

        }
    }


    /**
     * returns Query for CwhNodi (networks) of which user is member.
     * @param integer $userId - if null logged user id is considered
     * @return mixed
     *
     */
    public function getUserNetworks($userId = null){

        $networks = [];
        try {
            $networks = CwhActiveQuery::getUserNetworksQuery($userId)->all();
        }catch(Exception $ex){
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }
        return $networks;
    }

    /**
     * @return array
     */
    public function getNetworkModels(){

        try {
            if(!self::$networkModels) {
                self::$networkModels = self::$networkModels = CwhConfig::find()->andWhere(['<>', 'tablename', 'user'])->all();
            }
        }catch(Exception $ex){
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }

        return self::$networkModels;
    }

    /**
     * @return array
     */
    public function getFullNetworkModels(){

        try {
            if(!self::$fullNetworkModels) {
                self::$fullNetworkModels = self::$networkModels = CwhConfig::find()->all();
            }
        }catch(Exception $ex){
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }

        return self::$fullNetworkModels;
    }


    /**
     * @param $event
     */
    public function afterSaveModelDelCache($event){

        try {

            $models = ArrayHelper::merge($this->modelsEnabled,
                [
                    CwhPubblicazioniCwhNodiValidatoriMm::className(),
                    CwhPubblicazioniCwhNodiEditoriMm::className(),
                    User::className()
                ]);

            $moduleTag = Yii::$app->getModule('tag');
            if(isset($moduleTag)){
                $models = ArrayHelper::merge($models, [
                    \open20\amos\tag\models\EntitysTagsMm::className(),
                    CwhTagInterestMm::className(),
                    CwhTagOwnerInterestMm::className(),
                ]);
            }

            /** @var ModelNetworkInterface $model */
            foreach ($this->getNetworkModels() as $model) {
                $obj = Yii::createObject($model->classname);
                if ($obj) {
                    $models[] = $obj->getMmClassName();
                }
            }

            if (in_array(get_class($event->sender), $models)) {
                $this->resetCwhMaterializatedView();
            }
        }catch(Exception $ex){
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }

    }
    
    /**
     * 
     */
    public function resetCwhMaterializatedView(){
        CwhNodi::mustReset();
        \Yii::$app->cache->flush();
    }

}
