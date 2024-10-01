<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\controllers;

use open20\amos\core\user\User;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhAuthAssignment;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\utility\CwhUtil;
use yii\console\Controller;

/**
 * Class CommandsController
 * @package open20\amos\cwh\controllers
 */
class CommandsController extends Controller
{
    /**
     * @var AmosCwh $cwhModule
     */
    public $cwhModule;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->cwhModule = AmosCwh::instance();
        parent::init();
    }
    
    public function actionAddCwhPermissionsPublications()
    {
        if (!empty($this->cwhModule)) {
            $this->addCwhPermissionsPersonalPublications();
            $this->addCwhPermissionsNetworkPublications();
        } else {
            echo "cwh module not found";
        }
    }
    
    protected function addCwhPermissionsPersonalPublications()
    {
        echo "Adding cwh permission for personal publications - begin\n";
        $userIds = User::find()->select('id')->all();
        foreach ($userIds as $userId) {
            $cwhNodiId = 'user-' . $userId->id;
            foreach ($this->cwhModule->modelsEnabled as $contentModel) {
                $permissionCreateArray = [
                    'item_name' => $this->cwhModule->permissionPrefix . "_CREATE_" . $contentModel,
                    'user_id' => $userId->id,
                    'cwh_nodi_id' => $cwhNodiId
                ];
                $cwhAssignCreate = CwhAuthAssignment::findOne($permissionCreateArray);
                if (empty($cwhAssignCreate)) {
                    $cwhAssignCreate = new CwhAuthAssignment($permissionCreateArray);
                    $cwhAssignCreate->detachBehaviors();
                    $cwhAssignCreate->save(false);
                }
            }
        }
        echo "Adding cwh permission for personal publications - end\n";
    }
    
    protected function addCwhPermissionsNetworkPublications()
    {
        echo "Adding cwh permission for network publications - begin\n";
        $cwhConfigs = CwhConfig::find()->andWhere(['not', ['tablename' => 'user']])->all();
        foreach ($cwhConfigs as $cwhConfig) {
            $networkObject = \Yii::createObject($cwhConfig->classname);
            $mmObjectClassname = $networkObject->getMmClassName();
            echo "Adding cwh permission for network type: " . $networkObject->formName() . "\n";
            $networks = $networkObject->find()->all();
            foreach ($networks as $network) {
                echo $cwhConfig->tablename . " " . $network->id . ": ";
                $userNetworkMms = $mmObjectClassname::find()->andWhere([$networkObject->getMmNetworkIdFieldName() => $network->id])->all();
                $membersCount = count($userNetworkMms);
                echo $membersCount . " members\n";
                foreach ($userNetworkMms as $userNetworkMm) {
                    CwhUtil::setCwhAuthAssignments($network, $userNetworkMm);
                }
            }
        }
        echo "Adding cwh permission for network publications - end\n";
    }
}
