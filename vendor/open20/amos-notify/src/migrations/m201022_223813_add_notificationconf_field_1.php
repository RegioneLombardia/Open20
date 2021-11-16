<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify\migrations
 * @category   CategoryName
 */

use open20\amos\notificationmanager\models\NotificationConf;
use yii\db\Migration;

/**
 * Class m201022_223813_add_notificationconf_field_1
 */
class m201022_223813_add_notificationconf_field_1 extends Migration
{
    private $tableName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = NotificationConf::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'notify_ticket_faq_referee', $this->boolean()->null()->defaultValue(1)->after('notify_comments'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'notify_ticket_faq_referee');
        return true;
    }
}
