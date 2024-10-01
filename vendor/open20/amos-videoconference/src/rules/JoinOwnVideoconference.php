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
use open20\amos\videoconference\models\VideoconfUsersMm;
use open20\amos\admin\AmosAdmin;

/**
 * Class JoinOwnVideoconference
 * @package open20\amos\admin\rbac
 */
class JoinOwnVideoconference extends Rule
{
    public $name        = 'joinYourVideoconference';
    public $description = '';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $model = ((isset($params['model']) && $params['model']) ? $params['model'] : new VideoconfUsersMm());
if(!($model instanceof VideoconfUsersMm)){
    $model = new VideoconfUsersMm();
}
        if (!$model->videoconf_id) {
            $post = \Yii::$app->getRequest()->post();
            $get  = \Yii::$app->getRequest()->get();

            if (isset($get['id'])) {
                $model = $this->instanceModel($model, $get['id']);
            } elseif (isset($post['id'])) {
                $model = $this->instanceModel($model, $post['id']);
            }
        }

        if (isset($model->user_profile_id)) {
            $userProfile = AmosAdmin::instance()->createModel('UserProfile');
            $profile     = $userProfile::findOne($model->user_profile_id);
            return ($profile->user_id == $user);
        }
        return false;
    }

    /**
     * @param Videoconf $model
     * @param int $modelId
     * @return mixed
     */
    private function instanceModel($model, $modelId)
    {
        /** @var Videoconf $videconfInstance */
        $videconfInstance = new VideoconfUsersMm;
        $instancedModel   = $videconfInstance::findOne(['videoconf_id' => $modelId]);
        if (!is_null($instancedModel)) {
            $model = $instancedModel;
        }
        return $model;
    }
}