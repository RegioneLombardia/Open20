<?php

use yii\db\Migration;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m210517_120000_add_field_user_profile extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $ts = $this->db->schema->getTableSchema('user_profile', true);
        $columns = $ts->columnNames;
        if ( !in_array('user_import_task_id', $columns) ) {
            $this->addColumn('user_profile', 'user_import_task_id', $this->integer()->after('user_id'));
        }

        if ( isset($ts->foreignKeys['fk_userprofile_user_import_task_id1']) && (!empty(($ts->foreignKeys['fk_userprofile_user_import_task_id1']))) ) {
            // do nothing
        } else {
            $this->addForeignKey('fk_userprofile_user_import_task_id1', 'user_profile', 'user_import_task_id', 'user_import_task', 'id');
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $ts = $this->db->schema->getTableSchema('user_profile', true);
//        http://adminer.devel.open20.it/?username=admin&db=deployer_platform_434&foreign=user_profile&name=
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        if (isset($ts->foreignKeys['fk_userprofile_user_import_task_id1']) && !empty($ts->foreignKeys['fk_userprofile_user_import_task_id1'])) {
            $this->dropForeignKey('fk_userprofile_user_import_task_id1', 'user_profile');
        }
        $columns = $ts->columnNames;
        if ( in_array('user_import_task_id', $columns) ) {
            $this->dropColumn('user_profile', 'user_import_task_id');
        }
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}