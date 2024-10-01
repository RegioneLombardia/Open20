<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m220531100700_collaboration_expressions_of_interest_permissions*/
class m220531_100701_collaboration_expressions_of_interest_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'COLLABORATIONEXPRESSIONSOFINTEREST_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model CollaborationExpressionsOfInterest',
                'ruleName' => null,
                'parent' => ['COLLABORATIONS_ADMINISTRATOR', 'COLLABORATIONS_STAFF', 'COLLABORATIONS_READER']
            ],
            [
                'name' =>  'COLLABORATIONEXPRESSIONSOFINTEREST_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model CollaborationExpressionsOfInterest',
                'ruleName' => null,
                'parent' => ['COLLABORATIONS_ADMINISTRATOR', 'COLLABORATIONS_STAFF', 'COLLABORATIONS_READER']
            ],
            [
                'name' =>  'COLLABORATIONEXPRESSIONSOFINTEREST_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model CollaborationExpressionsOfInterest',
                'ruleName' => null,
                'parent' => ['COLLABORATIONS_ADMINISTRATOR', 'COLLABORATIONS_STAFF']
            ],
            [
                'name' =>  'COLLABORATIONEXPRESSIONSOFINTEREST_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model CollaborationExpressionsOfInterest',
                'ruleName' => null,
                'parent' => ['COLLABORATIONS_ADMINISTRATOR', 'COLLABORATIONS_STAFF']
            ],
            [
                'name' => \open20\amos\collaborations\rules\UpdateOwnExpressionOfInterest::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE e DELETE sulla propria manifestazione di interesse',
                'ruleName' => \open20\amos\collaborations\rules\UpdateOwnExpressionOfInterest::className(),
                'parent' => ['COLLABORATIONS_READER'],
                'children' => ['COLLABORATIONEXPRESSIONSOFINTEREST_UPDATE', 'COLLABORATIONEXPRESSIONSOFINTEREST_DELETE']
            ],

        ];
    }
}
