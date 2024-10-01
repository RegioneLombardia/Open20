<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 23/10/2020
 * Time: 15:34
 */

namespace amos\podcast\rules;


use open20\amos\core\rules\DefaultOwnContentRule;

class UpdateOwnPodcastRule extends DefaultOwnContentRule
{

    public $name = 'updateOwnPodcastRule';


    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            /** @var Record $model */
            $model = $params['model'];
            if (!$model->id) {
                $post = \Yii::$app->getRequest()->post();
                $get = \Yii::$app->getRequest()->get();
                if (isset($get['id'])) {
                    $model = $this->instanceModel($model, $get['id']);
                } elseif (isset($post['id'])) {
                    $model = $this->instanceModel($model, $post['id']);
                }
            }
            if(\Yii::$app->user->can('PODCAST_VALIDATOR')){
                return true;
            }

            return ($model->created_by == $user);
        } else {
            return false;
        }
    }
}