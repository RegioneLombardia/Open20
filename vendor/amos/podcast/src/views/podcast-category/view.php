<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/amos/podcast/src/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
* @var yii\web\View $this
* @var amos\podcast\models\PodcastCategory $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/podcast']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Podcast Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podcast-category-view">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
                'name',
            'description:html',
    ],    
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
