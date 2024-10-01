<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201012_150336_podcast_permissions*/
class m201016_101536_podcast_workflow_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [

            [
                'name' => \amos\podcast\models\Podcast::PODCAST_WORKFLOW_STATUS_PUBLISHED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow Permission',
                'parent' => ['ADMINISTRATOR_PODCAST', 'PODCAST_VALIDATOR']
            ],
            [
                'name' => \amos\podcast\models\Podcast::PODCAST_WORKFLOW_STATUS_REJECTED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow Permission',
                'parent' =>  ['ADMINISTRATOR_PODCAST', 'PODCAST_VALIDATOR']
            ],
            [
                'name' =>\amos\podcast\models\Podcast::PODCAST_WORKFLOW_STATUS_REQUESTPUBLICATION,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow Permission',
                'parent' => ['PODCAST_VALIDATOR', 'ADMIN_TERRITORY', 'PODCAST_READER']
            ],

            // ---------------
            [
                'name' => \amos\podcast\models\PodcastEpisode::PODCASTEP_WORKFLOW_STATUS_PUBLISHED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow Permission',
                'parent' => ['ADMINISTRATOR_PODCAST', 'PODCAST_VALIDATOR']
            ],
            [
                'name' => \amos\podcast\models\PodcastEpisode::PODCASTEP_WORKFLOW_STATUS_REJECTED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow Permission',
                'parent' =>  ['ADMINISTRATOR_PODCAST', 'PODCAST_VALIDATOR']
            ],
            [
                'name' => \amos\podcast\models\PodcastEpisode::PODCASTEP_WORKFLOW_STATUS_REQUESTPUBLICATION,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow Permission',
                'parent' => ['PODCAST_VALIDATOR', 'ADMIN_TERRITORY', 'PODCAST_READER']
            ],


        ];
    }
}
