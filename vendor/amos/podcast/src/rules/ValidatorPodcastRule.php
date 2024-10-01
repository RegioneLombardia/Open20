<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 27/10/2020
 * Time: 17:13
 */

namespace amos\podcast\rules;

use amos\podcast\utility\PodcastUtility;
use open20\amos\core\record\Record;
use open20\amos\core\rules\DefaultOwnContentRule;
use Yii;
use amos\podcast\models\PodcastEpisode;

class ValidatorPodcastRule extends DefaultOwnContentRule
{

    public $name = 'validatorPodcastRule';

    /**
     * @inheritdoc
     */
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
            $moduleCwh = \Yii::$app->getModule('cwh');
            $community_id = null;
            isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
            if ($scope && $scope['community']) {
                $community_id = $scope['community'];
            }

            if ($community_id) {
                return PodcastUtility::isManagerCommunity();
            }
            return false;
        } else {
            return false;
        }
    }

}