<?php

use backend\modules\statistics\widgets\icons\WidgetDataAnalysis;
use open20\amos\core\migration\AmosMigrationPermissions;

class m210531_183001_data_analysis_widget_permission_update extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => WidgetDataAnalysis::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_APPROVER', 'PC_STRUCTURE_APPROVER']
                ],
            ],
        ];
    }
}