<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200401_125313_personlaized_event_type extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('event_type', ['color' => "#737373", 'partners' => false], ['id' => \open20\amos\events\models\EventType::TYPE_INFORMATIVE]);
        $this->update('event_type', ['color' => "#CB250B", 'partners' => false], ['id' => \open20\amos\events\models\EventType::TYPE_LIMITED_SEATS]);
        $this->update('event_type', ['color' => "#CB740B", 'partners' => false], ['id' => \open20\amos\events\models\EventType::TYPE_OPEN]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
