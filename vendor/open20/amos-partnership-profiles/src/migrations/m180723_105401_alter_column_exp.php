<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

class m180723_105401_alter_column_exp extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('expressions_of_interest', 'partnership_offered', "TEXT NOT NULL COMMENT 'Partnership offered'");
        $this->alterColumn('expressions_of_interest', 'additional_information', "TEXT NOT NULL COMMENT 'Additional information'");
        $this->alterColumn('expressions_of_interest', 'clarifications', "TEXT NOT NULL COMMENT 'Clarifications'");

    }

    public function safeDown()
    {
     
    }
}