<?php

class m161209_084653_permissions_sondaggi_role extends \yii\db\Migration {

    const TABLE_PERMISSION = '{{%auth_item_child}}';

    public function safeUp() {
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGI_CREATE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGI_DELETE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGI_READ',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGI_UPDATE',
        ]);

        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDE_CREATE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDE_DELETE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDE_READ',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDE_UPDATE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDEPAGINE_CREATE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDEPAGINE_DELETE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDEPAGINE_READ',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDEPAGINE_UPDATE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDETIPOLOGIE_CREATE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDETIPOLOGIE_DELETE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDETIPOLOGIE_READ',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIDOMANDETIPOLOGIE_UPDATE',
        ]);

        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIRISPOSTEPREDEFINITE_CREATE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIRISPOSTEPREDEFINITE_DELETE',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIRISPOSTEPREDEFINITE_READ',
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'parent' => 'AMMINISTRAZIONE_SONDAGGI',
            'child' => 'SONDAGGIRISPOSTEPREDEFINITE_UPDATE',
        ]);
    }

    public function safeDown() {
        echo "Down() non previsto per il file m161209_084653_permissions_sondaggi_role";
        return false;
    }

}
