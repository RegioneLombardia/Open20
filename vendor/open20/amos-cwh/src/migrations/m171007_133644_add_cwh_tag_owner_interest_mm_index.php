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

class m171007_133644_add_cwh_tag_owner_interest_mm_index extends Migration
{
    const TABLE = '{{%cwh_tag_owner_interest_mm}}';

    public function up()
    {
        try{
            $this->createIndex('fk_cwh_tag_owner_interest_mm_interest_classname_idx', self::TABLE, 'interest_classname');
            $this->createIndex('fk_cwh_tag_owner_interest_mm_tag_id_idx', self::TABLE, 'tag_id');
            $this->createIndex('fk_cwh_tag_owner_interest_mm_classname_idx', self::TABLE, 'classname');
            $this->createIndex('fk_cwh_tag_owner_interest_mm_record_id_idx', self::TABLE, 'record_id');
            $this->createIndex('fk_cwh_tag_owner_interest_mm_root_id_idx', self::TABLE, 'root_id');
        }catch(\yii\base\Exception $ex){
            echo $ex->getMessage();
        }
    }

    public function down()
    {
        try{
            $this->dropIndex('fk_cwh_tag_owner_interest_mm_interest_classname_idx', self::TABLE);
            $this->dropIndex('fk_cwh_tag_owner_interest_mm_tag_id_idx', self::TABLE);
            $this->dropIndex('fk_cwh_tag_owner_interest_mm_classname_idx', self::TABLE);
            $this->dropIndex('fk_cwh_tag_owner_interest_mm_record_id_idx', self::TABLE);
            $this->dropIndex('fk_cwh_tag_owner_interest_mm_root_id_idx', self::TABLE);
        }catch(\yii\base\Exception $ex){
            echo $ex->getMessage();
        }

    }
}
