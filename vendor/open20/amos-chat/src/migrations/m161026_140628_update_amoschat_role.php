<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

use yii\db\Migration;

class m161026_140628_update_amoschat_role extends Migration
{
    const TABLE_AUTH_ITEM = '{{%auth_item}}';
    const TABLE_AUTH_ITEM_CHILD = '{{%auth_item_child}}';

    /**
     * Use this instead of function up().
     */
    public function safeUp()
    {
        try {
            $this->execute("SET foreign_key_checks = 0;");
            $this->update('tag_models_auth_items_mm', ['auth_item' => 'AMMINISTRATORE_CHAT'] , ['auth_item' => 'AMMINISRATORE_CHAT']);
            $this->update(self::TABLE_AUTH_ITEM, ['name' => 'AMMINISTRATORE_CHAT'] , ['name' => 'AMMINISRATORE_CHAT']);
            $this->update(self::TABLE_AUTH_ITEM_CHILD, ['parent' => 'AMMINISTRATORE_CHAT'] , ['parent' => 'AMMINISRATORE_CHAT']);
            $this->update(self::TABLE_AUTH_ITEM_CHILD, ['child' => 'AMMINISTRATORE_CHAT'] , ['child' => 'AMMINISRATORE_CHAT']);
            $this->execute("SET foreign_key_checks = 1;");
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
            return false;
        }

        return true;
    }

    /**
     * Use this instead of function down().
     */
    public function safeDown()
    {
        echo "Down non esegue nessuna operazione.";
        return true;
    }
}
