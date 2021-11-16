<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m190712_095527_permission_published_sondaggi extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for publication';

        return [
            [
                'name' =>  \open20\amos\sondaggi\rules\SondaggiWorkflowPublishedRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'permission publish sondaggi',
                'ruleName' => \open20\amos\sondaggi\rules\SondaggiWorkflowPublishedRule::className(),
                'parent' => ['AMMINISTRAZIONE_SONDAGGI','SondaggiValidate'],
                'children' => ['SondaggiWorkflow/VALIDATO']
           ],

            [
            'name' =>  'SondaggiWorkflow/VALIDATO',
            'update' => true,
            'newValues' => [
                'removeParents' => ['AMMINISTRAZIONE_SONDAGGI','SondaggiValidate']
                ],
            ]
        ];
    }
}
