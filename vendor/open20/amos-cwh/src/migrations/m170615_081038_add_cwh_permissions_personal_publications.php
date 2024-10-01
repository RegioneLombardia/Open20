<?php

use yii\db\Migration;
use open20\amos\core\user\User;
use open20\amos\cwh\models\CwhAuthAssignment;

/**
 * For each platform user add cwh permissions to create contents in personal scope; redactor user will be the validator of all these contents
 *
 * Class m170615_081038_add_cwh_permissions_personal_publications
 */
class m170615_081038_add_cwh_permissions_personal_publications extends Migration
{
    public function safeUp()
    {
        /** @var open20\amos\cwh\AmosCwh $moduleCwh */
        $moduleCwh = Yii::$app->getModule('cwh');
        if(!empty($moduleCwh)){
            $userIds = User::find()->select('id')->all();
            foreach ($userIds as $userId){
                $cwhNodiId = 'user-'.$userId->id;
                foreach ($moduleCwh->modelsEnabled as $contentModel){
                    $permissionCreateArray = [
                        'item_name' => $moduleCwh->permissionPrefix . "_CREATE_".$contentModel,
                        'user_id' => $userId->id,
                        'cwh_nodi_id' => $cwhNodiId
                    ];
                    $cwhAssignCreate = CwhAuthAssignment::findOne($permissionCreateArray);
                    if(empty($cwhAssignCreate)){
                        $cwhAssignCreate = new CwhAuthAssignment($permissionCreateArray);
                        $cwhAssignCreate->detachBehaviors();
                        $cwhAssignCreate->save(false);
                    }
                }
            }
            return true;
        } else {
            echo "cwh module not found";
            return false;
        }

    }

    public function safeDown()
    {
        echo "m170615_081038_add_cwh_permissions_personal_pubblications cannot be reverted.\n";

        return false;
    }
}
