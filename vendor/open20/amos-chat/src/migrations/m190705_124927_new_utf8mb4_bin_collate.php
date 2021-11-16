<?php

use yii\db\Migration;

/**
 * Class m190705_124927_new_utf8mb4_bin_collate
 */
class m190705_124927_new_utf8mb4_bin_collate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableName = '{{%amoschat_message}}';
        
        $db = Yii::$app->getDb();
        $db->createCommand("ALTER TABLE $tableName CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin")->execute();
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $tableName = '{{%amoschat_message}}';
        
        $db = Yii::$app->getDb();
        $db->createCommand("ALTER TABLE $tableName CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        return true;
    }

    
}
