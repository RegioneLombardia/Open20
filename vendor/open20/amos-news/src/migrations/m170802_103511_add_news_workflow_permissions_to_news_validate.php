<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\news\models\News;

/**
 * Class m170802_103511_add_news_workflow_permissions_to_news_validate
 */
class m170802_103511_add_news_workflow_permissions_to_news_validate extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => News::NEWS_WORKFLOW_STATUS_BOZZA,
                'update' => true,
                'newValues' => [
                    'addParents' => ['NewsValidate'],
                    'removeParents' => ['NewsValidateOnDomain']
                ]
            ],
            [
                'name' => News::NEWS_WORKFLOW_STATUS_DAVALIDARE,
                'update' => true,
                'newValues' => [
                    'addParents' => ['NewsValidate'],
                    'removeParents' => ['NewsValidateOnDomain']
                ]
            ],
            [
                'name' => News::NEWS_WORKFLOW_STATUS_VALIDATO,
                'update' => true,
                'newValues' => [
                    'addParents' => ['NewsValidate'],
                    'removeParents' => ['NewsValidateOnDomain']
                ]
            ],
            [
                'name' => 'NewsValidateOnDomain',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATED_BASIC_USER']
                ]
            ],
            [
                'name' => 'NewsValidate',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATED_BASIC_USER']
                ]
            ]
        ];
    }
}


