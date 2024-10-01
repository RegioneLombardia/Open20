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
 * Class m170911_102944_create_partnership_profiles_table_partnership_profiles_type
 */
class m170911_102944_create_partnership_profiles_table_partnership_profiles_type extends AmosMigrationTableCreation
{
    const ADMIN_ID = 1;
    
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%partnership_profiles_type}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Name'),
            'description' => $this->string(255)->null()->defaultValue(null)->comment('Description')
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
            'name',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by'
        ], [
            [
                1,
                'Research',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                2,
                'Technical collaboration',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                3,
                'Proscription',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                4,
                'Join Venture',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                5,
                'Subcontracting',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ]
        ]);
    }
}
