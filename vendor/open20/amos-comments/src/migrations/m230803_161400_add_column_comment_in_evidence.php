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


class m230803_161400_add_column_comment_in_evidence extends Migration
{
    const TABLE = '{{%comment}}';

    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'in_evidence',
            $this->integer()->defaultValue(0)->after('status')
        );
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'in_evidence');
    }

}