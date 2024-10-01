<?php

use yii\db\Migration;

class m220527_093300_create_table_collaboration_proposals extends Migration
{
    const TABLE_COLLABORATION_PROPOSALS            = '{{%collaboration_proposals}}';
    const TABLE_COLLABORATION_CATEGORY             = '{{%collaboration_category}}';
    const TABLE_COLLABORATION_TYPE                 = '{{%collaboration_type}}';
    const TABLE_COLLABORATION_STAGE_OF_DEVELOPMENT = '{{%collaboration_stage_of_development}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_COLLABORATION_PROPOSALS, true) === null) {
            $this->createTable(self::TABLE_COLLABORATION_PROPOSALS, [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull()->comment('Title'),
                'category_id' => $this->integer()->notNull()->comment('Category ID'),
                'collaboration_type_id' => $this->integer()->notNull()->comment('Collaboration Sought ID'),
                'short_summary' => $this->text(500)->notNull()->comment('Short Summary'),
                'full_description' => $this->text(4000)->notNull()->comment('Full Description'),
                'advantages' => $this->text(1000)->comment('Advantages / Innovation'),
                'technical_specifications' => $this->text(1000)->comment('Technical Specifications / Expertise Sought'),
                'stage_of_development_id' => $this->integer()->notNull()->comment('Stage of Development'),
                'partner_role' => $this->text(4000)->notNull()->comment('Role of Partner Sought'),
                'caption' => $this->string(100)->null()->defaultValue(null)->comment('Caption'),
                'short_abstract' => $this->string(100)->null()->defaultValue(null)->comment('Short Abstract'),
                'video_pitch' => $this->string()->null()->defaultValue(null)->comment('Video Pitch'),
                'video_title' => $this->string(250)->null()->defaultValue(null)->comment('Video Title'),
                'start_date' => $this->date()->notNull()->comment('Start Date'),
                'end_date' => $this->date()->notNull()->comment('End Date'),

                'created_by' => $this->integer()->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_by' => $this->integer()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
                'deleted_by' => $this->integer()->null()->defaultValue(null),
                'deleted_at' => $this->dateTime()->null()->defaultValue(null),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE_COLLABORATION_PROPOSALS);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
