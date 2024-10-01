<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use yii\db\Migration;


class m210730_113300_add_column_to_comment_disabled_notification_users extends Migration
{

    public function up()
    {
        $this->addColumn('comment_disabled_notification_users', 'context_model_id', $this->integer()->defaultValue(null)->after('user_id'));
        $this->addColumn('comment_disabled_notification_users', 'context_model_class_name', $this->string()->defaultValue(null)->after('user_id'));
        
    }

    public function down()
    {
        $this->dropColumn('comment_disabled_notification_users', 'context_model_id');
        $this->dropColumn('comment_disabled_notification_users', 'context_model_class_name');
    }   

}