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
 * Class m190109_172216_fix_sondaggi_widgets_permissions
 */
class m190109_172216_fix_sondaggi_widgets_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['AMMINISTRAZIONE_SONDAGGI', 'COMPILATORE_SONDAGGI'],
                    'removeParents' => ['ADMIN', 'BASIC_USER']
                ]
            ]
        ];
    }
}
