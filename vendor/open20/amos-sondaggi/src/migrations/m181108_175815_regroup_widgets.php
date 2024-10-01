<?php

/**
 * Aria S.p.A.
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
class m181108_175815_regroup_widgets extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['dashboard_visible' => 0, 'child_of' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral'], ['classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconPubblicaSondaggi']);
        $this->update('amos_widgets', ['dashboard_visible' => 0, 'child_of' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral'], ['classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggi']);
        $this->update('amos_widgets', ['dashboard_visible' => 0, 'child_of' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral'], ['classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi']);



    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update('amos_widgets', ['dashboard_visible' => 1, 'child_of' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral'], ['classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconPubblicaSondaggi']);
        $this->update('amos_widgets', ['dashboard_visible' => 1,'child_of' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral'], ['classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggi']);
        $this->update('amos_widgets', ['dashboard_visible' => 1,'child_of' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral'], ['classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi']);

    }
}
