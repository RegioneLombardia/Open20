<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\rss\migrations
 * @category   CategoryName
 */

use amos\rss\widgets\graphics\WidgetGraphicsRssNewsCategory;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m181211_102253_add_auth_item_rss_news_category
 */
class m181211_102253_add_auth_item_rss_news_category extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => WidgetGraphicsRssNewsCategory::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetGraphicsRssNewsCategory',
                'parent' => ['LETTORE_NEWS', 'ADMIN']
            ]
        ];
    }
}
