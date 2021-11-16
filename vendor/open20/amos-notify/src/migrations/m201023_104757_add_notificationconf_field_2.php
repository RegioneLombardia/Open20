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
 * Class m201023_104757_add_notificationconf_field_2
 */
class m201023_104757_add_notificationconf_field_2 extends Migration
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
        $this->addColumn($this->tableName, 'notify_newsletter', $this->boolean()->null()->defaultValue(1)->after('notify_ticket_faq_referee'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'notify_newsletter');
        return true;
    }
}
