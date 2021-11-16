<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200921_120023_permissions_widget_last_campains*/
class m200921_120023_permissions_widget_last_campains extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => \backend\modules\campains\widgets\graphics\WidgetGraphicsLastCampains::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'permission widget lastcampains',
                'parent' => ['PC_BASIC_USER',]
            ],
        ];
    }
}
