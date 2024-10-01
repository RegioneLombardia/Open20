<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200924_104110_li_modello_permissions*/
class m201118_175710_podcasts_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'amos\podcast\widgets\icons\WidgetIconPodcastOwn',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'amos\podcast\rules\CreatePodcastRule'
                    ],
                    'removeParents' => [
                        'PODCAST_READER'
                    ]
                ]
            ],



        ];
    }
}
