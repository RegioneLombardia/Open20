<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/socialwall/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;

/**
* @var yii\web\View $this
* @var open20\socialwall\models\SocialwallPosts $model
*/

$this->title = Yii::t('socialwall', 'Contenuto');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/socialwall']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('socialwall', 'Socialwall Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('socialwall', 'Contenuto');
?>
<div class="socialwall-posts-view">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
            [
                'attribute' => 'profile_name',
                'format' => 'raw',
                'value' => function ($model) {
                    $socialLogo = "";
                    switch ($model->type) {
                        case \open20\socialwall\utility\Social::FACEBOOK:
                            $socialLogo = '<span class="icon icon-lg icon-white mdi mdi-facebook" style="font-size: 40px;"></span>';
                            break;
                        case \open20\socialwall\utility\Social::INSTAGRAM:
                            $socialLogo = '<span class="icon icon-lg icon-white mdi mdi-instagram" style="font-size: 40px;"></span>';
                            break;
                        case \open20\socialwall\utility\Social::TWITTER:
                            $socialLogo = '<span class="icon icon-lg icon-white mdi mdi-twitter" style="font-size: 40px;"></span>';
                            break;
                    }

//                    return "<div style=\"display: flex;align-items: center;\">" .$socialLogo . "<a class=\"m-l-5\" href=\"{$model->profile_url}\" target=\"_blank\">{$model->profile_name}</a></div>";
                    return "<div style=\"display: flex; align-items: center;\">" .
                                $socialLogo .
                                ((!empty($model->profile_picture)) ?
                                    "<img class=\"m-l-10\" src=\"{$model->profile_picture}\" width=\"25px\" />"
                                    : ""
                                ) .
                                "<a class=\"m-l-5\" href=\"{$model->socialwallPostsProfile->profile_url}\" target=\"_blank\">{$model->socialwallPostsProfile->profile_name}</a>" .
                        "</div>";
                }
            ],
            [
                'format' => 'raw',
                'label' => $model->attributeLabels()['post_image'],
                'value' => function ($model) {
                    if(!empty($model->hasOneFile('post_image')->one())) {
                        return "<img src=\"{$model->getPostImage()->getWebUrl()}\" style='max-width: 100%;' />";
                    } else {
                        return "";
                    }
                },
            ],
            'text:html',
            [
                'attribute' => 'permalink',
                'format' => 'raw',
                'value' => function ($model) {
                    return "<a href=\"{$model->permalink}\" target=\"_blank\">{$model->permalink}</a>";
                }
            ],
            [
                'attribute' => 'publication_date',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate(
                        strtotime($model->publication_date),
                        isset(
                            \Yii::$app->components['formatter']['datetimeFormat']) ?
                            \Yii::$app->components['formatter']['datetimeFormat'] :
                            'd-m-Y H:i:s');
                }
            ],
            'search_tags',
    ],    
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
