<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200331_180013_personlaized_colors_event_type extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('event_type', ['color' => "#CB740B"], ['id' => \open20\amos\events\models\EventType::TYPE_INFORMATIVE]);
        $this->update('event_type', ['color' => "#CB250B"], ['id' => \open20\amos\events\models\EventType::TYPE_LIMITED_SEATS]);
        $this->update('event_type', ['color' => "#737373"], ['id' => \open20\amos\events\models\EventType::TYPE_OPEN]);
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
