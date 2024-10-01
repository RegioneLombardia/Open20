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
* @var open20\socialwall\models\SocialwallPostsProfiles $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/socialwall']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Socialwall Posts Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socialwall-posts-profiles-view">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
                'type',
            'profile_name',
            'profile_url',
    ],    
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
