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

class CreatePodcastRule extends DefaultOwnContentRule
{

    public $name = 'createPodcastRule';


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

            $module = \Yii::$app->getModule('podcast');
            //if you are in comunity, only cm can create
            if($module && $module->canCreateOnlyCommunityManager){
                $moduleCwh = \Yii::$app->getModule('cwh');
                $community_id = null;
                isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
                if ($scope && $scope['community']) {
                    $community_id = $scope['community'];
                }
                if($community_id) {
                    return PodcastUtility::isManagerCommunity();
                }
            }

            //basic user cannot create a podcast
            if($module && $module->basicUserCannotCreate) {
                return false;
            }

            return true;
        } else {
            return false;
        }
    }
}