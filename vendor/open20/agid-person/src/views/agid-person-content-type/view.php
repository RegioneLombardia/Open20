<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modues/operators/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
* @var yii\web\View $this
* @var backend\modules\operators\models\AgidPersonContentType $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/operators']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Content Type Persona'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agid-person-content-type-view">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
                'name',
            'description:html',
    ],    
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-primary']); ?></div>
