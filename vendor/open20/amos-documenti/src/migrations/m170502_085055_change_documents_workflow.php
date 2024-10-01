<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m170502_085055_change_documents_workflow
 */
class m170502_085055_change_documents_workflow extends Migration
{
    const TABLE_WORKFLOW_STATUS = '{{%sw_status}}';
    const TABLE_WORKFLOW_TRANSITIONS = '{{%sw_transition}}';
    const TABLE_WORKFLOW_METADATA = '{{%sw_metadata}}';

    public function safeUp()
    {
        /** Insert transition from status TOVALIDATE to status DRAFT */
        $this->insert(self::TABLE_WORKFLOW_TRANSITIONS,
            [
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'start_status_id' => 'DAVALIDARE',
                'end_status_id' => 'BOZZA'
            ]);
        /**  find News in status not validated and replace the status with draft */
        $this->update(Documenti::tableName(), ['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA],
            ['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_NONVALIDATO]);

        /** delete status not validated from workflow tables */
        $this->delete(self::TABLE_WORKFLOW_METADATA, [
            'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
            'status_id' => 'NONVALIDATO',
        ]);
        $this->delete(self::TABLE_WORKFLOW_TRANSITIONS, [
            'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
            'start_status_id' => 'NONVALIDATO'
        ]);
        $this->delete(self::TABLE_WORKFLOW_TRANSITIONS, [
            'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
            'end_status_id' => 'NONVALIDATO'
        ]);
        $this->delete(self::TABLE_WORKFLOW_STATUS, ['id' => 'NONVALIDATO', 'workflow_id' => Documenti::DOCUMENTI_WORKFLOW]);

        return true;
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_WORKFLOW_TRANSITIONS,
            [
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'start_status_id' => 'DAVALIDARE',
                'end_status_id' => 'BOZZA'
            ]);

        $this->batchInsert(self::TABLE_WORKFLOW_STATUS,
            [
                'id',
                'workflow_id',
                'label',
                'sort_order'
            ],
            [
                [
                    'NONVALIDATO',
                    Documenti::DOCUMENTI_WORKFLOW,
                    'Not validated',
                    3
                ]
            ]);
        $this->batchInsert(self::TABLE_WORKFLOW_TRANSITIONS,
            [
                'workflow_id',
                'start_status_id',
                'end_status_id'
            ],
            [
                [
                    Documenti::DOCUMENTI_WORKFLOW,
                    'DAVALIDARE',
                    'NONVALIDATO'
                ],
                [
                    Documenti::DOCUMENTI_WORKFLOW,
                    'NONVALIDATO',
                    'BOZZA'
                ],
            ]);
        $this->batchInsert(self::TABLE_WORKFLOW_METADATA,
            [
                'workflow_id',
                'status_id',
                'key',
                'value'
            ],
            [
                //metadata NOTVALIDATED status
                [
                    'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                    'status_id' => 'NONVALIDATO',
                    '`key`' => 'class',
                    'value' => 'btn btn-navigation-primary'
                ],
                [
                    'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                    'status_id' => 'NONVALIDATO',
                    '`key`' => 'description',
                    'value' => 'Il documento non verrà validato'
                ],
                [
                    'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                    'status_id' => 'NONVALIDATO',
                    '`key`' => 'label',
                    'value' => 'Non validato'
                ],
                [
                    'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                    'status_id' => 'NONVALIDATO',
                    '`key`' => 'message',
                    'value' => 'Vuoi non validare questo documento?'
                ],
                [
                    'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                    'status_id' => 'NONVALIDATO',
                    '`key`' => 'order',
                    'value' => '2'
                ]
            ]);
        return true;
    }
}
