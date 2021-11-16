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
use yii\rbac\Permission;

/**
 * Class m170623_145301_trans_wid_perm2
 */
class m170623_145301_trans_wid_perm2 extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return $this->setWidgetsPermissions();
    }

    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => \open20\amos\translation\widgets\icons\WidgetIconTrTranslators::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconTrTranslators',
                'parent' => ['TRANSLATE_MANAGER']
            ],
        ];
    }
}
