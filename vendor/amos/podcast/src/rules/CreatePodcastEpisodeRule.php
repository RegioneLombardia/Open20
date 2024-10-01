<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 23/10/2020
 * Time: 15:34
 */

namespace amos\podcast\rules;


use amos\podcast\utility\PodcastUtility;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\rules\DefaultOwnContentRule;

class CreatePodcastEpisodeRule extends DefaultOwnContentRule
{

    public $name = 'createPodcastEpisodeRule';


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

            $module = \Yii::$app->getModule('podast');
            if($module && $module->canCreateCommunityManager){
                $moduleCwh = \Yii::$app->getModule('cwh');
                $community_id = null;
                isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
                if ($scope && $scope['community']) {
                    $community_id = $scope['community'];
                }
                if($community_id) {
                    return PodcastUtility::isManagerCommunity();
                }
                return true;
            }
            return true;
        } else {
            return false;
        }
    }
}