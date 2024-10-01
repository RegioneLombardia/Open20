<?php

namespace open20\amos\cwh\models;

use cornernote\workflow\manager\models\Workflow;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\cwh\utility\CwhUtil;
use yii\helpers\ArrayHelper;

/**
 * Class CwhConfigContents
 * This is the model class for table "cwh_config_contents".
 *
 * @property array statuses
 *
 * @package open20\amos\cwh\models
 */
class CwhConfigContents extends \open20\amos\cwh\models\base\CwhConfigContents
{

    const WORKFLOW_AFFIX = 'Workflow';

    /**
     * @param $classname
     * @return static
     */
    public static function getConfig($classname)
    {
        return self::findOne(['classname' => $classname]);
    }

    /**
     * @return mixed
     */
    public static function getConfigs()
    {
        return self::find()->all();
    }
    
    /**
     * 
     */
    public function loadFromDb()
    {
        if(!empty($this->classname))
        {
            $dbModel = self::findOne(['classname' => $this->classname]);
            if(!is_null($dbModel))
            {
                $this->setAttributes($dbModel->attributes);
                $this->isNewRecord =false;
            }
        }
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        $refClass = new \ReflectionClass($this->classname);

        $workflow = Workflow::findOne($refClass->getShortName() . self::WORKFLOW_AFFIX);
        $retArray = [];
        if (!is_null($workflow)) {
            $retArray = ArrayHelper::map($workflow->statuses, function ($array, $default) {
                return "{$array->workflow_id}/{$array->id}";
            }, 'label');
        }
        return $retArray;
    }

    /**
     * @return mixed
     */
    public function getModelAttributes()
    {

        $attributes = [];
        if(!empty($this->classname)) {
            if(class_exists($this->classname)){
                /** @var Record $modelObject */
                $modelObject = \Yii::createObject($this->classname);
                $modelAttributes = $modelObject->attributes();
                foreach ($modelAttributes as $attribute){
                    $attributes = ArrayHelper::merge($attributes, [$attribute => $attribute. ' (' . $modelObject->getAttributeLabel($attribute). ')']);
                }
            }
        }
        return $attributes;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert){
            set_time_limit(0);
            $cwhConfigIdUser = CwhConfig::findOne(['tablename' => 'user'])->id;
            $userIds = User::find()->select('id')->column();
            foreach ($userIds as $userId) {
                $cwhNodiId = 'user-' . $userId;
                $permissionCreateArray = [
                    'item_name' => \Yii::$app->getModule('cwh')->permissionPrefix . "_CREATE_" . $this->classname,
                    'user_id' => $userId,
                    'cwh_nodi_id' => $cwhNodiId,
                    'cwh_network_id' => $userId,
                    'cwh_config_id' => $cwhConfigIdUser
                ];
                $cwhAssignCreate = CwhAuthAssignment::findOne($permissionCreateArray);
                if (empty($cwhAssignCreate)) {
                    $cwhAssignCreate = new CwhAuthAssignment($permissionCreateArray);
                    $cwhAssignCreate->detachBehaviors();
                    $cwhAssignCreate->save(false);
                }
                $cwhConfigs = CwhConfig::find()->andWhere(['not', ['tablename' => 'user']])->all();
                foreach ($cwhConfigs as $cwhConfig) {
                    $networkObject = \Yii::createObject($cwhConfig->classname);
                    $mmTable = \Yii::createObject($networkObject->getMmClassName());
                    $userNetworkMms = $mmTable->find()->andWhere([$networkObject->getMmUserIdFieldName() => $userId])->all();
                    $networkIdField = $networkObject->getMmNetworkIdFieldName();
                    foreach ($userNetworkMms as $userNetworkMm) {
                        CwhUtil::setCwhAuthAssignments(null, $userNetworkMm, false, $this->classname, $cwhConfig);
                    }
                }
            }
        }
    }

    public function beforeDelete()
    {
        set_time_limit(0);
        $moduleCwh = \Yii::$app->getModule('cwh');
        $cwhAssignArray = CwhAuthAssignment::find()->andWhere([
            'item_name' => [
                $moduleCwh->permissionPrefix . "_CREATE_" . $this->classname,
                $moduleCwh->permissionPrefix . "_VALIDATE_" . $this->classname
            ]
        ])->all();

        foreach ($cwhAssignArray as $cwhAssign){
            $cwhAssign->detachBehaviors();
            $cwhAssign->delete();
        }
        return parent::beforeDelete();

    }

}
