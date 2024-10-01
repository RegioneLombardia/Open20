<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m220216_145403_partnership_profiles_category_permissions*/
class m220216_145403_partnership_profiles_category_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [

                [
                    'name' =>  'PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR',
                    'type' => Permission::TYPE_ROLE,
                    'description' => 'Ruolo per creare le categorie delle proposte',
                    'ruleName' => null,
                    'parent' => ['PARTNERSHIP_PROFILES_PLUGIN_ADMINISTRATOR']
                ],
            [
                    'name' =>  'PARTNERSHIPPROFILESCATEGORY_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model PartnershipProfilesCategory',
                    'ruleName' => null,
                    'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
                ],
                [
                    'name' =>  'PARTNERSHIPPROFILESCATEGORY_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model PartnershipProfilesCategory',
                    'ruleName' => null,
                    'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'PARTNERSHIPPROFILESCATEGORY_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model PartnershipProfilesCategory',
                    'ruleName' => null,
                    'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
                ],
                [
                    'name' =>  'PARTNERSHIPPROFILESCATEGORY_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model PartnershipProfilesCategory',
                    'ruleName' => null,
                    'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
                ],

            [
                'name' =>  'PARTNERSHIPPROFILESCATEGORYMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model PartnershipProfilesCategoryMm',
                'ruleName' => null,
                'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
            ],
            [
                'name' =>  'PARTNERSHIPPROFILESCATEGORYMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model PartnershipProfilesCategoryMm',
                'ruleName' => null,
                'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
            ],
            [
                'name' =>  'PARTNERSHIPPROFILESCATEGORYMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model PartnershipProfilesCategoryMm',
                'ruleName' => null,
                'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
            ],
            [
                'name' =>  'PARTNERSHIPPROFILESCATEGORYMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model PartnershipProfilesCategoryMm',
                'ruleName' => null,
                'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
            ],

            [
                'name' =>  'PARTNERSHIPPROFILESCATEGORYROLES_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model PartnershipProfilesCategoryRoles',
                'ruleName' => null,
                'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
            ],
            [
                'name' =>  'PARTNERSHIPPROFILESCATEGORYROLES_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model PartnershipProfilesCategoryRoles',
                'ruleName' => null,
                'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
            ],
            [
                'name' =>  'PARTNERSHIPPROFILESCATEGORYROLES_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model PartnershipProfilesCategoryRoles',
                'ruleName' => null,
                'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
            ],
            [
                'name' =>  'PARTNERSHIPPROFILESCATEGORYROLES_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model PartnershipProfilesCategoryRoles',
                'ruleName' => null,
                'parent' => ['PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR']
            ],



        ];
    }
}
