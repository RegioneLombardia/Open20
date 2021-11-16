<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\notificationmanager\models\Newsletter;
use yii\rbac\Permission;

/**
 * Class m200508_163240_newsletter_workflow_permissions
 */
class m200508_163240_newsletter_workflow_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => Newsletter::WORKFLOW_STATUS_DRAFT,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso stato workflow Newsletter: Bozza',
                'parent' => ['NEWSLETTER_ADMINISTRATOR', 'NEWSLETTER_MANAGER']
            ],
            [
                'name' => Newsletter::WORKFLOW_STATUS_WAIT_SEND,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso stato workflow Newsletter: In attesa di invio',
                'parent' => ['NEWSLETTER_ADMINISTRATOR', 'NEWSLETTER_MANAGER']
            ],
            [
                'name' => Newsletter::WORKFLOW_STATUS_WAIT_RESEND,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso stato workflow Newsletter: In attesa di reinvio',
                'parent' => ['NEWSLETTER_ADMINISTRATOR', 'NEWSLETTER_MANAGER']
            ],
            [
                'name' => Newsletter::WORKFLOW_STATUS_SENT,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso stato workflow Newsletter: Inviata',
                'parent' => ['NEWSLETTER_ADMINISTRATOR', 'NEWSLETTER_MANAGER']
            ]
        ];
    }
}
