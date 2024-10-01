<?php

use open20\amos\collaborations\models\CollaborationProposals;
use yii\db\Migration;

class m221215_155800_insert_workflow_dataconfirm_close_button extends Migration
{
    const TABLE = '{{%sw_metadata}}';


    public function safeUp()
    {
        // Button label
        $this->insert(
            self::TABLE,
            [
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'CLOSED',
                'key' => 'message',
                'value' => 'La proposta verra\' chiusa forzatamente e non sara\' piu\' modificabile. Inoltre, non sara\' piu\' visibile tra le proposte pubblicate. Sei sicuro di voler cambiare stato?'
            ]
        );

        return true;
    }

    public function safeDown()
    {
        $this->delete(
            self::TABLE,
            [
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'CLOSED',
                'key' => 'message',
                'value' => 'La proposta verra\' chiusa forzatamente e non sara\' piu\' modificabile. Inoltre, non sara\' piu\' visibile tra le proposte pubblicate. Sei sicuro di voler cambiare stato?'

            ]
        );

        return true;
    }
}
