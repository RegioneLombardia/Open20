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
use yii\rbac\Permission;

/**
 * Class m170518_074520_associate_news_validator_rule_permissions
 */
class m170518_074520_associate_news_validator_rule_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NewsValidateOnDomain',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate at least one news in a domain with cwh permission',
                'ruleName' => \open20\amos\core\rules\UserValidatorContentRule::className(),
                'parent' => ['VALIDATORE_NEWS']
            ],
            [
                'name' => 'NEWS_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['NewsValidateOnDomain'],
                    'removeParents' => ['VALIDATORE_NEWS']
                ]
            ],
            [
                'name' => News::NEWS_WORKFLOW_STATUS_BOZZA,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow news stato bozza',
                'ruleName' => null,
                'parent' => ['NewsValidateOnDomain'],
                'dontRemove' => true
            ],
            [
                'name' => News::NEWS_WORKFLOW_STATUS_DAVALIDARE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow news stato da validare',
                'ruleName' => null,
                'parent' => ['NewsValidateOnDomain'],
                'dontRemove' => true
            ],
            [
                'name' => News::NEWS_WORKFLOW_STATUS_VALIDATO,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow news stato validato',
                'ruleName' => null,
                'parent' => ['NewsValidateOnDomain'],
                'dontRemove' => true
            ],
            [
                'name' => open20\amos\news\widgets\icons\WidgetIconNewsDaValidare::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconNewsDaValidare',
                'ruleName' => null,
                'parent' => ['NewsValidateOnDomain'],
                'dontRemove' => true
            ]
        ];
    }
}
