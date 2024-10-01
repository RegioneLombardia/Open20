<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh\migrations
 * @category   CategoryName
 */

use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\utility\CwhUtil;
use yii\db\Migration;

/**
 * Class m180802_162304_add_cwh_permissions_network_publications
 */
class m180802_162304_add_cwh_permissions_network_publications extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
//        $this->execute('CREATE TABLE ' . \open20\amos\cwh\models\CwhAuthAssignment::tableName().'_bk' . ' LIKE ' . \open20\amos\cwh\models\CwhAuthAssignment::tableName());
//        $this->execute('INSERT INTO ' . \open20\amos\cwh\models\CwhAuthAssignment::tableName().'_bk'. ' (SELECT * FROM ' . \open20\amos\cwh\models\CwhAuthAssignment::tableName(). ')');
        /** @var open20\amos\cwh\AmosCwh $moduleCwh */
        $moduleCwh = Yii::$app->getModule('cwh');
        if (!empty($moduleCwh)) {
            $cwhConfigs = CwhConfig::find()->andWhere(['not', ['tablename' => 'user']])->all();
            foreach ($cwhConfigs as $cwhConfig) {
                $networkObject = Yii::createObject($cwhConfig->classname);
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
            return true;
        } else {
            echo "cwh module not found";
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180802_162304_add_cwh_permissions_network_publications data will not be reverted\n";
        return true;
    }
}
