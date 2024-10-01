<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\ProfiloSediTypes;
use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m180829_095638_populate_table_profilo_sedi_types
 */
class m180829_095638_populate_table_profilo_sedi_types extends Migration
{
    const ADMIN_ID = 1;

    private $tableName = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = ProfiloSediTypes::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->batchInsert($this->tableName, [
            'id',
            'name',
            'active',
            'read_only',
            'order',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by'
        ], [
            [
                1,
                'Sede legale',
                1,
                1,
                1,
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                2,
                'Sede operativa',
                1,
                1,
                2,
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
        ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if ($this->db->driverName === 'mysql') {
            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
        }
        $this->delete($this->tableName, ['in', 'id', [1, 2]]);
        if ($this->db->driverName === 'mysql') {
            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        }
        return true;
    }
}
