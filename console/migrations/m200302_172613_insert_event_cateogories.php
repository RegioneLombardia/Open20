<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200302_172613_insert_event_cateogories extends Migration
{
    const TABLE_EVENT_CATEGORY = "event_category";







    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert(self::TABLE_EVENT_CATEGORY, ['name' => 'Mostre ed esposizioni', 'description' => 'Mostre ed esposizioni']);
        $this->insert(self::TABLE_EVENT_CATEGORY, ['name' => 'Concerti', 'description' => 'Concerti']);
        $this->insert(self::TABLE_EVENT_CATEGORY, ['name' => 'Manifestazioni', 'description' => 'Manifestazioni']);
        $this->insert(self::TABLE_EVENT_CATEGORY, ['name' => 'Iniziative di sensibilizzazione', 'description' => 'Iniziative di sensibilizzazione']);
        $this->insert(self::TABLE_EVENT_CATEGORY, ['name' => 'Workshop', 'description' => 'Workshop']);
        $this->insert(self::TABLE_EVENT_CATEGORY, ['name' => 'Convegni', 'description' => 'Convegni']);
        $this->insert(self::TABLE_EVENT_CATEGORY, ['name' => 'Fiere', 'description' => 'Fiere']);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->delete(self::TABLE_EVENT_CATEGORY, ['name' => 'Mostre ed esposizioni', 'description' => 'Mostre ed esposizioni']);
        $this->delete(self::TABLE_EVENT_CATEGORY, ['name' => 'Concerti', 'description' => 'Concerti']);
        $this->delete(self::TABLE_EVENT_CATEGORY, ['name' => 'Manifestazioni', 'description' => 'Manifestazioni']);
        $this->delete(self::TABLE_EVENT_CATEGORY, ['name' => 'Iniziative di sensibilizzazione', 'description' => 'Iniziative di sensibilizzazione']);
        $this->delete(self::TABLE_EVENT_CATEGORY, ['name' => 'Workshop', 'description' => 'Workshop']);
        $this->delete(self::TABLE_EVENT_CATEGORY, ['name' => 'Convegni', 'description' => 'Convegni']);
        $this->delete(self::TABLE_EVENT_CATEGORY, ['name' => 'Fiere', 'description' => 'Fiere']);


        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
