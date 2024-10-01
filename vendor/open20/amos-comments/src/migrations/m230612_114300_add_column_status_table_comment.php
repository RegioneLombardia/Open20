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


class m230612_114300_add_column_status_table_comment extends Migration
{
    const TABLE = '{{%comment}}';

    public function up()
    {
        $this->addColumn(self::TABLE, 'status',
            $this->string()->notNull()->defaultValue(Comment::STATUS_PUBLISHED)->after('context_id')
        );
    }

    public function down()
    {
        $this->dropColumn(self::TABLE, 'status');
    }

}