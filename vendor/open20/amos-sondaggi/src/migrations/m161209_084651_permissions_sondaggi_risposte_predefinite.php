<?php

class m161209_084651_permissions_sondaggi_risposte_predefinite extends \yii\db\Migration
{

    const TABLE_PERMISSION = '{{%auth_item}}';

    public function safeUp()
    {
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIRISPOSTEPREDEFINITE_CREATE',
            'type' => '2',
            'description' => 'Permesso di CREATE sul model SONDAGGIRISPOSTEPREDEFINITE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIRISPOSTEPREDEFINITE_DELETE',
            'type' => '2',
            'description' => 'Permesso di DELETE sul model SONDAGGIRISPOSTEPREDEFINITE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIRISPOSTEPREDEFINITE_READ',
            'type' => '2',
            'description' => 'Permesso di READ sul model SONDAGGIRISPOSTEPREDEFINITE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIRISPOSTEPREDEFINITE_UPDATE',
            'type' => '2',
            'description' => 'Permesso di UPDATE sul model SONDAGGIRISPOSTEPREDEFINITE'
        ]);                         
    }

    public function safeDown()
    {
        echo "Down() non previsto per il file m161209_084651_permissions_sondaggi_risposte_predefinite";
        return false;
    }

}