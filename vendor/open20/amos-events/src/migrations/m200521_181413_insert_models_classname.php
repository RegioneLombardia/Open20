<?php

use yii\db\Migration;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200521_181413_insert_models_classname extends Migration
{
    const TABLE_EVENT_LIST= "event";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('models_classname', [
            'classname' => \open20\amos\events\models\EventInvitation::className(),
            'module' => 'events',
            'label' => ' Event invitation',
        ]);

        $this->insert('models_classname', [
            'classname' => \open20\amos\events\models\EventInvitationSent::className(),
            'module' => 'events',
            'label' => ' Event invitation sent',
        ]);

        $this->insert('models_classname', [
            'classname' => \open20\amos\events\models\EventParticipantCompanion::className(),
            'module' => 'events',
            'label' => ' Event participant companion',
        ]);

        $this->insert('models_classname', [
            'classname' => \open20\amos\events\models\EventCommunicationSent::className(),
            'module' => 'events',
            'label' => ' Event Communication sent',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->delete('models_classname', [
            'classname' => \open20\amos\events\models\EventInvitation::className(),
            'module' => 'events',
            'label' => ' Event invitation',
        ]);

        $this->delete('models_classname', [
            'classname' => \open20\amos\events\models\EventInvitationSent::className(),
            'module' => 'events',
            'label' => ' Event invitation sent',
        ]);

        $this->delete('models_classname', [
            'classname' => \open20\amos\events\models\EventParticipantCompanion::className(),
            'module' => 'events',
            'label' => ' Event participant companion',
        ]);

        $this->delete('models_classname', [
            'classname' => \open20\amos\events\models\EventCommunicationSent::className(),
            'module' => 'events',
            'label' => ' Event Communication sent',
        ]);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
