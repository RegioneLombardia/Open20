<?php

use open20\amos\events\models\EventCategory;
use yii\db\Migration;

class m200515_115513_add_categories extends Migration
{
    const TABLE_NAME = "event_category";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        Yii::$app->db->createCommand()->truncateTable(self::TABLE_NAME)->execute();

        $this->insert(EventCategory::tableName(),
            [
            'id' => 1,
            'name' => 'Aperture Palazzo Lombardia e Palazzo Pirelli',
            'description' => 'Aperture Palazzo Lombardia e Palazzo Pirelli',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'created_by' => 1,
            'updated_by' => 1
        ]);
        $this->insert(EventCategory::tableName(),
            [
            'id' => 2,
            'name' => 'Convegni e Tavole Rotonde',
            'description' => 'Convegni e Tavole Rotonde',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'created_by' => 1,
            'updated_by' => 1
        ]);
        $this->insert(EventCategory::tableName(),
            [
            'id' => 3,
            'name' => 'Eventi e Iniziative Istituzionali',
            'description' => 'Eventi e Iniziative Istituzionali',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'created_by' => 1,
            'updated_by' => 1
        ]);
        $this->insert(EventCategory::tableName(),
            [
            'id' => 4,
            'name' => 'Incontri Pubblici e Audizioni',
            'description' => 'Incontri Pubblici e Audizioni',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'created_by' => 1,
            'updated_by' => 1
        ]);
        $this->insert(EventCategory::tableName(),
            [
            'id' => 5,
            'name' => 'Incontri Tecnici e Formativi',
            'description' => 'Incontri Tecnici e Formativi',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'created_by' => 1,
            'updated_by' => 1
        ]);
        $this->insert(EventCategory::tableName(),
            [
            'id' => 6,
            'name' => 'Manifestazioni',
            'description' => 'Manifestazioni',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'created_by' => 1,
            'updated_by' => 1
        ]);
        $this->insert(EventCategory::tableName(),
            [
            'id' => 7,
            'name' => 'Fiere',
            'description' => 'Fiere',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'created_by' => 1,
            'updated_by' => 1
        ]);
        $this->insert(EventCategory::tableName(),
            [
            'id' => 8,
            'name' => 'Mostre ed esposizioni',
            'description' => 'Mostre ed esposizioni',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'created_by' => 1,
            'updated_by' => 1
        ]);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->delete(EventCategory::tableName(), 'id IN (1, 2, 3, 4,5,6,7,8)');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}