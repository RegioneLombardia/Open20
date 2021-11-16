<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170217_152715_remove_news_allegati_permissions
 */
class m170217_152715_remove_news_allegati_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setProcessInverted(true);
    }

    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'NEWSALLEGATI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_NEWS', 'VALIDATORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => 'NEWSALLEGATI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_NEWS', 'VALIDATORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => 'NEWSALLEGATI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_NEWS', 'VALIDATORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => 'NEWSALLEGATI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsAllegati',
                'ruleName' => null,
                'parent' => ['CREATORE_NEWS', 'VALIDATORE_NEWS', 'FACILITATORE_NEWS']
            ]
        ];
    }
}
