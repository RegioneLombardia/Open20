<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201012_150336_podcast_permissions*/
class m201012_150336_podcast_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'ADMINISTRATOR_PODCAST',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator podcast',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],

            [
                'name' =>  'PODCAST_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Reader podcast',
                'ruleName' => null,
                'parent' => ['BASIC_USER']
            ],
            [
                'name' =>  'PODCAST_VALIDATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Reader podcast',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_PODCAST']
            ],

            //------------
                [
                    'name' =>  'PODCAST_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model Podcast',
                    'ruleName' => null,
                    'parent' => ['ADMINISTRATOR_PODCAST']
                ],
                [
                    'name' =>  'PODCAST_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model Podcast',
                    'ruleName' => null,
                    'parent' => ['ADMINISTRATOR_PODCAST']
                    ],
                [
                    'name' =>  'PODCAST_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model Podcast',
                    'ruleName' => null,
                    'parent' => ['ADMINISTRATOR_PODCAST']
                ],
                [
                    'name' =>  'PODCAST_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model Podcast',
                    'ruleName' => null,
                    'parent' => ['ADMINISTRATOR_PODCAST']
                ],
            //
            [
                'name' =>  'PODCASTCATEGORY_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model PodcastCategory',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_PODCAST']
            ],
            [
                'name' =>  'PODCASTCATEGORY_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model PodcastCategory',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_PODCAST']
            ],
            [
                'name' =>  'PODCASTCATEGORY_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model PodcastCategory',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_PODCAST']
            ],
            [
                'name' =>  'PODCASTCATEGORY_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model PodcastCategory',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_PODCAST']
            ],

            // ------------------------

            [
                'name' =>  'PODCASTEPISODE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model PodcastEpisode',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_PODCAST']
            ],
            [
                'name' =>  'PODCASTEPISODE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model PodcastEpisode',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_PODCAST']
            ],
            [
                'name' =>  'PODCASTEPISODE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model PodcastEpisode',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_PODCAST']
            ],
            [
                'name' =>  'PODCASTEPISODE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model PodcastEpisode',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_PODCAST']
            ],


        ];
    }
}
