<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\videoconference
 * @category   CategoryName
 */

namespace open20\amos\videoconference\rules;

use open20\amos\videoconference\AmosVideoconference;
use open20\amos\admin\models\UserProfile;
use yii\rbac\Item;
use yii\rbac\Rule;
use open20\amos\videoconference\models\Videoconf;

/**
 * Class UpdateOwnVideoconference
 * @package open20\amos\admin\rbac
 */
class UpdateOwnVideoconference extends Rule
{
    public $name        = 'isYourVideoconference';
    public $description = '';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $model = ((isset($params['model']) && $params['model']) ? $params['model'] : new Videoconf());

        if (!$model->id) {
            $post = \Yii::$app->getRequest()->post();
            $get  = \Yii::$app->getRequest()->get();

            if (isset($get['id'])) {
                $model = $this->instanceModel($model, $get['id']);
            } elseif (isset($post['id'])) {
                $model = $this->instanceModel($model, $post['id']);
            }
        }

        return ($model->created_by == $user);
    }

    /**
     * @param Videoconf $model
     * @param int $modelId
     * @return mixed
     */
    private function instanceModel($model, $modelId)
    {
        /** @var Videoconf $videconfInstance */
        $videconfInstance = new Videoconf;
        $instancedModel   = $videconfInstance::findOne($modelId);
        if (!is_null($instancedModel)) {
            $model = $instancedModel;
        }
        return $model;
    }
}