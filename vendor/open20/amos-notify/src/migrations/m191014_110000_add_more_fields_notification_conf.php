<?php

use yii\db\Migration;

class m191014_110000_add_more_fields_notification_conf extends Migration
{
    const TABLE = 'notificationconf';

    public function safeUp()
    {
       $defaultValueEmail = open20\amos\notificationmanager\widgets\NotifyFrequencyAdvancedWidget::DEFAULT_EMAIL_FREQUENCY;
       $this->addColumn(self::TABLE, 'profilo_successo_email' ,$this->integer(1)->defaultValue($defaultValueEmail)->after('sms'));
       $this->addColumn(self::TABLE, 'contenuti_successo_email' ,$this->integer(1)->defaultValue($defaultValueEmail)->after('sms'));
       $this->addColumn(self::TABLE, 'periodo_inattivita_flag' ,$this->integer(1)->defaultValue(1)->after('sms'));
       $this->addColumn(self::TABLE, 'contatti_suggeriti_email' ,$this->integer(1)->defaultValue($defaultValueEmail)->after('sms'));
       $this->addColumn(self::TABLE, 'contatto_accettato_flag' ,$this->integer(1)->defaultValue(1)->after('sms'));
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'contatto_accettato_flag');
        $this->dropColumn(self::TABLE, 'contatti_suggeriti_email');
        $this->dropColumn(self::TABLE, 'periodo_inattivita_flag');
        $this->dropColumn(self::TABLE, 'contenuti_successo_email');
        $this->dropColumn(self::TABLE, 'profilo_successo_email');
    }
}
