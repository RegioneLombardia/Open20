<?php
use yii\rbac\Permission;
use yii\helpers\ArrayHelper;
use frontend\rules\PartnerRegistratoRule;
use open20\amos\core\migration\AmosMigrationPermissions;


/**
* Class m230726_161216_update_permissions_collaborations
*/
class m230726_161216_update_permissions_collaborations extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return $this->setModelPermissions();
    }



    /**
     * set Permissions for associate it to roles
     *
     * @return void
     */
    private function setModelPermissions()
    {

        return [

            [
                'name' => 'COLLABORATIONS_STAFF',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'CAPOREDATTORECMS',
                    ]
                ]
            ],

            [
                'name' => 'COLLABORATIONS_ADMINISTRATOR',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'CAPOREDATTORECMS',
                    ]
                ]
            ],

            [
                'name' => 'COLLABORATIONS_READER',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'CAPOREDATTORECMS',
                    ]
                ]
            ]


        ];

    }

}