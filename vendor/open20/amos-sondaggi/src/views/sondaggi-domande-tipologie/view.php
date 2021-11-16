<?php

use open20\amos\sondaggi\AmosSondaggi;
use kartik\detail\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiDomandeTipologie $model
 */
$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Tipologie domande'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sondaggi-domande-tipologie-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'tipologia',
            'descrizione:ntext',
            'attivo' => [
                'attribute' => 'attivo',
                'value' => ($model->attivo) ? 'Si' : 'No',
            ]
            /* [
              'attribute'=>'created_at',
              'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
              ],
              [
              'attribute'=>'updated_at',
              'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
              ],
              [
              'attribute'=>'deleted_at',
              'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
              ],
              'created_by',
              'updated_by',
              'deleted_by',
              'version', */
        ],
    ]) ?>
</div>
