<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;

class m210519_083000_workflow_sessioni_permission extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return \yii\helpers\ArrayHelper::merge(
                $this->setPluginRoles(), $this->setWorkflowStatusPermissions()
        );
    }

    /**
     * Plugin roles.
     *
     * @return array
     */
    private function setPluginRoles()
    {
        return [
        ];
    }

    /**
     * Workflow statuses permissions
     *
     * @return array
     */
    private function setWorkflowStatusPermissions()
    {
        return [
            [
                'name' => SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Compilazione del sondaggio',
                'parent' => ['COMPILA_SONDAGGIO']
            ],
            [
                'name' => SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Richiesta di invio compilazione',
                'parent' => ['SondaggiSessioniWorkflowGeneric']
            ],
            [
                'name' => SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Invio compilazione',
                'parent' => ['SondaggiSessioniWorkflowGeneric']
            ],
        ];
    }
}