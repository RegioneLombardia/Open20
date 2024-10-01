<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m231003_174200_task_manager_jobs_update
 */
class m231003_174200_task_manager_jobs_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('task_manager_jobs', 'start_execution_time', $this->dateTime()->defaultValue(null)->after('filename'));
        $this->addColumn('task_manager_jobs', 'end_execution_time', $this->dateTime()->defaultValue(null)->after('start_execution_time'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('task_manager_jobs', 'start_execution_time');
        $this->dropColumn('task_manager_jobs', 'end_execution_time');
    }
}
