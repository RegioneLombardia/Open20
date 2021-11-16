<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\dashboard\models\AmosWidgets;

class m160912_144916_create_discussioni_widgets extends \open20\amos\core\migration\AmosMigrationWidgets
{
    const MODULE_NAME = 'discussioni';

    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'update' => true,
                'status' => AmosWidgets::STATUS_ENABLED
            ],
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicAll::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'update' => true,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className()
            ],
            [
                'classname' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicCreatedBy::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'update' => true,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className()
            ],
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopic::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'update' => true,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className()
            ],
            [
                'classname' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicDaValidare::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'update' => true,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className()
            ],
            [
                'classname' => \open20\amos\discussioni\widgets\graphics\WidgetGraphicsDiscussioniInEvidenza::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'update' => true,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className()
            ],
            [
                'classname' => \open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'update' => true,
                'child_of' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className()
            ]
        ];
    }
}
