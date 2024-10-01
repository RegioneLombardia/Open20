<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */
class m230526_114600_cms_dash_sidebar_item_add_isRolesListActive_rolesListType extends \yii\db\Migration {

    private $table = '{{%cms_dash_sidebar_item}}';

    const COLUMN1 = 'isRolesListActive';
    const COLUMN2 = 'rolesListType';

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $table = $this->db->schema->getTableSchema($this->table);

        if (!(isset($table->columns[self::COLUMN1]))) {
            $this->addColumn($this->table, self::COLUMN1, $this->boolean()->notNull()->after('position')->defaultValue(false)->comment('la lista dei ruoli è attiva o meno'));
        }
        if (!(isset($table->columns[self::COLUMN2]))) {
            $this->addColumn($this->table, self::COLUMN2, $this->boolean()->notNull()->after(self::COLUMN1)->comment('lista da trattare come whitelist o blacklist'));
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn($this->table, self::COLUMN2);
        $this->dropColumn($this->table, self::COLUMN1);
        return true;
    }
}
