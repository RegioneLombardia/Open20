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
use yii\db\Schema;
use open20\amos\cwh\models\CwhNodi;

class m171011_123326_add_index_refactor_cwh extends Migration
{
    const TABLE = '{{%cwh_tag_owner_interest_mm}}';
    const TABLE2 = '{{%cwh_pubblicazioni_cwh_nodi_validatori_mm}}';
    const TABLE3 = '{{%cwh_pubblicazioni_cwh_nodi_editori_mm}}';
    const TABLE4 = '{{%cwh_nodi_mt}}';

    public function up()
    {
        try{
            $this->alterColumn(self::TABLE,'interest_classname',Schema::TYPE_STRING . "(80)" );
            $this->alterColumn(self::TABLE,'classname',Schema::TYPE_STRING . "(80)" );
            $this->alterColumn(self::TABLE,'record_id',Schema::TYPE_INTEGER);
            $this->createIndex('cwh_tag_owner_interest_mm_deleted_at_idx', self::TABLE, 'deleted_at');

            $this->dropForeignKey('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_nodi3',self::TABLE2);
            $this->dropForeignKey('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_pubblicazioni3',self::TABLE2);
            $this->alterColumn(self::TABLE2,'cwh_pubblicazioni_id',Schema::TYPE_STRING . "(30)" );
            $this->alterColumn(self::TABLE2,'cwh_nodi_id',Schema::TYPE_STRING . "(30)" );
            $this->addPrimaryKey('pk',self::TABLE2,['cwh_pubblicazioni_id','cwh_nodi_id']);

            $this->dropForeignKey('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_nodi2',self::TABLE3);
            $this->dropForeignKey('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_pubblicazioni2',self::TABLE3);
            $this->alterColumn(self::TABLE3,'cwh_pubblicazioni_id',Schema::TYPE_STRING . "(30)" );
            $this->alterColumn(self::TABLE3,'cwh_nodi_id',Schema::TYPE_STRING . "(30)" );
            $this->addPrimaryKey('pk',self::TABLE3,['cwh_pubblicazioni_id','cwh_nodi_id']);


            $this->dropPrimaryKey('PRIMARY', self::TABLE4);
            $this->alterColumn(self::TABLE4,'id',Schema::TYPE_STRING . "(30)" );
            $this->alterColumn(self::TABLE4,'cwh_config_id',Schema::TYPE_INTEGER);
            $this->alterColumn(self::TABLE4,'classname',Schema::TYPE_STRING . "(80)" );
            $this->addPrimaryKey('pk',self::TABLE4,['id', 'cwh_config_id', 'record_id', 'classname']);
            $this->createIndex('cwh_nodi_mt_deleted_at_idx', self::TABLE4, 'deleted_at');

            CwhNodi::mustReset();

        }catch(\yii\base\Exception $ex){
            echo $ex->getMessage();
        }
    }

    public function down()
    {
        try{
            $this->dropIndex('cwh_tag_owner_interest_mm_deleted_at_idx', self::TABLE);
            $this->alterColumn(self::TABLE,'interest_classname',Schema::TYPE_STRING . "(255)" );
            $this->alterColumn(self::TABLE,'classname',Schema::TYPE_STRING . "(255)" );
            $this->alterColumn(self::TABLE,'record_id',Schema::TYPE_STRING . "(255)");

            $this->dropPrimaryKey('pk',self::TABLE2);
            $this->alterColumn(self::TABLE2,'cwh_pubblicazioni_id',Schema::TYPE_STRING . "(255)" );
            $this->alterColumn(self::TABLE2,'cwh_nodi_id',Schema::TYPE_STRING . "(255)" );
            $this->createIndex('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_nodi3',self::TABLE2,['cwh_nodi_id']);
            $this->createIndex('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_pubblicazioni3',self::TABLE2,['cwh_pubblicazioni_id']);


            $this->dropPrimaryKey('pk',self::TABLE3);
            $this->alterColumn(self::TABLE3,'cwh_pubblicazioni_id',Schema::TYPE_STRING . "(255)" );
            $this->alterColumn(self::TABLE3,'cwh_nodi_id',Schema::TYPE_STRING . "(255)" );
            $this->createIndex('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_nodi2',self::TABLE3,['cwh_nodi_id']);
            $this->createIndex('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_pubblicazioni2',self::TABLE3,'cwh_pubblicazioni_id');

            $this->dropPrimaryKey('pk',self::TABLE4);
            $this->dropIndex('cwh_nodi_mt_deleted_at_idx', self::TABLE4);
            $this->alterColumn(self::TABLE4,'id',Schema::TYPE_STRING . "(255)" );
            $this->alterColumn(self::TABLE4,'cwh_config_id',Schema::TYPE_BIGINT ."(20)");
            $this->alterColumn(self::TABLE4,'classname',Schema::TYPE_STRING . "(255)" );
            $this->createPrimaryKey('PRIMARY', self::TABLE4,['id']);

            CwhNodi::mustReset();

        }catch(\yii\base\Exception $ex){
            echo $ex->getMessage();
        }

    }
}
