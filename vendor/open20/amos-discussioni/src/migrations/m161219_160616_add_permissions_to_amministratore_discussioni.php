<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m161219_160616_add_permissions_to_amministratore_discussioni extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = array_merge(
            $this->setDiscussioniTopicAllegatiModelPermissions(),
            $this->setDiscussioniCommentiModelPermissions(),
            $this->setDiscussioniRisposteModelPermissions()
        );
    }

    /**
     * News attachments model permissions
     *
     * @return array
     */
    private function setDiscussioniTopicAllegatiModelPermissions()
    {
        return [
            [
                'name' => 'DISCUSSIONIALLEGATI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIALLEGATI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIALLEGATI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIALLEGATI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ]
        ];
    }

    /**
     * DiscussioniCommenti model permissions
     *
     * @return array
     */
    private function setDiscussioniCommentiModelPermissions()
    {
        return [
            [
                'name' => 'DISCUSSIONICOMMENTI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model DiscussioniCommenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONICOMMENTI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model DiscussioniCommenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONICOMMENTI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model DiscussioniCommenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONICOMMENTI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model DiscussioniCommenti',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ]
        ];
    }

    /**
     * DiscussioniRisposte model permissions
     *
     * @return array
     */
    private function setDiscussioniRisposteModelPermissions()
    {
        return [
            [
                'name' => 'DISCUSSIONIRISPOSTE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model DiscussioniRisposte',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIRISPOSTE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model DiscussioniRisposte',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIRISPOSTE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model DiscussioniRisposte',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIRISPOSTE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model DiscussioniRisposte',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ]
        ];
    }
}
