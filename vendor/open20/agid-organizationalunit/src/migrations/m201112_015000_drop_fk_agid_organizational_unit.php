<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use yii\db\Migration;


class m201112_015000_drop_fk_agid_organizational_unit extends Migration
{

    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('agid_organizational_unit');

        if (isset($table->columns['user_profile_president_id'])) {
            
            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");

            // dropForeignKey
            $this->dropForeignKey ( 'fk-user-profile-president-id', 'agid_organizational_unit' );
            // dropColumn
            $this->dropColumn('agid_organizational_unit', 'user_profile_president_id');

            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        }


        if (isset($table->columns['user_profile_vice_president_id'])) {

            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");

            // dropForeignKey
            $this->dropForeignKey ( 'fk-user-profile-vice-president-id', 'agid_organizational_unit' );
            // dropColumn
            $this->dropColumn('agid_organizational_unit', 'user_profile_vice_president_id');

            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        }

    }

    public function down()
    {
        return true;
    }

}