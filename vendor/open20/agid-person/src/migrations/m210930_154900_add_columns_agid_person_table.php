<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */
use yii\db\Migration;

/**
 * Class m210930_154900_add_columns_agid_person_table
 */
class m210930_154900_add_columns_agid_person_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('agid_person', 'name_surname',
            $this->string()->defaultValue(NULL)->comment("Agid Person Name Surname"));

        \Yii::$app->db->createCommand('UPDATE agid_person set name_surname = CONCAT(UPPER(LEFT(name,1)),LOWER(SUBSTRING(name FROM 2)), " ", UPPER(LEFT(surname,1)),LOWER(SUBSTRING(surname FROM 2)))')->execute();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('agid_person', 'name_surname');
    }
}