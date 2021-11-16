<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\user\User;
use yii\db\Migration;

class m170504_143013_fix_temp_user extends Migration
{
    const TABLE_NAME = 'user';

    public function safeUp()
    {
        $table = \Yii::$app->db->schema->getTableSchema(self::TABLE_NAME);
        if (!isset($table->columns['deleted_at'])) {
            $this->addColumn(self::TABLE_NAME, 'deleted_at',
                $this->dateTime()->defaultValue(null)->comment('Deleted at'));
        }
        if (!isset($table->columns['created_by'])) {
            $this->addColumn(self::TABLE_NAME, 'created_by',
                $this->integer()->defaultValue(null)->comment('Created by'));
        }
        if (!isset($table->columns['updated_by'])) {
            $this->addColumn(self::TABLE_NAME, 'updated_by',
                $this->integer()->defaultValue(null)->comment('Updated by'));
        }
        if (!isset($table->columns['deleted_by'])) {
            $this->addColumn(self::TABLE_NAME, 'deleted_by',
                $this->integer()->defaultValue(null)->comment('Deleted by'));
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Don't care about the weird layout of this section
        $table = \Yii::$app->db->schema->getTableSchema(self::TABLE_NAME);
        if (isset($table->columns['created_at']) && ($table->columns['created_at']->dbType == 'int(11)'
                || $table->columns['created_at']->dbType == 'int')) {
            // Backup column
            $this->renameColumn(self::TABLE_NAME, 'created_at', 'created_at_int');
            // Add new column
            $this->addColumn(self::TABLE_NAME, 'created_at',
                $this->dateTime()->defaultValue(null)->after('status')->comment('Created at'));
        }
        if (isset($table->columns['updated_at']) && ($table->columns['updated_at']->dbType == 'int(11)'
                || $table->columns['updated_at']->dbType == 'int')) {
            // Backup column
            $this->renameColumn(self::TABLE_NAME, 'updated_at', 'updated_at_int');
            // Add new column
            $this->addColumn(self::TABLE_NAME, 'updated_at',
                $this->dateTime()->defaultValue(null)->after('created_at')->comment('Updated at'));
        }

        $table = \Yii::$app->db->schema->getTableSchema(self::TABLE_NAME); // Required for new columns
        $query = new \yii\db\Query();
        $query->from(User::tableName());
        $users = $query->all();
        // Populate new column with old column converted to datetime
        foreach ($users as $user) {
            $user2upd = User::findOne($user['id']);
            if (is_null($user2upd)) {
                continue;
            }
            $user2upd->detachBehaviors();
            /** @var \open20\amos\core\user\User $user */
            if(isset($user['created_at_int'])){
                $user2upd->created_at = date('Y-m-d H:i:s', $user['created_at_int']);
            }
            if(isset($user['updated_at_int'])){
		$user2upd->updated_at = date('Y-m-d H:i:s', $user['updated_at_int']);
            }
            $user2upd->created_by = 1;
            $user2upd->updated_by = 1;
            if (!$user2upd->save(false)) {
                return false;
            }
        }

        if (isset($table->columns['created_at_int']) && ($table->columns['created_at_int']->dbType == 'int(11)')) {
            try {
                $this->dropColumn(self::TABLE_NAME, 'created_at_int');
            } catch (\Exception $exception) {
                print_r($exception->getMessage() . "\n");
                return false;
            }
        }
        if (isset($table->columns['updated_at_int']) && ($table->columns['updated_at_int']->dbType == 'int(11)')) {
            try {
                $this->dropColumn(self::TABLE_NAME, 'updated_at_int');
            } catch (\Exception $exception) {
                print_r($exception->getMessage() . "\n");
                return false;
            }
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }

    public function safeDown()
    {
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Don't care about the weird layout of this section
        $table = \Yii::$app->db->schema->getTableSchema(self::TABLE_NAME);
        if (isset($table->columns['created_at']) && ($table->columns['created_at']->dbType == 'datetime')) {
            // Backup column
            $this->renameColumn(self::TABLE_NAME, 'created_at', 'created_at_dt');
            // Add new column
            $this->addColumn(self::TABLE_NAME, 'created_at', $this->integer());
        }
        if (isset($table->columns['updated_at']) && ($table->columns['updated_at']->dbType == 'datetime')) {
            // Backup column
            $this->renameColumn(self::TABLE_NAME, 'updated_at', 'updated_at_dt');
            // Add new column
            $this->addColumn(self::TABLE_NAME, 'updated_at', $this->integer());
        }

        $table = \Yii::$app->db->schema->getTableSchema(self::TABLE_NAME); // Required for new columns
        $query = new \yii\db\Query();
        $query->from(User::tableName());
        $users = $query->all();
        // Populate new column with old column converted to datetime
        foreach ($users as $user) {
            $user2upd = User::findOne($user['id']);
            if (is_null($user2upd)) {
                continue;
            }
            $user2upd->detachBehaviors();
            $created_at_dt = new DateTime($user['created_at_dt']);
            $updated_at_dt = new DateTime($user['updated_at_dt']);
            /** @var \open20\amos\core\user\User $user */
            $user2upd->created_at = $created_at_dt->getTimestamp();
            $user2upd->updated_at = $updated_at_dt->getTimestamp();
            if (!$user2upd->save(false)) {
                return false;
            }
        }

        if (isset($table->columns['created_at_dt']) && ($table->columns['created_at_dt']->dbType == 'datetime')) {
            try {
                $this->dropColumn(self::TABLE_NAME, 'created_at_dt');
            } catch (\Exception $exception) {
                print_r($exception->getMessage() . "\n");
                return false;
            }
        }
        if (isset($table->columns['updated_at_dt']) && ($table->columns['updated_at_dt']->dbType == 'datetime')) {
            try {
                $this->dropColumn(self::TABLE_NAME, 'updated_at_dt');
            } catch (\Exception $exception) {
                print_r($exception->getMessage() . "\n");
                return false;
            }
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $table = \Yii::$app->db->schema->getTableSchema(self::TABLE_NAME); // Required for removed columns
        if (isset($table->columns['deleted_at'])) {
            $this->dropColumn(self::TABLE_NAME, 'deleted_at');
        }
        if (isset($table->columns['created_by'])) {
            $this->dropColumn(self::TABLE_NAME, 'created_by');
        }
        if (isset($table->columns['updated_by'])) {
            $this->dropColumn(self::TABLE_NAME, 'updated_by');
        }
        if (isset($table->columns['deleted_by'])) {
            $this->dropColumn(self::TABLE_NAME, 'deleted_by');
        }
    }
}
