<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-community/src/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
* @var yii\web\View $this
* @var open20\amos\community\models\CommunityUserField $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/community']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Community User Field'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-user-field-view">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
                'community_id',
            'user_field_type_id',
            'name',
            'description:html',
            'tooltip:html',
            'required',
    ],    
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
