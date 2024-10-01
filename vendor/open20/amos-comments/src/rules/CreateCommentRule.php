<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\rules
 * @category   CategoryName
 */

namespace open20\amos\comments\rules;

use open20\amos\core\helpers\StringHelper;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\core\rules\ReadContextRule;
use yii\rbac\Rule;

/**
 * Class CreateCommentRule
 * @package open20\amos\comments\rules
 */
class CreateCommentRule extends ReadContextRule
{
    public $name = 'createCommentFromContext';
    public $contextClass = 'context';
    public $contextId = 'context_id';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            $model = $params['model'];
            $modelNameForRule = StringHelper::baseName($model->className());
            if ($modelNameForRule == 'Comment' || $modelNameForRule == 'CommentReply')
                return parent::execute($user, $item, $params);
            /** @var Record $model */
            \Yii::debug(strtoupper($modelNameForRule).'_READ', 'comment');
            return \Yii::$app->user->can(strtoupper($modelNameForRule).'_READ', $params);
        } else {
            return false;
        }
    }
}
