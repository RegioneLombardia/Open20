<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m190205_145830_add_sondaggi_permissions
 */
class m190205_145830_add_sondaggi_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'SONDAGGI_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SondaggiValidate']
                ]
            ],
            [
                'name' => 'SONDAGGIDOMANDE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SondaggiValidate']
                ]
            ],
            [
                'name' => 'SONDAGGIDOMANDE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SondaggiValidate']
                ]
            ],
            [
                'name' => 'SONDAGGIDOMANDEPAGINE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SondaggiValidate']
                ]
            ],
            [
                'name' => 'SONDAGGIDOMANDEPAGINE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SondaggiValidate']
                ]
            ],
            [
                'name' => 'SONDAGGIRISPOSTEPREDEFINITE_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SondaggiValidate']
                ]
            ],
            [
                'name' => 'SONDAGGIRISPOSTEPREDEFINITE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SondaggiValidate']
                ]
            ],
            [
                'name' => 'SONDAGGIRISPOSTEPREDEFINITE_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SondaggiValidate']
                ]
            ],
            [
                'name' => 'SONDAGGIRISPOSTEPREDEFINITE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SondaggiValidate']
                ]
            ]
        ];
    }
}
