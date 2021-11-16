<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m200910_113646_preference_landing_protagonist_permissions*/
class m200910_123646_preference_landing_contennts_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'PREFERENCELANDINGPROTAGONIST_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model PreferenceLandingProtagonist',
                    'ruleName' => null,
                    'parent' => ['PC_LANDING_ADMINISTRATOR']
                ],
                [
                    'name' =>  'PREFERENCELANDINGPROTAGONIST_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model PreferenceLandingProtagonist',
                    'ruleName' => null,
                    'parent' => ['PC_LANDING_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'PREFERENCELANDINGPROTAGONIST_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model PreferenceLandingProtagonist',
                    'ruleName' => null,
                    'parent' => ['PC_LANDING_ADMINISTRATOR']
                ],
                [
                    'name' =>  'PREFERENCELANDINGPROTAGONIST_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model PreferenceLandingProtagonist',
                    'ruleName' => null,
                    'parent' => ['PC_LANDING_ADMINISTRATOR']
                ],
            //--------------
            [
                'name' =>  'PREFERENCELANDINGNEWS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model PreferenceLandingNews',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PREFERENCELANDINGNEWS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model PreferenceLandingNews',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PREFERENCELANDINGNEWS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model PreferenceLandingNews',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PREFERENCELANDINGNEWS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model PreferenceLandingNews',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],


        ];
    }
}
