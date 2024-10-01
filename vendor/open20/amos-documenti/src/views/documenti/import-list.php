<?php

use open20\amos\core\views\AmosGridView;
use open20\amos\core\icons\AmosIcons;

use yii\helpers\Html;
use yii\grid\ActionColumn;
    
$this->title = 'Import list';
$this->params['breadcrumbs'][] = $this->title;

echo AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'name_file',
        [
            'attribute' => 'successfull',
            'format' => 'boolean'
        ],
        [
            'attribute' => 'created_at',
            'format' => 'datetime'
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{download}',
            'buttons' => [
                'download' => function($url, $model){
                    return Html::a(
                        AmosIcons::show('download'),
                        ['/import/default/generate-excel', 'id' => $model->id], 
                        ['class' => 'btn btn-tools-secondary']
                    );
                }
            ]
        ]
    ]
]);
