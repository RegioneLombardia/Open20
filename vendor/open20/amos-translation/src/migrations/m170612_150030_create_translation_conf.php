<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation\migrations
 * @category   CategoryName
 */

/**
 * Class m170612_150030_create_translation_conf
 */
class m170612_150030_create_translation_conf extends \yii\db\Migration
{
    const TABLE = '{{%translation_conf}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableName = $this->db->getSchema()->getRawTableName(self::TABLE);

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            try {
                $this->createTable(self::TABLE, [
                    'namespace' => $this->string()->notNull(),
                    'plugin' => $this->string()->null(),
                    'model_generated' => $this->integer()->notNull()->defaultValue(0),
                    'fields' => $this->string()->null(),
                    'created_at' => $this->dateTime()->null(),
                    'updated_at' => $this->dateTime()->null(),
                    'deleted_at' => $this->dateTime()->null(),
                    'created_by' => $this->integer()->null(),
                    'updated_by' => $this->integer()->null(),
                    'deleted_by' => $this->integer()->null(),
                ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);
                $this->addPrimaryKey('pk_' . $tableName . 'tr1', self::TABLE, ['namespace']);
            } catch (Exception $e) {
                echo "Errore durante la creazione della tabella " . $tableName . "\n";
                echo $e->getMessage() . "\n";
                return false;
            }
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella " . $tableName . " esiste gia'\n";
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        try {
            $this->dropTable(self::TABLE);
        } catch (Exception $e) {
            echo "Errore durante la cancellazionedella tabella " . self::TABLE . "\n";
            echo $e->getMessage() . "\n";
            return false;
        }

        return true;
    }
}
