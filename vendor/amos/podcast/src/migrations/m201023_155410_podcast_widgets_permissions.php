<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200924_104110_li_modello_permissions*/
class m201023_155410_podcast_widgets_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => \amos\podcast\widgets\icons\WidgetIconDashboardPodcast::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for widget Podcast',
                'parent' => ['PODCAST_READER', 'ADMINISTRATOR_PODCAST']
            ],
            [
                'name' => \amos\podcast\widgets\icons\WidgetIconPodcastOwn::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for widget Podcast',
                'parent' => ['PODCAST_READER', 'ADMINISTRATOR_PODCAST']
            ],
            [
                'name' => \amos\podcast\widgets\icons\WidgetIconPodcastPublished::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for widget Podcast',
                'parent' => ['PODCAST_READER', 'ADMINISTRATOR_PODCAST']
            ],
            [
                'name' => \amos\podcast\widgets\icons\WidgetIconPodcastAdmin::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for widget Podcast',
                'parent' => [ 'ADMINISTRATOR_PODCAST']
            ],
            [
                'name' => \amos\podcast\widgets\icons\WidgetIconPodcastCategory::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for widget Podcast',
                'parent' => [ 'ADMINISTRATOR_PODCAST']
            ],
            [
                'name' => \amos\podcast\widgets\icons\WidgetIconPodcastToValidate::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for widget Podcast',
                'parent' => [ 'PODCAST_VALIDATOR' ,'ADMINISTRATOR_PODCAST']
            ],


        ];
    }
}
