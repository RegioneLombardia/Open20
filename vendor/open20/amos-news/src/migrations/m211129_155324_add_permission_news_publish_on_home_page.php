<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

use yii\rbac\Permission;

class m211129_155324_add_permission_news_publish_on_home_page extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NewsPublishOnHomePage',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to publish on home page a news',
                'ruleName' => \open20\amos\news\rules\PublishOnHomePageNewsRule::class,
                'parent' => ['VALIDATORE_NEWS']
            ],
            [
                'name' => 'NEWS_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['NewsPublishOnHomePage'],
                    'removeParents' => ['VALIDATORE_NEWS']
                ]
            ]
        ];
    }
}
