<?php
/** @var $sector String
 *  @var $dataProvider \yii\data\ActiveDataProvider
 *  @var $model \open20\amos\events\models\Event
 */
use open20\amos\events\AmosEvents;

$this->title = strip_tags($model->title) ." ". AmosEvents::t('amosevents', 'settore')." '$sector'";
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();

?>

<div class="col-xs-12">
    <h2><?= $this->title ?></h2>
    <?= \yii\helpers\Html::a(AmosEvents::t('amosevents', 'Indietro'), ['view','id' => $model->id, '#' => 'tab-seats_management'],  [
            'class' => 'btn btn-navigation-secondary'
    ]);?>
    <?= \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'row',
            'seat',
            [
                'attribute' => 'automatic',
                'format' => 'boolean'
            ],
            [
                'attribute' => 'available_for_groups',
                'format' => 'boolean'
            ],
            [
                'attribute' => 'stringSeatAssignedTo',
                'label' => AmosEvents::t('amosevents', 'Assegnato a')

            ],
             [
                'attribute' => 'labelStatus',
                'label' => AmosEvents::t('amosevents', 'Status')
            ],
        ]
    ])?>

</div>
