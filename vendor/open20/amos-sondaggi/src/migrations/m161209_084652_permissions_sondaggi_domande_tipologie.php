<?php

class m161209_084652_permissions_sondaggi_domande_tipologie extends \yii\db\Migration
{

    const TABLE_PERMISSION = '{{%auth_item}}';

    public function safeUp()
    {
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDETIPOLOGIE_CREATE',
            'type' => '2',
            'description' => 'Permesso di CREATE sul model SONDAGGIDOMANDETIPOLOGIE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDETIPOLOGIE_DELETE',
            'type' => '2',
            'description' => 'Permesso di DELETE sul model SONDAGGIDOMANDETIPOLOGIE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDETIPOLOGIE_READ',
            'type' => '2',
            'description' => 'Permesso di READ sul model SONDAGGIDOMANDETIPOLOGIE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDETIPOLOGIE_UPDATE',
            'type' => '2',
            'description' => 'Permesso di UPDATE sul model SONDAGGIDOMANDETIPOLOGIE'
        ]);                         
    }

    public function safeDown()
    {
        echo "Down() non previsto per il file m161209_084652_permissions_sondaggi_domande_tipologie";
        return false;
    }
}