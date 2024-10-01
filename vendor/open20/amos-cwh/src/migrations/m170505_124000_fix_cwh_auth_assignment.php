<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh\migrations
 * @category   CategoryName
 */

use open20\amos\cwh\models\CwhAuthAssignment;
use yii\db\Migration;

class m170505_124000_fix_cwh_auth_assignment extends Migration
{
    const TABLE_NAME = 'cwh_auth_assignment';

    public function safeUp()
    {
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Don't care about the weird layout of this section
        $table = \Yii::$app->db->schema->getTableSchema(self::TABLE_NAME);
        if (isset($table->columns['created_by']) && ($table->columns['created_by']->dbType == 'datetime')) {
            // Backup column
            $this->renameColumn(self::TABLE_NAME, 'created_by', 'created_by_dt');
            // Add new column
            $this->addColumn(self::TABLE_NAME, 'created_by',
                $this->integer()->defaultValue(null)->after('deleted_at')->comment('Created by'));
        }
        if (isset($table->columns['updated_by']) && ($table->columns['updated_by']->dbType == 'datetime')) {
            // Backup column
            $this->renameColumn(self::TABLE_NAME, 'updated_by', 'updated_by_dt');
            // Add new column
            $this->addColumn(self::TABLE_NAME, 'updated_by',
                $this->integer()->defaultValue(null)->after('created_by')->comment('Updated by'));
        }
        if (isset($table->columns['deleted_by']) && ($table->columns['deleted_by']->dbType == 'datetime')) {
            // Backup column
            $this->renameColumn(self::TABLE_NAME, 'deleted_by', 'deleted_by_dt');
            // Add new column
            $this->addColumn(self::TABLE_NAME, 'deleted_by',
                $this->integer()->defaultValue(null)->after('updated_by')->comment('Deleted by'));
        }

        $table = \Yii::$app->db->schema->getTableSchema(self::TABLE_NAME); // Required for new columns
        $query = new \yii\db\Query();
        $query->from(CwhAuthAssignment::tableName());
        $rows = $query->all();
        // Populate new column with old column converted to datetime
        if(!empty($rows)) {
            foreach ($rows as $row) {
                $row2upd = CwhAuthAssignment::findOne($row['id']);
                if (!is_null($row2upd)) {
                    $row2upd->detachBehaviors();
                    /** @var \open20\amos\cwh\ $row */
                    if (isset($table->columns['created_by_dt']) && ($table->columns['created_by']->dbType == 'integer')) {
                        $row2upd->created_by = ($row['created_by_dt'] > 0) ? $row['created_by_dt'] : null;
                    }
                    if (isset($table->columns['updated_by_dt']) && ($table->columns['updated_by']->dbType == 'integer')) {
                        $row2upd->updated_by = ($row['updated_by_dt'] > 0) ? $row['updated_by_dt'] : null;
                    }
                    if (isset($table->columns['deleted_by_dt']) && ($table->columns['deleted_by']->dbType == 'integer')) {
                        $row2upd->deleted_by = ($row['deleted_by_dt'] > 0) ? $row['deleted_by_dt'] : null;
                    }
                    if (!$row2upd->save(false)) {
                        return false;
                    }
                }
            }
        }

        if (isset($table->columns['created_by_dt']) && ($table->columns['created_by_dt']->dbType == 'datetime')) {
            try {
                $this->dropColumn(self::TABLE_NAME, 'created_by_dt');
            } catch (\Exception $exception) {
                print_r($exception->getMessage() . "\n");
                return false;
            }
        }
        if (isset($table->columns['updated_by_dt']) && ($table->columns['updated_by_dt']->dbType == 'datetime')) {
            try {
                $this->dropColumn(self::TABLE_NAME, 'updated_by_dt');
            } catch (\Exception $exception) {
                print_r($exception->getMessage() . "\n");
                return false;
            }
        }
        if (isset($table->columns['deleted_by_dt']) && ($table->columns['deleted_by_dt']->dbType == 'datetime')) {
            try {
                $this->dropColumn(self::TABLE_NAME, 'deleted_by_dt');
            } catch (\Exception $exception) {
                print_r($exception->getMessage() . "\n");
                return false;
            }
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        return true;
    }

    public function safeDown()
    {
        $table = \Yii::$app->db->schema->getTableSchema(self::TABLE_NAME);
        if (isset($table->columns['created_by']) && ($table->columns['created_by']->dbType == 'int(11)')) {
            $this->alterColumn(self::TABLE_NAME, 'created_by', $this->dateTime()->defaultValue(null));
        }
        if (isset($table->columns['updated_by']) && ($table->columns['updated_by']->dbType == 'int(11)')) {
            $this->alterColumn(self::TABLE_NAME, 'updated_by', $this->dateTime()->defaultValue(null));
        }
        if (isset($table->columns['deleted_by']) && ($table->columns['deleted_by']->dbType == 'int(11)')) {
            $this->alterColumn(self::TABLE_NAME, 'deleted_by', $this->dateTime()->defaultValue(null));
        }
        return true;
    }
}
