<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200924_104110_li_modello_permissions*/
class m201118_174710_podcast_validations_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'PodcastWorkflow/PUBLISHED',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        amos\podcast\rules\ValidatorPodcastRule::className()
                    ]
                ]
            ],

            [
                'name' =>  'PodcastWorkflow/REJECTED',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        amos\podcast\rules\ValidatorPodcastRule::className(),
                    ]
                ]
            ],



        ];
    }
}
