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
 * Class m190103_122315_add_column_enable_facilitator_box
 */
class m191128_093215_insert_models_classname extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('models_classname',[
            'classname' => \open20\amos\admin\models\UserProfile::className(),
            'module' => 'admin',
            'label' => 'User Profile',
            ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('models_classname',[
            'classname' => \open20\amos\admin\models\UserProfile::className(),
            'module' => 'admin',
            'label' => 'User Profile',
        ]);
    }
}
