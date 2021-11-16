<?php

class m210525_151142_update_widgets extends \open20\amos\core\migration\AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'amos\userimporter\widgets\icons\WidgetIconImporter',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['AMMINISTRATORE_USERIMPORTTASK'],
                ]
            ],
        ];
    }
}