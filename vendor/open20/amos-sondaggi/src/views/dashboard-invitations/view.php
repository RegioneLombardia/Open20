<?php

use open20\amos\sondaggi\AmosSondaggi;
use kartik\detail\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiDomandePagine $model
 */
$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Domande dei sondaggi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-domande-pagine-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'sondaggi_id' => [
                'attribute' => 'sondaggi_id',
                'value' => $model->sondaggi['titolo']
            ],
            'domanda',

            /* 'filemanager_mediafile_id',
              [
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
