<?php

use open20\amos\discussioni\models\DiscussioniTopic;

use yii\db\Migration;

class m170606_073900_add_discussioni_slug extends Migration
{
    private 
        $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = DiscussioniTopic::tableName();
        
    }

    /**
     * @inheritdoc
     * @return boolean
     */
    public function safeUp()
    {
        $table = $this->db->getTableSchema($this->tableName);
        if (!isset($table->columns['slug'])) {
            $this->addColumn(
                $this->tableName,
                'slug',
                $this->text()->null()->after('id')
            );
        }
        
        if (!isset($table->columns['close_comment_thread'])) {
            $this->addColumn(
                $this->tableName, 
                'close_comment_thread', 
                $this->boolean()->notNull()->defaultValue(0)->comment('Close discussion')->after('slug')
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     * @return boolean
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName(), 'slug');
        $this->dropColumn($this->tableName(), 'close_comment_thread');

        return true;
    }
}
