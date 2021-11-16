<?php

class m161209_084649_permissions_sondaggi_domande_pagine extends \yii\db\Migration
{

    const TABLE_PERMISSION = '{{%auth_item}}';

    public function safeUp()
    {
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDEPAGINE_CREATE',
            'type' => '2',
            'description' => 'Permesso di CREATE sul model SONDAGGIDOMANDEPAGINE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDEPAGINE_DELETE',
            'type' => '2',
            'description' => 'Permesso di DELETE sul model SONDAGGIDOMANDEPAGINE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDEPAGINE_READ',
            'type' => '2',
            'description' => 'Permesso di READ sul model SONDAGGIDOMANDEPAGINE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDEPAGINE_UPDATE',
            'type' => '2',
            'description' => 'Permesso di UPDATE sul model SONDAGGIDOMANDEPAGINE'
        ]);                         
    }

    public function safeDown()
    {
        echo "Down() non previsto per il file m161209_084649_permissions_sondaggi_domande_pagine";
        return false;
    }

}