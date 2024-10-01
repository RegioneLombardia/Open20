<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170717_094844_add_documents_facilitator_to_platform_facilitator
 */
class m170717_094844_add_documents_facilitator_to_platform_facilitator extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'FACILITATORE_DOCUMENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['FACILITATOR']
                ]
            ],
        ];
    }
}