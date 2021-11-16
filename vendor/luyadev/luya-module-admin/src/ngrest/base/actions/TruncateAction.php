<?php

namespace luya\admin\ngrest\base\actions;

use Yii;
use yii\rest\Action;

/**
 * Truncate Table
 *
 * This truncates all the data for the given modelClass.
 *
 * @since 3.2.0
 */
class TruncateAction extends Action
{
    /**
     * Truncate all data for given modelClass.
     */
    public function run()
    {
        $class = $this->modelClass;
        $class::getDb()->createCommand()->truncateTable($class::tableName())->execute();
        Yii::$app->getResponse()->setStatusCode(204);
    }
}
