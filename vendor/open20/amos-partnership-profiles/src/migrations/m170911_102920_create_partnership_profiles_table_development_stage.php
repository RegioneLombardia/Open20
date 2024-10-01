<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;
use yii\db\Expression;

/**
 * Class m170911_102920_create_partnership_profiles_table_development_stage
 */
class m170911_102920_create_partnership_profiles_table_development_stage extends AmosMigrationTableCreation
{
    const ADMIN_ID = 1;
    
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%development_stage}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'value' => $this->string(255)->notNull()->comment('Value')
        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function beforeTableCreation()
    {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }
    
    /**
     * @inheritdoc
     */
    protected function afterTableCreation()
    {
        $this->batchInsert($this->tableName, [
            'id',
            'value',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by'
        ], [
            [
                1,
                'On the market',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                2,
                'Demonstrable',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                3,
                'Idea',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                4,
                'Tested in the lab',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ]
        ]);
    }
}
