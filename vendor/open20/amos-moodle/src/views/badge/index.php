<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
//use open20\amos\moodle\models\Course;
use open20\amos\moodle\helpers\MoodleHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yii\data\ArrayDataProvider $parametro
 */
$actionColumn = '{view}';

$this->title = Yii::t('cruds', 'Riconoscimenti');
$this->params['breadcrumbs'][] = $this->title;
$dataProvider = $this->params['dataProvider'];
?>
<div class="moodle-topic-index">
    <?php // echo $this->render('_search', ['model' => $model]);    ?>

    <p>
        <?php /* echo         Html::a(Yii::t('cruds', 'Nuovo {modelClass}', [
          'modelClass' => 'Moodle Topic',
          ])        , ['create'], ['class' => 'btn btn-amministration-primary']) */ ?>
    </p>

    <?php
    //pr($parametro);exit;
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider, // $parametro,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                // ['class' => 'yii\grid\SerialColumn'],
                'id',
                'fullname',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => $actionColumn,
                    'buttons' => [
                        'view' => function ($url, $model) {
                            $createUrlParams = [
                                '/moodle/topic/index',
                                'courseId' => $model['id'],
                            ];
                            $btn = Html::a(
                                            AmosIcons::show('file', ['class' => 'btn btn-tool-secondary']), Yii::$app->urlManager->createUrl($createUrlParams), [
                                        'title' => Yii::t('amoscore', 'Leggi'),
                                        'class' => 'bk-btnView',
                                            ]
                            );
                            return $btn;
                        },
                    ]
                ],
            ],
        ],
        'listView' => [
            'itemView' => '_item',
            'masonry' => TRUE,
            'masonrySelector' => '.grid',
            'masonryOptions' => [
                'itemSelector' => '.grid-item',
                'columnWidth' => '.grid-sizer',
                'percentPosition' => 'true',
                'gutter' => 20
            ]
        ]
    ]);
    ?>

</div>
