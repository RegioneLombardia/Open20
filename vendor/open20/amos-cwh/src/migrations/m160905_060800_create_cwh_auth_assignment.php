<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\rbac\DbManager;

class m160905_060800_create_cwh_auth_assignment extends Migration
{
    const TABLE = '{{%cwh_auth_assignment}}';

    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => $this->primaryKey(),
                'item_name' => $this->string(255)->notNull(),
                'user_id' => $this->string(255)->notNull(),
                'cwh_nodi_id' => $this->string(255)->notNull(),
                'created_at' => $this->dateTime()->null()->comment("Creato il"),
                'updated_at' => $this->dateTime()->null()->comment("Aggiornato il"),
                'deleted_at' => $this->dateTime()->null()->comment("Cancellato il"),
                'created_by' => $this->dateTime()->null()->comment("Creato da"),
                'updated_by' => $this->dateTime()->null()->comment("Aggiornato da"),
                'deleted_by' => $this->dateTime()->null()->comment("Cancellato da"),
            ], $tableOptions);

            $this->addForeignKey('fk_item_name', self::TABLE, 'item_name', $this->getAuthManager()->itemTable, 'name');
//            $this->addForeignKey('fk_user_id', self::TABLE, 'user_id', \open20\amos\core\user\User::tableName() , 'id');
//            $this->addForeignKey('fk_cwh_nodi_id', self::TABLE, 'cwh_nodi_id', \open20\amos\cwh\models\CwhNodi::tableName() , 'id');
            return true;
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
            return true;
        }
    }

    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $authManager;
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null) {
            $this->dropTable(self::TABLE);
            return true;
        } else {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
            return true;
        }
    }


}
