<?php

class m230612_125500_insert_configuration_comment_dashboard extends \yii\db\Migration
{
    const TABLE = '{{%comment_dashboard}}';

    public function safeUp()
    {
        $this->insert(self::TABLE, [
            'netiquette_platform' => 0,
            'netiquette_communities' => 0,
            'moderation_platform' => 0,
            'moderation_communities' => 0,
        ]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE, ['id' => 1]);
    }
}