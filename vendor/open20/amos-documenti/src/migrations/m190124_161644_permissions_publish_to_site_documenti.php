<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190124_161644_permissions_publish_to_site_documenti
 */
class m190124_161644_permissions_publish_to_site_documenti extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DOCUMENTI_PUBLISHER_FRONTEND',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to publish in frontend',
            ],
        ];
    }
}
