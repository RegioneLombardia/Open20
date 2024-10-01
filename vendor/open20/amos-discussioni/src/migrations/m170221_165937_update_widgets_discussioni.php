<?php

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m170221_165937_update_widgets_discussioni
 */
class m170221_165937_update_widgets_discussioni extends AmosMigrationWidgets
{
    const MODULE_NAME = 'discussioni';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = array_merge(
            $this->initIconWidgetsConf(),
            $this->initGraphicWidgetsConf()
        );
    }

    /**
     * Init the icon widgets configurations
     * @return array
     */
    private function initIconWidgetsConf()
    {
        return [
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1,
                'update' => true
            ],
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopic::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className(),
                'default_order' => 10,
                'update' => true
            ],
            [
                'classname' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicCreatedBy::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className(),
                'default_order' => 30,
                'update' => true
            ],
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicAll::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className(),
                'default_order' => 40,
                'update' => true
            ],
            [
                'classname' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicDaValidare::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className(),
                'default_order' => 50,
                'update' => true
            ]
        ];
    }

    /**
     * Init the graphic widgets configurations
     * @return array
     */
    private function initGraphicWidgetsConf()
    {
        return [
            [
                'classname' => \open20\amos\discussioni\widgets\graphics\WidgetGraphicsDiscussioniInEvidenza::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className(),
                'default_order' => 1,
                'update' => true
            ],
            [
                'classname' => \open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className(),
                'default_order' => 1,
                'update' => true
            ]
        ];
    }
}
