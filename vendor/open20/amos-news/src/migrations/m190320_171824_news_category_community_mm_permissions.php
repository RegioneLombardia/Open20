<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m190320_171824_news_category_community_mm_permissions*/
class m190320_171824_news_category_community_mm_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'NEWSCATEGORYCOMMUNITYMM_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model NewsCategoryCommunityMm',
                    'ruleName' => null,
                    'parent' => ['AMMINISTRATORE_CATEGORIE_NEWS']
                ],
                [
                    'name' =>  'NEWSCATEGORYCOMMUNITYMM_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model NewsCategoryCommunityMm',
                    'ruleName' => null,
                    'parent' => ['AMMINISTRATORE_CATEGORIE_NEWS']
                    ],
                [
                    'name' =>  'NEWSCATEGORYCOMMUNITYMM_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model NewsCategoryCommunityMm',
                    'ruleName' => null,
                    'parent' => ['AMMINISTRATORE_CATEGORIE_NEWS']
                ],
                [
                    'name' =>  'NEWSCATEGORYCOMMUNITYMM_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model NewsCategoryCommunityMm',
                    'ruleName' => null,
                    'parent' => ['AMMINISTRATORE_CATEGORIE_NEWS']
                ],

            ];
    }
}
