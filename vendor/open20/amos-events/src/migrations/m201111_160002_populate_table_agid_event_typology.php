<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m201111_160002_populate_table_agid_event_typology
 */
class m201111_160002_populate_table_agid_event_typology extends Migration
{
    const ADMIN_ID = 1;
    
    private $tableName = 'agid_event_typology';
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->batchInsert($this->tableName, [
            'id',
            'name',
            'level',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by'
        ], [
            [
                1,
                'Eventi culturali',
                1,
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                2,
                'Eventi sociali',
                1,
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                3,
                'Eventi politici',
                1,
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                4,
                'Eventi di affari o commerciali',
                1,
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                5,
                'Eventi sportivi',
                1,
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ]
        ]);
        
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->truncateTable($this->tableName);
        return true;
    }
}
