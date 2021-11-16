<?php

use open20\amos\events\models\EventCategory;
use yii\db\Migration;

class m200805_160600_delted_tags_preferences extends Migration
{
    const TABLE_NAME = "event";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        //salute e prenvenzione
        $this->update('tag',  ['deleted_at' => '2020-07-30 12:00:00', 'deleted_by' => 1], ['codice' => 'preference_4']);
        //agricoltura
        $this->update('tag',  ['deleted_at' => '2020-07-30 12:00:00', 'deleted_by' => 1], ['codice' => 'preference_16']);
        //sport
        $this->update('tag',  ['deleted_at' => '2020-07-30 12:00:00', 'deleted_by' => 1], ['codice' => 'preference_17']);
        //sociale
        $this->update('tag',  ['deleted_at' => '2020-07-30 12:00:00', 'deleted_by' => 1], ['codice' => 'preference_14']);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->update('tag',  ['deleted_at' => null, 'deleted_by' => null], ['codice' => 'preference_4']);
        //agricoltura
        $this->update('tag',  ['deleted_at' => null, 'deleted_by' => null], ['codice' => 'preference_16']);
        //sport
        $this->update('tag',  ['deleted_at' => null, 'deleted_by' => null], ['codice' => 'preference_17']);
        //sociale
        $this->update('tag',  ['deleted_at' => null, 'deleted_by' => null], ['codice' => 'preference_14']);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }

}