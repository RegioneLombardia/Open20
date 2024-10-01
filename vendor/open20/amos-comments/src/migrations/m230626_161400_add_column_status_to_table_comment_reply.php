<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use open20\amos\comments\models\Comment;
use yii\db\Migration;


class m230626_161400_add_column_status_to_table_comment_reply extends Migration
{
    const TABLE = '{{%comment_reply}}';

    public function up()
    {
        $this->addColumn(self::TABLE, 'status',
            $this->string()->notNull()->defaultValue(Comment::STATUS_PUBLISHED)->after('comment_id')
        );
    }

    public function down()
    {
        $this->dropColumn(self::TABLE, 'status');
    }

}