<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170907_094628_delete_discussioni_allegati_permissions
 */
class m170907_094628_delete_discussioni_allegati_permissions extends AmosMigrationPermissions
{
    public function init()
    {
        parent::init();
        $this->setProcessInverted(true);
    }
    
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DISCUSSIONIALLEGATI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIALLEGATI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIALLEGATI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONIALLEGATI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE allegati sul model DiscussioniAllegati',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
        ];
    }
}
