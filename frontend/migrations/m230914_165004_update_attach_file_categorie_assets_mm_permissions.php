<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m230914_165004_update_attach_file_categorie_assets_mm_permissions
 */
class m230914_165004_update_attach_file_categorie_assets_mm_permissions extends AmosMigrationPermissions
{
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'ATTACHFILECATEGORIEASSETSMM_CREATE',
                'update' => true,
        	'newValues' => [
                    'addParents' => ['ATTACH_DATABANK_FILE_ADMINISTRATOR']
        	]
            ],
            [
                'name' => 'ATTACHFILECATEGORIEASSETSMM_READ',
                'update' => true,
        	'newValues' => [
                    'addParents' => ['ATTACH_DATABANK_FILE_ADMINISTRATOR']
        	]
            ],
            [
                'name' => 'ATTACHFILECATEGORIEASSETSMM_UPDATE',
                'update' => true,
        	'newValues' => [
                    'addParents' => ['ATTACH_DATABANK_FILE_ADMINISTRATOR']
        	]
            ],
            [
                'name' => 'ATTACHFILECATEGORIEASSETSMM_DELETE',
                'update' => true,
        	'newValues' => [
                    'addParents' => ['ATTACH_DATABANK_FILE_ADMINISTRATOR']
        	]
            ],
        ];
    }
}