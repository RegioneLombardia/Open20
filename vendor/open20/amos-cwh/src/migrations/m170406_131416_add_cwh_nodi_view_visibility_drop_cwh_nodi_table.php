<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */
use open20\amos\cwh\models\CwhConfig;
use yii\db\Migration;

/**
 * Class m170406_131416_add_cwh_nodi_view_visibility_drop_cwh_nodi_table
 */
class m170406_131416_add_cwh_nodi_view_visibility_drop_cwh_nodi_table extends Migration
{
    const TABLE = '{{%cwh_nodi}}';

    public function safeUp()
    {

        if(Yii::$app->db->schema->getTableSchema(self::TABLE) !== null) { // IF cwh_nodi exists
            $this->execute("SET FOREIGN_KEY_CHECKS=0;");
            $this->dropTable(self::TABLE);
            $this->execute("SET FOREIGN_KEY_CHECKS=1;");
        }

        $listaConf = CwhConfig::find()->asArray()->all();

        $sqlSelect = '( ';
        $numeroConf = count($listaConf);
        $i = 1;
        foreach ($listaConf as $conf){
            $sqlSelect .= $conf['raw_sql'];
            if ($i < $numeroConf) {
                $sqlSelect .= ' ) UNION ( ';
            }
            $i++;
        }
        $sqlSelect .= ' );';

        $sql = 'CREATE OR REPLACE VIEW cwh_nodi_view AS ' . $sqlSelect;

        $commandSql = Yii::$app->getDb()->createCommand($sql);
        $commandSql->execute();

        return true;
    }

    public function safeDown()
    {

        return true;
    }

}
