<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m180801_093949_alter_cwh_config_add_field_visibility
 */
class m180801_093949_alter_cwh_config_add_field_visibility extends Migration
{
    const TABLENAME = '{{%cwh_config}}';

    const TABLENAME_BK = '{{%cwh_config_bk}}';

    const CWH_NODI_VIEW = '{{%cwh_nodi_view}}';

    const COMMUNITY = 'community';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute('CREATE TABLE ' . self::TABLENAME_BK . ' LIKE ' . self::TABLENAME);
        $this->execute('INSERT INTO ' . self::TABLENAME_BK . ' (SELECT * FROM ' . self::TABLENAME . ')');

        $this->addColumn(self::TABLENAME, 'visibility',
            $this->string(255)->defaultValue('1')->comment('Network visibility condition')->after('tablename'));

        //set correct value of contents visibility for community if present
        $tableCommunity = \Yii::$app->db->schema->getTableSchema(self::COMMUNITY);
        if (!is_null($tableCommunity)) {
            if (isset($tableCommunity->columns['contents_visibility'])) {
                $this->update(self::TABLENAME, ['visibility' => '`community`.`contents_visibility`'],
                    ['tablename' => 'community']);
            } else {
                $this->update(self::TABLENAME, ['visibility' => '0'],
                    ['tablename' => 'community']);
            }
        }

        $this->dropColumn(self::TABLENAME, 'raw_sql');

        \open20\amos\cwh\utility\CwhUtil::createCwhView();

        return true;
    }

    /**
     *
     * @inheritdoc
     */
    public function safeDown()
    {

        $this->execute("SET foreign_key_checks = 0;");
        $this->execute("DROP VIEW " . self::CWH_NODI_VIEW);
        $this->dropTable(self::TABLENAME);

        $this->renameTable(self::TABLENAME_BK, self::TABLENAME);
        $this->execute("SET foreign_key_checks = 1;");
        \open20\amos\cwh\utility\CwhUtil::createCwhView();

        return true;
    }
}
