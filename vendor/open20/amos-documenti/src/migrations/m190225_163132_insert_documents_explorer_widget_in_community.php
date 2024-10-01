<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m190225_163132_insert_documents_explorer_widget_in_community
 */
class m190225_163132_insert_documents_explorer_widget_in_community extends \open20\amos\core\migration\AmosMigration
{
    const MODULE_NAME = 'documenti';
    const COMMUNITY_MODULE_NAME = 'community';

    public function safeUp()
    {
        $communityModule = \Yii::$app->getModule('community');
        if(isset($communityModule)) {
            $this->insert('amos_widgets', [
                'classname' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsExplorer::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::COMMUNITY_MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1,
                'sub_dashboard' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => date('Y-m-d H:m:s'),
            ]);
        }

        return true;
    }

    public function safeDown()
    {
        $communityModule = \Yii::$app->getModule('community');
        if(isset($communityModule)) {
            $this->delete('amos_widgets', [
                'classname' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsExplorer::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::COMMUNITY_MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1,
                'sub_dashboard' => 1,
            ]);
        }
        return true;
    }

}
