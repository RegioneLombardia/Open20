<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/operator/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
* @var yii\web\View $this
* @var open20\agid\service\models\AgidServiceContentType $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/operators']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Content Type Service'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agid-content-type-service-view">

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
