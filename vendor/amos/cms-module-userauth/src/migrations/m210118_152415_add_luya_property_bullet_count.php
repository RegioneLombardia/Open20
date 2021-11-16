<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfileArea;
use yii\db\Migration;

/**
 * Class m181012_162615_add_user_profile_area_field_1
 */
class m210118_152415_add_luya_property_bullet_count extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('admin_property', [
            'module_name' => 'userauthfrontend',
            'var_name' => 'bulletCounts',
            'class_name' => 'amos\userauth\frontend\properties\BulletCountsProperty',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('admin_property', [
            'module_name' => 'userauthfrontend',
            'var_name' => 'bulletCounts',
            'class_name' => 'amos\userauth\frontend\properties\BulletCountsProperty',
        ]);
    }
}
