<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bulletcounter}}`.
 */
class m191104_083732_create_bullet_counters_table extends Migration {

    /**
     * Bullet counter table
     * @var type 
     */
    protected $tableName;
    
    /**
     *
     * @var type 
     */
    protected $tableOptions;
    
    /**
     * Somewhere something was changed
     * @var type 
     */
    protected  $tableUpdateContents;

    /**
     * @inheritdoc
     */
    public function init() {
        $this->tableName = '{{%bullet_counters}}';
        $this->tableUpdateContents = '{{%update_contents}}';
        $this->tableOptions = null;
    }

    /**
     * @inheritdoc
     */
    public function safeUp() {
        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * Table with bullets counter for single users and relative widget
         */
        if ($this->db->schema->getTableSchema($this->tableName, true) === null) {
            $this->createTable(
                $this->tableName,
                [
                    'id' => $this->primaryKey(),
                    'widget_icon_id' => $this->integer(11)->notNull()->defaultValue(null)->comment('Widgets con relativo bullet counter'),
                    'user_id' => $this->integer(11)->notNull()->defaultValue(null)->comment('User profile reference'),
                    'counter' => $this->integer(11)->notNull()->defaultValue(null),
                    'created_at' => $this->dateTime(),
                    'updated_at' => $this->dateTime(),
                    'deleted_at' => $this->dateTime()->comment('Cancellato il'),
                    'created_by' => $this->integer(11)->defaultValue(null)->comment('Creato da'),
                    'updated_by' => $this->integer(11)->defaultValue(null)->comment('Aggiornato da'),
                    'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Cancellato da')
                ],
                $this->tableOptions
            );

            
            $this->addForeignKey(
                'fk_user_profile_idx',
                $this->tableName,
                'user_id',
                '{{%user_profile}}',
                'id'
            );

        }
        
        /**
         * Table with bullets counter for single users and relative widget
         */
        if ($this->db->schema->getTableSchema($this->tableUpdateContents, true) === null) {
            $this->createTable(
                $this->tableUpdateContents,
                [
                    'id' => $this->primaryKey(),
                    'module' => $this->char(64)->notNull()->defaultValue(null)->unique()->comment('Module with update contents'),
                    'updates' => $this->integer(1)->notNull()->defaultValue(false)->comment('Some updates here!'),
                    'created_at' => $this->dateTime(),
                    'updated_at' => $this->dateTime(),
                    'deleted_at' => $this->dateTime()->comment('Cancellato il'),
                    'created_by' => $this->integer(11)->defaultValue(null)->comment('Creato da'),
                    'updated_by' => $this->integer(11)->defaultValue(null)->comment('Aggiornato da'),
                    'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Cancellato da')
                ],
                $this->tableOptions
            );
        }
            
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropTable($this->tableName);
        $this->dropTable($this->tableUpdateContents);
    }

}
