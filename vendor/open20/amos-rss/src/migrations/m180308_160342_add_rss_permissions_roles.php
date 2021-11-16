<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\rss\migrations
 * @category   CategoryName
 */

use amos\rss\widgets\graphics\WidgetGraphicsRssUltimeNews;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m180308_160342_add_rss_permissions_roles
 */
class m180308_160342_add_rss_permissions_roles extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => WidgetGraphicsRssUltimeNews::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetGraphicsRssUltimeNews',
                'parent' => ['LETTORE_NEWS', 'ADMIN']
            ]
        ];
    }
}
