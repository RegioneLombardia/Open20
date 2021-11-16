<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-events/src/views
 */

use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventLocation $model
 */

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Event Location'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-location-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description',
            'addressField' => [
                'attribute' => 'addressField',
                'label' => \open20\amos\events\AmosEvents::t('amosevents', 'Event Address'),
            ],
        ],
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
