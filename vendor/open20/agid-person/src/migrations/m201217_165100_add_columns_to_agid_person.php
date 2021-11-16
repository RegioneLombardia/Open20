<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\person\migrations
 * @category   CategoryName
 */

use open20\agid\person\models\AgidPerson;
use yii\db\Migration;

/**
 * Class m201217_165100_add_columns_to_agid_person
 */
class m201217_165100_add_columns_to_agid_person extends Migration
{
    private $tableName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = AgidPerson::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        // fk columns
        $this->addColumn( $this->tableName, 'agid_organizational_unit_1_id', $this->integer()->null()->defaultValue(null)->after('status') );
        $this->addColumn( $this->tableName, 'agid_organizational_unit_2_id', $this->integer()->null()->defaultValue(null)->after('status') );
        $this->addColumn( $this->tableName, 'agid_organizational_unit_3_id', $this->integer()->null()->defaultValue(null)->after('status') );
        $this->addColumn( $this->tableName, 'agid_organizational_unit_4_id', $this->integer()->null()->defaultValue(null)->after('status') );
        $this->addColumn( $this->tableName, 'agid_organizational_unit_5_id', $this->integer()->null()->defaultValue(null)->after('status') );
    

        // columns
        $this->addColumn( $this->tableName, 'person_function_1', $this->text()->null()->defaultValue(null)->comment('Person function 1')->after('status') );
        $this->addColumn( $this->tableName, 'person_function_2', $this->text()->null()->defaultValue(null)->comment('Person function 2')->after('status') );
        $this->addColumn( $this->tableName, 'person_function_3', $this->text()->null()->defaultValue(null)->comment('Person function 3')->after('status') );
        $this->addColumn( $this->tableName, 'person_function_4', $this->text()->null()->defaultValue(null)->comment('Person function 4')->after('status') );
        $this->addColumn( $this->tableName, 'person_function_5', $this->text()->null()->defaultValue(null)->comment('Person function 5')->after('status') );


        // addForeignKey
        $this->addForeignKey(
            'fk-agid-organizational-unit-1-id',
            'agid_person',
            'agid_organizational_unit_1_id',
            'agid_organizational_unit',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-agid-organizational-unit-2-id',
            'agid_person',
            'agid_organizational_unit_2_id',
            'agid_organizational_unit',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-agid-organizational-unit-3-id',
            'agid_person',
            'agid_organizational_unit_3_id',
            'agid_organizational_unit',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-agid-organizational-unit-4-id',
            'agid_person',
            'agid_organizational_unit_4_id',
            'agid_organizational_unit',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-agid-organizational-unit-5-id',
            'agid_person',
            'agid_organizational_unit_5_id',
            'agid_organizational_unit',
            'id',
            'SET NULL'
        );
        
    }
    

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

        // dropColumn
        $this->dropColumn($this->tableName, 'person_function_1');
        $this->dropColumn($this->tableName, 'person_function_2');
        $this->dropColumn($this->tableName, 'person_function_3');
        $this->dropColumn($this->tableName, 'person_function_4');
        $this->dropColumn($this->tableName, 'person_function_5');

        // dropForeignKey
        $this->dropForeignKey('fk-agid-organizational-unit-1-id', $this->tableName);
        // dropColumn
        $this->dropColumn($this->tableName, 'agid_organizational_unit_1_id');

        $this->dropForeignKey('fk-agid-organizational-unit-2-id', $this->tableName);
        $this->dropColumn($this->tableName, 'agid_organizational_unit_2_id');

        $this->dropForeignKey('fk-agid-organizational-unit-3-id', $this->tableName);
        $this->dropColumn($this->tableName, 'agid_organizational_unit_3_id');

        $this->dropForeignKey('fk-agid-organizational-unit-4-id', $this->tableName);
        $this->dropColumn($this->tableName, 'agid_organizational_unit_4_id');

        $this->dropForeignKey('fk-agid-organizational-unit-5-id', $this->tableName);
        $this->dropColumn($this->tableName, 'agid_organizational_unit_5_id');
    }
}
