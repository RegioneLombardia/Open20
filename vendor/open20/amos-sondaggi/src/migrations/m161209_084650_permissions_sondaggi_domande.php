<?php

class m161209_084650_permissions_sondaggi_domande extends \yii\db\Migration
{

    const TABLE_PERMISSION = '{{%auth_item}}';

    public function safeUp()
    {
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDE_CREATE',
            'type' => '2',
            'description' => 'Permesso di CREATE sul model SONDAGGIDOMANDE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDE_DELETE',
            'type' => '2',
            'description' => 'Permesso di DELETE sul model SONDAGGIDOMANDE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDE_READ',
            'type' => '2',
            'description' => 'Permesso di READ sul model SONDAGGIDOMANDE'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'SONDAGGIDOMANDE_UPDATE',
            'type' => '2',
            'description' => 'Permesso di UPDATE sul model SONDAGGIDOMANDE'
        ]);                         
    }

    public function safeDown()
    {
        echo "Down() non previsto per il file m161209_084650_permissions_sondaggi_domande";
        return false;
    }

}