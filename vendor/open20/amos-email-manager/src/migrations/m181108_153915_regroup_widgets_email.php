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
class m181108_153915_regroup_widgets_email extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['child_of' => 'open20\amos\dashboard\widgets\icons\WidgetIconManagement'], ['classname' => 'open20\amos\emailmanager\widgets\icons\WidgetIconEmailManager']);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update('amos_widgets', ['child_of' => null], ['classname' => 'open20\amos\emailmanager\widgets\icons\WidgetIconEmailManager']);

    }
}
