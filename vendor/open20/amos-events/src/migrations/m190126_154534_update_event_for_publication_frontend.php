<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m190126_154534_update_event_for_publication_frontend
 */
class m190126_154534_update_event_for_publication_frontend extends Migration
{
    const TABLE = '{{%event}}';

    public function safeUp()
    {
        $this->update(self::TABLE, ['primo_piano' => 1, 'in_evidenza' => 1],
            ['status' => open20\amos\events\models\Event::EVENTS_WORKFLOW_STATUS_PUBLISHED]);
        return true;
    }

    public function safeDown()
    {
        $this->update(self::TABLE, ['primo_piano' => 0, 'in_evidenza' => 0],
            ['status' => open20\amos\events\models\Event::EVENTS_WORKFLOW_STATUS_PUBLISHED]);
        return true;
    }
}
