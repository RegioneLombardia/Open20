<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m181011_093428_populate_table_profilo_enti_type
 */
class m181011_093428_populate_table_profilo_enti_type extends Migration
{
    const ADMIN_ID = 1;

    private $tableName = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = '{{%profilo_enti_type}}';
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->batchInsert($this->tableName, [
            'id',
            'name'
        ], [
            [
                1,
                'Comune (Amministratori e dipendenti comunali)'
            ],
            [
                2,
                'Altro ente (Persone appartenenti a enti non comunali)'
            ]
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
