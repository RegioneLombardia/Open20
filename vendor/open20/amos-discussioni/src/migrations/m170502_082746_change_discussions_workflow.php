<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\amos\discussioni\models\DiscussioniTopic;

/**
 * Class m170502_082746_change_discussions_workflow
 */
class m170502_082746_change_discussions_workflow extends Migration
{
    const TABLE_WORKFLOW_STATUS = '{{%sw_status}}';
    const TABLE_WORKFLOW_TRANSITIONS = '{{%sw_transition}}';
    const TABLE_WORKFLOW_METADATA = '{{%sw_metadata}}';

    public function safeUp()
    {
        /** Insert transition from status TOVALIDATE to status DRAFT */
        $this->insert(self::TABLE_WORKFLOW_TRANSITIONS,
            [
                'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
                'start_status_id' => 'DAVALIDARE',
                'end_status_id' => 'BOZZA'
            ]);
        /**  find News in status not validated and replace the status with draft */
        $this->update(DiscussioniTopic::tableName(), ['status' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_BOZZA],
            ['status' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DISATTIVA]);

        /** delete status not validated from workflow tables */
        $this->delete(self::TABLE_WORKFLOW_METADATA, [
            'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
            'status_id' => 'DISATTIVA',
        ]);
        $this->delete(self::TABLE_WORKFLOW_TRANSITIONS, [
            'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
            'start_status_id' => 'DISATTIVA'
        ]);
        $this->delete(self::TABLE_WORKFLOW_TRANSITIONS, [
            'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
            'end_status_id' => 'DISATTIVA'
        ]);
        $this->delete(self::TABLE_WORKFLOW_STATUS, ['id' => 'DISATTIVA', 'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW]);

        return true;
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_WORKFLOW_TRANSITIONS,
            [
                'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
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
                    'DISATTIVA',
                    DiscussioniTopic::DISCUSSIONI_WORKFLOW,
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
                    DiscussioniTopic::DISCUSSIONI_WORKFLOW,
                    'DAVALIDARE',
                    'DISATTIVA'
                ],
                [
                    DiscussioniTopic::DISCUSSIONI_WORKFLOW,
                    'DISATTIVA',
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
                    'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
                    'status_id' => 'DISATTIVA',
                    '`key`' => 'class',
                    'value' => 'btn btn-navigation-primary'
                ],
                [
                    'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
                    'status_id' => 'DISATTIVA',
                    '`key`' => 'description',
                    'value' => 'La discussione non verrà validata'
                ],
                [
                    'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
                    'status_id' => 'DISATTIVA',
                    '`key`' => 'label',
                    'value' => 'Non validata'
                ],
                [
                    'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
                    'status_id' => 'DISATTIVA',
                    '`key`' => 'message',
                    'value' => 'Vuoi non validare questa discussione?'
                ],
                [
                    'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
                    'status_id' => 'DISATTIVA',
                    '`key`' => 'order',
                    'value' => '2'
                ],
            ]);
        return true;
    }
}
