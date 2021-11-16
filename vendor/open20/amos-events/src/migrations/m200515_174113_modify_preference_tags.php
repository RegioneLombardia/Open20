<?php

use open20\amos\events\models\EventCategory;
use yii\db\Migration;

class m200515_174113_modify_preference_tags extends Migration
{
    const TABLE_NAME = "event";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->update('tag',  ['nome' => 'Salute e prevenzione'], ['codice' => 'preference_4']);
        $this->update('tag',  ['nome' => 'Agricoltura e alimentazione'], ['codice' => 'preference_16']);
        $this->update('tag',  ['nome' => 'Ambiente e Sviluppo sostenibile'], ['codice' => 'preference_18']);
        $this->update('tag',  ['nome' => 'Mobilità, trasporti e blocchi del traffico'], ['codice' => 'preference_1']);

        $this->delete('tag',  ['codice' => 'preference_7']);
        $this->delete('tag',  ['codice' => 'preference_8']);
        $this->delete('tag',  ['codice' => 'preference_9']);
        $this->delete('tag',  ['codice' => 'preference_11']);
        $this->delete('tag',  ['codice' => 'preference_12']);
        $this->delete('tag',  ['codice' => 'preference_15']);



        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
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