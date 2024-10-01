<?php

use open20\gamification\models\PunteggiUtenti;
use yii\db\Migration;

class m231114_163900_update_table_punteggi_gamif_fk extends Migration
{
    private $tabella = null;

    public function __construct()
    {
        $this->tabella = PunteggiUtenti::tableName();
        parent::__construct();
    }

    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->addForeignKey('fk_gamification_id', $this->tabella, 'gamification_id', 'gamification', 'id', 'RESTRICT');
        $this->addForeignKey('fk_user_profile_id', $this->tabella, 'user_profile_id', 'user_profile', 'id', 'RESTRICT');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        return true;
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropForeignKey('fk_gamification_id', $this->tabella);
        $this->dropForeignKey('fk_user_profile_id', $this->tabella);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        return true;
    }
}
