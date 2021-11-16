<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200407_102613_bypassworkflow extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->update('sw_workflow', ['initial_status_id' => 'VALIDATED'], ['id' => 'UserProfileWorkflow']);
        $this->alterColumn(\open20\amos\admin\models\UserProfile::tableName(), 'validato_almeno_una_volta', $this->boolean()->defaultValue(1)->comment('L utente è stato validato almeno una volta'));
        $this->update(\open20\amos\admin\models\UserProfile::tableName(), ['validato_almeno_una_volta' => 1]);
    }


    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->update('sw_workflow', ['initial_status_id' => 'DRAFT'], ['id' => 'UserProfileWorkflow']);
        $this->alterColumn(\open20\amos\admin\models\UserProfile::tableName(), 'validato_almeno_una_volta', $this->boolean()->defaultValue(0)->comment('L utente è stato validato almeno una volta'));
        $this->update(\open20\amos\admin\models\UserProfile::tableName(), ['validato_almeno_una_volta' => 1]);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
