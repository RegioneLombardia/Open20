<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m210728_102400_add_playlist_youtube`.
 */
class m210728_102400_add_playlist_youtube extends Migration
{
    const TABLE = "preference_landing";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'playlist_youtube_description', $this->string(255)->defaultValue(null)->after('url'));
        $this->addColumn(self::TABLE, 'playlist_youtube_url', $this->string(255)->defaultValue(null)->after('playlist_youtube_description'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'playlist_youtube_description');
        $this->dropColumn(self::TABLE, 'playlist_youtube_url');
    }
}
