<?php

namespace open20\socialwall\widgets;

use open20\socialwall\models\SocialwallPosts;
use open20\socialwall\models\SocialwallPostsInSocialwall;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class SocialwallPickedPostsSelectorWidget extends \yii\base\Widget
{
    public $form;
    public $model;
    public $socialwallModel;
    public $inputId;
    public $parentContainerId;
    private $selectedList;

    private function getSelectedPostsForModel($model) {
        $modelId = $model->id;
        $selectedPosts = ArrayHelper::getColumn(SocialwallPostsInSocialwall::find()->andWhere(['socialwall_id' => $modelId])->asArray()->all(), 'socialwall_posts_id');
        $this->selectedList = implode(",", $selectedPosts);
        $dataProvider = new ActiveDataProvider([
            'query' => SocialwallPosts::find()
                ->andWhere(['IN', 'socialwall_posts.id', $selectedPosts])
                ->orderBy('publication_date DESC'),
            'pagination' => false
        ]);
        $dataProvider->setSort(false);
        return $dataProvider;
    }

    public function run()
    {
        $modelSocialwallPosts = new SocialwallPosts();
        $modelSocialwallPosts->setScenario(SocialwallPosts::SCENARIO_NOHIDE);

        return $this->render('socialwall-picked-posts-selector', [
            'form' => $this->form,
            'model' => $this->model,
            'modelSocialwallPosts' => $modelSocialwallPosts,
            'socialwallModel' => $this->socialwallModel,
            'inputId' => $this->inputId,
            'selectedPostsDataProvider' => $this->getSelectedPostsForModel($this->socialwallModel),
            'selectedList' => $this->selectedList,
            'parentContainerId' => $this->parentContainerId,
        ]);
    }
}