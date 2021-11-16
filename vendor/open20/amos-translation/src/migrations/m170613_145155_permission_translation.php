<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170613_145155_permission_translation
 */
class m170613_145155_permission_translation extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'CanTranslateRule',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Rule to translate',
                'ruleName' => \open20\amos\translation\rules\CanTranslateRule::className(),
                'parent' => ['CONTENT_TRANSLATOR']
            ],
            [
                'name' => 'TRANSLATOR',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to translate',
                'ruleName' => null,
                'parent' => ['CanTranslateRule']
            ],
        ];
    }
}
