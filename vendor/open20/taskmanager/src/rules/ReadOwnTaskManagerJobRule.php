<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\taskmanager
 * @category   CategoryName
 */

namespace open20\amos\taskmanager\rules;

use Yii;
use yii\rbac\Rule;

class ReadOwnTaskManagerJobRule extends Rule
{
  public $name = 'readOwnTaskManagerJob';

  /**
   * @inheritdoc
   */
  public function execute($user, $item, $model)
  {
    if (\Yii::$app->getUser()->can('TASKMANAGER_MANAGER')) return true;
    if ($model->created_by == \Yii::$app->getUser()->getId()) return true;
    return false;
  }
}
