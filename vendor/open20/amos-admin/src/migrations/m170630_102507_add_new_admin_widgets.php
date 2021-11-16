<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m170630_102507_add_new_admin_widgets
 */
class m170630_102507_add_new_admin_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'admin';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1,
                'update' => true,
                'dontRemove' => true
            ],
            [
                'classname' => \open20\amos\admin\widgets\icons\WidgetIconMyProfile::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1,
                'update' => true,
                'dontRemove' => true
            ],
            [
                'classname' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1
            ],
            [
                'classname' => \open20\amos\admin\widgets\icons\WidgetIconValidatedUserProfiles::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                'default_order' => 10
            ],
            [
                'classname' => \open20\amos\admin\widgets\icons\WidgetIconUserProfile::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                'default_order' => 20,
                'update' => true,
                'dontRemove' => true
            ],
            [
                'classname' => \open20\amos\admin\widgets\icons\WidgetIconCommunityManagerUserProfiles::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                'default_order' => 30
            ],
            [
                'classname' => \open20\amos\admin\widgets\icons\WidgetIconFacilitatorUserProfiles::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                'default_order' => 40
            ],
            [
                'classname' => \open20\amos\admin\widgets\icons\WidgetIconInactiveUserProfiles::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                'default_order' => 50
            ]
        ];
    }
}
