<?php

use yii\db\Migration;

class m221215_153800_alter_columns_workflow_close_button_sw_metadata extends Migration
{
    const TABLE = '{{%sw_metadata}}';


    public function safeUp()
    {
        // Button label
        $this->update(
            self::TABLE,
            [
                'value' => 'Chiudi proposta'
            ],
            [
                'workflow_id' => 'CollaborationProposalsWorkflow',
                'status_id' => 'CLOSED',
                'key' => 'buttonLabel',
                'value' => 'Chiudi'
            ]
        );
        // Description
        $this->update(
            self::TABLE,
            [
                'value' => 'La proposta non sarà più modificabile definitivamente'
            ],
            [
                'workflow_id' => 'CollaborationProposalsWorkflow',
                'status_id' => 'CLOSED',
                'key' => 'description',
                'value' => 'La proposta non sarà più modificabile'
            ]
        );

        return true;
    }

    public function safeDown()
    {
        // Button label
        $this->update(
            self::TABLE,
            [
                'value' => 'Chiudi'
            ],
            [
                'workflow_id' => 'CollaborationProposalsWorkflow',
                'status_id' => 'CLOSED',
                'key' => 'buttonLabel',
                'value' => 'Chiudi proposta'
            ]
        );
        // Description
        $this->update(
            self::TABLE,
            [
                'value' => 'La proposta non sarà più modificabile'
            ],
            [
                'workflow_id' => 'CollaborationProposalsWorkflow',
                'status_id' => 'CLOSED',
                'key' => 'description',
                'value' => 'La proposta non sarà più modificabile definitivamente'
            ]
        );

        return true;
    }
}
