<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


class m220531_101101_collaboration_proposals_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'COLLABORATIONPROPOSALS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model CollaborationProposals',
                'ruleName' => null,
                'parent' => ['COLLABORATIONS_ADMINISTRATOR', 'COLLABORATIONS_STAFF']
            ],
            [
                'name' =>  'COLLABORATIONPROPOSALS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model CollaborationProposals',
                'ruleName' => null,
                'parent' => ['COLLABORATIONS_ADMINISTRATOR', 'COLLABORATIONS_STAFF', 'COLLABORATIONS_READER']
            ],
            [
                'name' =>  'COLLABORATIONPROPOSALS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model CollaborationProposals',
                'ruleName' => null,
                'parent' => ['COLLABORATIONS_ADMINISTRATOR', 'COLLABORATIONS_STAFF']
            ],
            [
                'name' =>  'COLLABORATIONPROPOSALS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model CollaborationProposals',
                'ruleName' => null,
                'parent' => ['COLLABORATIONS_ADMINISTRATOR', 'COLLABORATIONS_STAFF']
            ],
            [
                'name' => \open20\amos\collaborations\rules\UpdateOwnCollaborationProposal::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE e DELETE sulla propria proposta di collaborazione',
                'ruleName' => \open20\amos\collaborations\rules\UpdateOwnCollaborationProposal::className(),
                'parent' => ['COLLABORATIONS_READER'],
                'children' => ['COLLABORATIONPROPOSALS_UPDATE', 'COLLABORATIONPROPOSALS_DELETE']
            ],

        ];
    }
}
