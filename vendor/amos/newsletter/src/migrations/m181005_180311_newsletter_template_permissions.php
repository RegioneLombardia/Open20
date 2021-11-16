<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m181005_180311_newsletter_template_permissions*/
class m181005_180311_newsletter_template_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'NEWSLETTERT_ADMINISTRATOR',
                    'type' => Permission::TYPE_ROLE,
                    'description' => 'Permesso di CREATE sul model NewsletterTemplate',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'NEWSLETTERTEMPLATE_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model NewsletterTemplate',
                    'ruleName' => null,
                    'parent' => ['NEWSLETTERT_ADMINISTRATOR']
                ],
                [
                    'name' =>  'NEWSLETTERTEMPLATE_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model NewsletterTemplate',
                    'ruleName' => null,
                    'parent' => ['NEWSLETTERT_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'NEWSLETTERTEMPLATE_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model NewsletterTemplate',
                    'ruleName' => null,
                    'parent' => ['NEWSLETTERT_ADMINISTRATOR']
                ],
                [
                    'name' =>  'NEWSLETTERTEMPLATE_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model NewsletterTemplate',
                    'ruleName' => null,
                    'parent' => ['NEWSLETTERT_ADMINISTRATOR']
                ],

            //CONTENT PERMISSION
            [
                'name' =>  'NEWSLETTERTEMPLATECONTENT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsletterTemplateContent',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERTEMPLATECONTENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsletterTemplateContent',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERTEMPLATECONTENT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsletterTemplateContent',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERTEMPLATECONTENT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsletterTemplateContent',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],

            //SECTION PERMISSION
            [
                'name' =>  'NEWSLETTERSECTION_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsletterSection',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERSECTION_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsletterSection',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERSECTION_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsletterSection',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERSECTION_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsletterSection',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],

            //NEWSLETTER PERMISSION
            [
                'name' =>  'NEWSLETTER_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Newsletter',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTER_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Newsletter',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTER_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Newsletter',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTER_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model Newsletter',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],


            //SECTION CONTENT PERMISSION
            [
                'name' =>  'NEWSLETTERSECTIONCONTENT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsletterSectionContent',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERSECTIONCONTENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsletterSectionContent',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERSECTIONCONTENT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsletterSectionContent',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERSECTIONCONTENT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsletterSectionContent',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],

            // content_type permissions
            [
                'name' =>  'NEWSLETTERCONTENTTYPE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsletterContentType',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERCONTENTTYPE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsletterContentType',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERCONTENTTYPE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsletterContentType',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERCONTENTTYPE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsletterContentType',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],


            // section_type permissions
            [
                'name' =>  'NEWSLETTERSECTIONTYPE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsletterSectionType',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERSECTIONTYPE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsletterSectionType',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERSECTIONTYPE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsletterSectionType',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],
            [
                'name' =>  'NEWSLETTERSECTIONTYPE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsletterSectionType',
                'ruleName' => null,
                'parent' => ['NEWSLETTERT_ADMINISTRATOR']
            ],






        ];
    }
}
