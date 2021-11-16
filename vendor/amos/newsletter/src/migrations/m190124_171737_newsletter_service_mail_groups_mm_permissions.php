<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m190124_171737_newsletter_service_mail_groups_mm_permissions*/
class m190124_171737_newsletter_service_mail_groups_mm_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'NEWSLETTERSERVICEMAILGROUPSMM_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model NewsletterServiceMailGroupsMm',
                    'ruleName' => null,
                    'parent' => ['NEWSLETTERT_ADMINISTRATOR']
                ],
                [
                    'name' =>  'NEWSLETTERSERVICEMAILGROUPSMM_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model NewsletterServiceMailGroupsMm',
                    'ruleName' => null,
                    'parent' => ['NEWSLETTERT_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'NEWSLETTERSERVICEMAILGROUPSMM_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model NewsletterServiceMailGroupsMm',
                    'ruleName' => null,
                    'parent' => ['NEWSLETTERT_ADMINISTRATOR']
                ],
                [
                    'name' =>  'NEWSLETTERSERVICEMAILGROUPSMM_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model NewsletterServiceMailGroupsMm',
                    'ruleName' => null,
                    'parent' => ['NEWSLETTERT_ADMINISTRATOR']
                ],

            ];
    }
}
