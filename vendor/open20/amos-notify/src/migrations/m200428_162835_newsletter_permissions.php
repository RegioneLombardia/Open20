<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\notificationmanager\rules\DeleteOwnNewsletterRule;
use open20\amos\notificationmanager\rules\UpdateOwnNewsletterRule;
use open20\amos\notificationmanager\widgets\icons\WidgetIconNewsletterAll;
use open20\amos\notificationmanager\widgets\icons\WidgetIconNewsletterCreatedBy;
use open20\amos\notificationmanager\widgets\icons\WidgetIconNewsletterDashboard;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m200428_162835_newsletter_permissions
 */
class m200428_162835_newsletter_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setModelPermissions(),
            $this->setWidgetsPermissions()
        );
    }
    
    private function setPluginRoles()
    {
        return [
            [
                'name' => 'NOTIFY_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for notify plugin',
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSLETTER_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for newsletter',
                'parent' => ['NOTIFY_ADMINISTRATOR']
            ],
            [
                'name' => 'NEWSLETTER_MANAGER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Manager role for newsletter',
                'parent' => ['NEWSLETTER_ADMINISTRATOR']
            ]
        ];
    }
    
    private function setModelPermissions()
    {
        return [
            [
                'name' => 'NEWSLETTER_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model Newsletter',
                'parent' => ['NEWSLETTER_ADMINISTRATOR', 'NEWSLETTER_MANAGER']
            ],
            [
                'name' => 'NEWSLETTER_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model Newsletter',
                'parent' => ['NEWSLETTER_ADMINISTRATOR', 'NEWSLETTER_MANAGER']
            ],
            [
                'name' => 'NEWSLETTER_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model Newsletter',
                'parent' => ['NEWSLETTER_ADMINISTRATOR', UpdateOwnNewsletterRule::className()]
            ],
            [
                'name' => 'NEWSLETTER_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model Newsletter',
                'parent' => ['NEWSLETTER_ADMINISTRATOR', DeleteOwnNewsletterRule::className()]
            ],
            [
                'name' => UpdateOwnNewsletterRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Rule to update own newsletters',
                'ruleName' => UpdateOwnNewsletterRule::className(),
                'parent' => ['NEWSLETTER_MANAGER']
            ],
            [
                'name' => DeleteOwnNewsletterRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Rule to delete own newsletters',
                'ruleName' => DeleteOwnNewsletterRule::className(),
                'parent' => ['NEWSLETTER_MANAGER']
            ],
        ];
    }
    
    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => WidgetIconNewsletterDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconNewsletterDashboard',
                'parent' => ['NEWSLETTER_ADMINISTRATOR', 'NEWSLETTER_MANAGER']
            ],
            [
                'name' => WidgetIconNewsletterAll::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconNewsletterAll',
                'parent' => ['NEWSLETTER_ADMINISTRATOR', 'NEWSLETTER_MANAGER']
            ],
            [
                'name' => WidgetIconNewsletterCreatedBy::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconNewsletterCreatedBy',
                'parent' => ['NEWSLETTER_ADMINISTRATOR', 'NEWSLETTER_MANAGER']
            ],
        ];
    }
}
