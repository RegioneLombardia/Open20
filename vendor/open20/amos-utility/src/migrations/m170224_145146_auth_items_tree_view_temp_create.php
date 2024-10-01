<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m170224_145146_auth_items_tree_view_temp_create extends \yii\db\Migration
{
    const TABLE = '{{%auth_items_tree_view_temp}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => $this->primaryKey(11),
                'parent' => $this->integer(11)->defaultValue(null)->comment('Parent'),
                'item' => $this->string(255)->defaultValue(null)->comment('Item'),
                'type' => $this->integer(11)->defaultValue(null)->comment('Type'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        } else {
            echo "Tabella esiste gia': " . self::TABLE;
        }
        return true;
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null) {
            $this->dropTable(self::TABLE);
        } else {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }
        return true;
    }
}