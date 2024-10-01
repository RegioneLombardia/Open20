<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;

/**
 * Class m171221_160155_modify_expressions_of_interest_workflow_1
 */
class m171221_160155_modify_expressions_of_interest_workflow_1 extends Migration
{
    const WORKFLOW_NAME = 'ExpressionsOfInterestWorkflow';
    const STATUS_NAME = 'TOVALIDATE';
    const TABLE_NAME = '{{%sw_metadata}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update(self::TABLE_NAME, ['value' => 'In evaluation'], [
            'workflow_id' => self::WORKFLOW_NAME,
            'status_id' => self::STATUS_NAME,
            'key' => 'label'
        ]);
        MigrationCommon::printConsoleMessage('Modificata label stato workflow in valutazione');
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update(self::TABLE_NAME, ['value' => 'Validation request'], [
            'workflow_id' => self::WORKFLOW_NAME,
            'status_id' => self::STATUS_NAME,
            'key' => 'label'
        ]);
        MigrationCommon::printConsoleMessage('Ripristinata label stato workflow in valutazione');
        return true;
    }
}
