<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m181121_151220_fix_lettore_organizzazioni_role
 */
class m181121_151220_fix_lettore_organizzazioni_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'PROFILOSEDI_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['LETTORE_ORGANIZZAZIONI']
                ]
            ]
        ];
    }
}
