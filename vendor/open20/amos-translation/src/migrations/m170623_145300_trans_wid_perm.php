<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m170623_145300_trans_wid_perm
 */
class m170623_145300_trans_wid_perm extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(), $this->setModelPermissions(), $this->setWidgetsPermissions()
        );
    }

    private function setPluginRoles()
    {
        return [
            [
                'name' => 'TRANSLATE_MANAGER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for translation plugin',
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'CONTENT_TRANSLATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Content translator role for Translation plugin',
                'parent' => ['ADMIN']
            ]
        ];
    }

    private function setModelPermissions()
    {
        return [
            [
                'name' => 'TRANSLATOR',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to translate',
                'parent' => ['TRANSLATE_MANAGER'],
                'dontRemove' => true,
            ],
            [
                'name' => 'TRANSLATION_ADMINISTRATOR',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to translate',
                'parent' => ['TRANSLATE_MANAGER'],
                'dontRemove' => true,
            ],
        ];
    }

    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => \open20\amos\translation\widgets\icons\WidgetIconTranslation::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconTranslation',
                'parent' => ['TRANSLATE_MANAGER', 'CONTENT_TRANSLATOR']
            ],
            [
                'name' => \open20\amos\translation\widgets\icons\WidgetIconTrContents::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconTrContents',
                'parent' => ['TRANSLATE_MANAGER', 'CONTENT_TRANSLATOR']
            ],
            [
                'name' => \open20\amos\translation\widgets\icons\WidgetIconTrPlatform::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconTrPlatform',
                'parent' => ['TRANSLATE_MANAGER', 'CONTENT_TRANSLATOR']
            ],
            [
                'name' => \open20\amos\translation\widgets\icons\WidgetIconTrLanguage::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconTrLanguage',
                'parent' => ['TRANSLATE_MANAGER']
            ],
            [
                'name' => \open20\amos\translation\widgets\icons\WidgetIconTrOptimize::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconTrOptimize',
                'parent' => ['TRANSLATE_MANAGER']
            ],
            [
                'name' => \open20\amos\translation\widgets\icons\WidgetIconTrScan::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconTrScan',
                'parent' => ['TRANSLATE_MANAGER']
            ],
        ];
    }
}
