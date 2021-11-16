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
class m181113_122815_remove_widgets_report extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['dashboard_visible' => 0], ['classname' => 'open20\amos\community\widgets\graphics\WidgetGraphicsCommunityReports']);


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update('amos_widgets', ['dashboard_visible' => 1], ['classname' => 'open20\amos\community\widgets\graphics\WidgetGraphicsCommunityReports']);

    }
}
