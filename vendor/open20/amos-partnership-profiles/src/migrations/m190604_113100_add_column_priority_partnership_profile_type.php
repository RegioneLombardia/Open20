<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    aster\platform\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\partnershipprofiles\models\base\PartnershipProfilesType;
use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m190604_113100_add_column_priority_partnership_profile_type
 */
class m190604_113100_add_column_priority_partnership_profile_type extends Migration
{
    const ADMIN_ID = 1;

    private $tableName = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = PartnershipProfilesType::tableName();
    }

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'priority', $this->integer(1)->after('description')->defaultValue(0)->comment('Order priority inside select html'));

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'priority');

        return true;
    }
}