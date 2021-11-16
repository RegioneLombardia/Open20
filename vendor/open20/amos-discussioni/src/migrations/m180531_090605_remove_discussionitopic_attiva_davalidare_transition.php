<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;
use \open20\amos\discussioni\models\DiscussioniTopic;
use yii\helpers\ArrayHelper;

/**
 * Class m180531_090605_remove_discussionitopic_attiva_davalidare_transition
 */
class m180531_090605_remove_discussionitopic_attiva_davalidare_transition extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setProcessInverted(true);
    }

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(parent::setWorkflow(), [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
                'start_status_id' => 'ATTIVA',
                'end_status_id' => 'DAVALIDARE'
            ],
        ]);
    }
}
