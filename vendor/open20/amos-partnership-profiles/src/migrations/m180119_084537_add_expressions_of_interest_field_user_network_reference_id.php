<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use yii\db\Migration;

/**
 * Class m180119_084537_add_expressions_of_interest_field_user_network_reference_id
 */
class m180119_084537_add_expressions_of_interest_field_user_network_reference_id extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(ExpressionsOfInterest::tableName(), 'user_network_reference_classname', $this->string()->null()->defaultValue(null)->after('partnership_profile_id')->comment('User Network Reference Classname'));
        $this->addColumn(ExpressionsOfInterest::tableName(), 'user_network_reference_id', $this->integer()->null()->defaultValue(null)->after('user_network_reference_classname')->comment('User Network Reference Id'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(ExpressionsOfInterest::tableName(), 'user_network_reference_classname');
        $this->dropColumn(ExpressionsOfInterest::tableName(), 'user_network_reference_id');
        return true;
    }
}
