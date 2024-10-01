<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\ProfiloUserMm;
use yii\db\Migration;

/**
 * Class m200908_154901_alter_profilo_user_mm_collation
 */
class m200908_154901_alter_profilo_user_mm_collation extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
        
        $this->db->createCommand("ALTER TABLE `" . ProfiloUserMm::tableName() . "` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE `" . ProfiloUserMm::tableName() . "` MODIFY `status` VARCHAR(255) COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE `" . ProfiloUserMm::tableName() . "` MODIFY `role` VARCHAR(255) COLLATE utf8_unicode_ci")->execute();
        
        $this->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200908_154901_alter_profilo_user_mm_collation cannot be reverted.\n";
        return false;
    }
}
