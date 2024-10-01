<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

class m150803_000009_permissions_cwh extends \yii\db\Migration
{

    const TABLE_PERMISSION = '{{%auth_item}}';

    public function safeUp()
    {
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHREGOLEPUBBLICAZIONE_CREATE',
            'type' => '2',
            'description' => 'Permesso di CREATE sul model CwhRegolePubblicazione'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHREGOLEPUBBLICAZIONE_DELETE',
            'type' => '2',
            'description' => 'Permesso di DELETE sul model CwhRegolePubblicazione'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHREGOLEPUBBLICAZIONE_READ',
            'type' => '2',
            'description' => 'Permesso di READ sul model CwhRegolePubblicazione'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHREGOLEPUBBLICAZIONE_UPDATE',
            'type' => '2',
            'description' => 'Permesso di UPDATE sul model CwhRegolePubblicazione'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHCONFIG_CREATE',
            'type' => '2',
            'description' => 'Permesso di CREATE sul model CwhConfig'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHCONFIG_DELETE',
            'type' => '2',
            'description' => 'Permesso di DELETE sul model CwhConfig'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHCONFIG_READ',
            'type' => '2',
            'description' => 'Permesso di READ sul model CwhConfig'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHCONFIG_UPDATE',
            'type' => '2',
            'description' => 'Permesso di UPDATE sul model CwhConfig'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHNODI_CREATE',
            'type' => '2',
            'description' => 'Permesso di CREATE sul model CwhNodi'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHNODI_DELETE',
            'type' => '2',
            'description' => 'Permesso di DELETE sul model CwhNodi'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHNODI_READ',
            'type' => '2',
            'description' => 'Permesso di READ sul model CwhNodi'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => 'CWHNODI_UPDATE',
            'type' => '2',
            'description' => 'Permesso di UPDATE sul model CwhNodi'
        ]);
    }

    public function safeDown()
    {
        echo "Down() non previsto per il file m150803_000009_permissions_cwh";
        return true;
    }

}