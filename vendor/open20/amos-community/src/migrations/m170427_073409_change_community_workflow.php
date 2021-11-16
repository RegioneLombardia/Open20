<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\community\models\Community;
use yii\db\Migration;

/**
 * Class m170427_073409_change_community_workflow
 */
class m170427_073409_change_community_workflow extends Migration
{

    const TABLE_WORKFLOW_STATUS = '{{%sw_status}}';
    const TABLE_WORKFLOW_TRANSITIONS = '{{%sw_transition}}';
    const TABLE_WORKFLOW_METADATA = '{{%sw_metadata}}';

    public function safeUp()
    {
        /** Editing label and metadata for status TOVALIDATE */
        $this->update(self::TABLE_WORKFLOW_STATUS, ['label' => 'Publish request'],
            ['id' => 'TOVALIDATE', 'workflow_id' => Community::COMMUNITY_WORKFLOW]);

        $this->update(self::TABLE_WORKFLOW_METADATA,
            [ 'value'=> 'Send publication request for the Community' ],
            [
                'workflow_id' => Community::COMMUNITY_WORKFLOW,
                'status_id' => 'TOVALIDATE',
                'key' => 'description'
            ]);
        $this->update(self::TABLE_WORKFLOW_METADATA,
            [ 'value'=> 'Publication request' ],
            [
                'workflow_id' => Community::COMMUNITY_WORKFLOW,
                'status_id' => 'TOVALIDATE',
                'key' => 'label'
            ]);
        $this->update(self::TABLE_WORKFLOW_METADATA,
            [ 'value'=> 'Send publication request for this community?' ],
            [
                'workflow_id' => Community::COMMUNITY_WORKFLOW,
                'status_id' => 'TOVALIDATE',
                'key' => 'message'
            ]);

        /** Editing label and metadata for status VALIDATED */
        $this->update(self::TABLE_WORKFLOW_STATUS, ['label' => 'Published'],
            ['id' => 'VALIDATED', 'workflow_id' => Community::COMMUNITY_WORKFLOW]);

        $this->update(self::TABLE_WORKFLOW_METADATA,
            [ 'value'=> 'Publish the Community' ],
            [
                'workflow_id' => Community::COMMUNITY_WORKFLOW,
                'status_id' => 'VALIDATED',
                'key' => 'description'
            ]);
        $this->update(self::TABLE_WORKFLOW_METADATA,
            [ 'value'=> 'Published' ],
            [
                'workflow_id' => Community::COMMUNITY_WORKFLOW,
                'status_id' => 'VALIDATED',
                'key' => 'label'
            ]);
        $this->update(self::TABLE_WORKFLOW_METADATA,
            [ 'value'=> 'Publish this Community?' ],
            [
                'workflow_id' => Community::COMMUNITY_WORKFLOW,
                'status_id' => 'VALIDATED',
                'key' => 'message'
            ]);

        /** Insert transition from status TOVALIDATE to status DRAFT */
        $this->insert(self::TABLE_WORKFLOW_TRANSITIONS,
            [
                'workflow_id' => Community::COMMUNITY_WORKFLOW,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'DRAFT'
            ]);
        /**  find communities in status not validated and replace the status with draft */
        $this->update('community', ['status' => Community::COMMUNITY_WORKFLOW_STATUS_DRAFT],
            ['status' => Community::COMMUNITY_WORKFLOW_STATUS_NOT_VALIDATED]);

        /** delete status not validated from workflow tables */
        $this->delete(self::TABLE_WORKFLOW_METADATA, [
            'workflow_id' => Community::COMMUNITY_WORKFLOW,
            'status_id' => 'NOTVALIDATED',
        ]);
        $this->delete(self::TABLE_WORKFLOW_TRANSITIONS, [
            'workflow_id' => Community::COMMUNITY_WORKFLOW,
            'start_status_id' => 'NOTVALIDATED'
        ]);
        $this->delete(self::TABLE_WORKFLOW_TRANSITIONS, [
            'workflow_id' => Community::COMMUNITY_WORKFLOW,
            'end_status_id' => 'NOTVALIDATED'
        ]);
        $this->delete(self::TABLE_WORKFLOW_STATUS, ['id' => 'NOTVALIDATED', 'workflow_id' => Community::COMMUNITY_WORKFLOW]);

        return true;
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_WORKFLOW_TRANSITIONS,
            [
                'workflow_id' => Community::COMMUNITY_WORKFLOW,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'DRAFT'
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
                    'NOTVALIDATED',
                    Community::COMMUNITY_WORKFLOW,
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
                    Community::COMMUNITY_WORKFLOW,
                    'TOVALIDATE',
                    'NOTVALIDATED'
                ],
                [
                    Community::COMMUNITY_WORKFLOW,
                    'NOTVALIDATED',
                    'DRAFT'
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
                    Community::COMMUNITY_WORKFLOW,
                    'NOTVALIDATED',
                    'class',
                    'btn btn-navigation-primary'
                ],
                [
                    Community::COMMUNITY_WORKFLOW,
                    'NOTVALIDATED',
                    'description',
                    'The Community will not be validated'
                ],
                [
                    Community::COMMUNITY_WORKFLOW,
                    'NOTVALIDATED',
                    'label',
                    'Not validated'
                ],
                [
                    Community::COMMUNITY_WORKFLOW,
                    'NOTVALIDATED',
                    'message',
                    'Do not validate this Community?'
                ],
                [
                    Community::COMMUNITY_WORKFLOW,
                    'NOTVALIDATED',
                    'order',
                    '3'
                ],
            ]);
        return true;
    }

}
