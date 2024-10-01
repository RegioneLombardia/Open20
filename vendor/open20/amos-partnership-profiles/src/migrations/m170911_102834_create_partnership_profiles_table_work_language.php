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
 * Class m170911_102834_create_partnership_profiles_table_work_language
 */
class m170911_102834_create_partnership_profiles_table_work_language extends AmosMigrationTableCreation
{
    const ADMIN_ID = 1;
    
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%work_language}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'work_language_code' => $this->string(5)->null()->defaultValue(null)->comment('Work language code'),
            'work_language' => $this->string(255)->null()->defaultValue(null)->comment('Work language')
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
            'work_language_code',
            'work_language',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by'
        ], [
            [
                1,
                'it-IT',
                'Italian',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                2,
                'en-GB',
                'English',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                3,
                'fr-FR',
                'French',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                4,
                'de-DE',
                'German',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ],
            [
                5,
                'ru-RU',
                'Russian',
                new Expression('NOW()'),
                new Expression('NOW()'),
                self::ADMIN_ID,
                self::ADMIN_ID
            ]
        ]);
    }
}
