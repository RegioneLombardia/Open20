<?php

use open20\amos\sondaggi\AmosSondaggi;
use kartik\detail\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiRispostePredefinite $model
 */
$this->title = $model;
// $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Risposte predefinite del sondaggio'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="sondaggi-risposte-predefinite-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'risposta:ntext',
            'sondaggi_domande_id',
            'ordinamento',
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
