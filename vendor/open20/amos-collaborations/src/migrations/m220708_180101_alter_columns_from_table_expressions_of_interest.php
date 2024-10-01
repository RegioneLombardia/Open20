<?php

use yii\db\Migration;

class m220708_180101_alter_columns_from_table_expressions_of_interest extends Migration
{
    const TABLE = '{{%collaboration_expressions_of_interest}}';
    const TABLE_ISTAT_NAZIONI = '{{%istat_nazioni}}';
    const TABLE_ISTAT_PROVINCE = '{{%istat_province}}';
    const TABLE_ISTAT_COMUNI = '{{%istat_comuni}}';


    public function safeUp()
    {
        $this->alterColumn(self::TABLE, 'country', $this->integer()->notNull()->comment('Country'));
        $this->alterColumn(self::TABLE, 'province', $this->integer()->null()->defaultValue(null)->comment('Province'));
        $this->alterColumn(self::TABLE, 'city', $this->integer()->null()->defaultValue(null)->comment('City'));
        $this->alterColumn(self::TABLE, 'municipality', $this->string()->null()->defaultValue(null)->comment('Municipality'));
        $this->alterColumn(self::TABLE, 'address', $this->string()->null()->defaultValue(null)->comment('Address'));
        $this->alterColumn(self::TABLE, 'postal_code', $this->string()->null()->defaultValue(null)->comment('Postal Code'));

        $this->delete(self::TABLE);
        $this->addForeignKey('fk_collaboration_expr_of_int_country_istat', self::TABLE, 'country', self::TABLE_ISTAT_NAZIONI, 'id');
        $this->addForeignKey('fk_collaboration_expr_of_int_province_istat', self::TABLE, 'province', self::TABLE_ISTAT_PROVINCE, 'id');
        $this->addForeignKey('fk_collaboration_expr_of_int_city_istat', self::TABLE, 'city', self::TABLE_ISTAT_COMUNI, 'id');

        return true;
    }

    public function safeDown()
    {
        echo ('Impossibile annullare la migration');
        return true;
    }
}
