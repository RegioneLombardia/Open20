<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230112_132647_permissions
 */
class m230112_132647_permissions extends AmosMigrationPermissions {

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations() {
        $prefixStr = '';

        return [
            [
                'name' => 'CAPOREDATTORECMS',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo di Capo Redattore',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'REDATTORECMS',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo di Redattore',
                'ruleName' => null,
                'parent' => ['ADMIN', 'CAPOREDATTORECMS']
            ],
            [
                'name' => 'CMS_PUBLISH_PAGE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di pubblicare le pagine del CMS',
                'ruleName' => null,
                'parent' => ['ADMIN', 'CAPOREDATTORECMS']
            ],
            [
                'name' => 'CMSWFREQUEST_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model CmsWfRequest',
                'ruleName' => null,
                'parent' => ['CAPOREDATTORECMS', 'REDATTORECMS']
            ],
            [
                'name' => 'CMSWFREQUEST_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model CmsWfRequest',
                'ruleName' => null,
                'parent' => ['CAPOREDATTORECMS', 'REDATTORECMS']
            ],
            [
                'name' => 'CMSWFREQUEST_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model CmsWfRequest',
                'ruleName' => null,
                'parent' => ['CAPOREDATTORECMS']
            ],
            [
                'name' => 'CMSWFREQUEST_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model CmsWfRequest',
                'ruleName' => null,
                'parent' => ['CAPOREDATTORECMS']
            ],
        ];
    }

}
