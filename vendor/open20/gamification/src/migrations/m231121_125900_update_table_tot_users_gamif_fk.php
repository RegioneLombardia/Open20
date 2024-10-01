<?php

use open20\gamification\models\PunteggiTotUtenti;
use yii\db\Migration;

class m231121_125900_update_table_tot_users_gamif_fk extends Migration
{
    private $tabella = null;

    public function __construct()
    {
        $this->tabella = PunteggiTotUtenti::tableName();
        parent::__construct();
    }

    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->addForeignKey('fk_gamification_tot_users_gamif_id', $this->tabella, 'gamification_id', 'gamification', 'id', 'RESTRICT');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        return true;
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropForeignKey('fk_gamification_tot_users_gamif_id', $this->tabella);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        return true;
    }
}
