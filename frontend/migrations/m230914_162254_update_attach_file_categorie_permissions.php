<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m230914_162254_update_attach_file_categorie_permissions
 */
class m230914_162254_update_attach_file_categorie_permissions extends AmosMigrationPermissions
{
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'ATTACHFILECATEGORIE_CREATE',
                'update' => true,
        	'newValues' => [
                    'addParents' => ['ATTACH_DATABANK_FILE_ADMINISTRATOR']
        	]
            ],
            [
                'name' => 'ATTACHFILECATEGORIE_READ',
                'update' => true,
        	'newValues' => [
                    'addParents' => ['ATTACH_DATABANK_FILE_ADMINISTRATOR']
        	]
            ],
            [
                'name' => 'ATTACHFILECATEGORIE_UPDATE',
                'update' => true,
        	'newValues' => [
                    'addParents' => ['ATTACH_DATABANK_FILE_ADMINISTRATOR']
        	]
            ],
            [
                'name' => 'ATTACHFILECATEGORIE_DELETE',
                'update' => true,
        	'newValues' => [
                    'addParents' => ['ATTACH_DATABANK_FILE_ADMINISTRATOR']
        	]
            ],
        ];
    }
}