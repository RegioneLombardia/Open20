<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\moodle\models\Lesson;
use open20\amos\moodle\helpers\MoodleHelper;
use yii\bootstrap\Modal;
use open20\amos\moodle\assets\MoodleAsset;
use yii\web\View;
use open20\amos\moodle\models\ServiceCall;
use open20\amos\moodle\AmosMoodle;

MoodleAsset::register($this);

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yii\data\ArrayDataProvider $parametro
 */
$actionColumn = '{view}';

$this->title = AmosMoodle::t('amosmoodle', 'Attività');
$this->params['breadcrumbs'][] = $this->title;
$dataProvider = $this->params['dataProvider'];

Modal::begin([
    'id' => 'lesson-info',
    'header' => '<span id="modal-title"></span>',
]);
?>
<div>
    <p><?= AmosMoodle::t('amosmoodle', '#no_info_available'); ?></p>
</div>

<?php Modal::end(); ?>

<div class="moodle-topic-index">
    <?php // echo $this->render('_search', ['model' => $model]);          ?>

    <p>

        <?php /* echo         Html::a(Yii::t('cruds', 'Nuovo {modelClass}', [
          'modelClass' => 'Moodle Topic',
          ])        , ['create'], ['class' => 'btn btn-amministration-primary']) */ ?>
    </p>

    <?php
//pr($parametro);
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider, // $parametro,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                // ['class' => 'yii\grid\SerialColumn'],
                //'id',
                'modname' => [
                    'attribute' => 'modname',
                    'format' => 'html',
                    'value' => function($model) {
                        /** @var Lesson $model */
                        if (!is_null($model->modname)) {
                            switch ($model->modname) {
                                case "certificate":
                                    $icon_modname = "my-certificate";
                                    $fontType = "dash";
                                    $title = "Tipologia Certificato";
                                    break;
                                case "resource":
                                    $icon_modname = "file-text";
                                    $fontType = "am";
                                    $title = "Tipologia Documento";
                                    break;
                                case "scorm":
                                    $icon_modname = "graduation-cap";
                                    $fontType = "am";
                                    $title = "Tipologia Lezione";
                                    break;

                                default:
                                    $icon_modname = '';
                            }


                            return AmosIcons::show($icon_modname, [
                                    'class' => 'icon-mooodle',
                                    'title' => $title,
                            ], $fontType);
                        } else {
                            return '';
                        }
                    }
                ],
                'name',
                //'modname',
                //'uservisible',
                //'moodleActivitiesCompletionStatus',
                'moodleActivitiesCompletionStatus' => [
                    'attribute' => 'moodleActivitiesCompletionStatus',
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'format' => 'html',
                    'value' => function($model) {
                        /** @var Lesson $model */
                        if (!is_null($model['moodleActivitiesCompletionStatus'])) {
                            switch ($model['moodleActivitiesCompletionStatus']) {
                                case ServiceCall::ACTIVITY_STATUS_INCOMPLETE:
                                case ServiceCall::ACTIVITY_STATUS_COMPLETE_FAIL:
                                    $statusIcon = AmosIcons::show('check', ['class' => 'state-wait', 'title' => 'Attività non completata']);
                                    break;
                                case ServiceCall::ACTIVITY_STATUS_COMPLETE:
                                case ServiceCall::ACTIVITY_STATUS_COMPLETE_PASS:
                                     $statusIcon = AmosIcons::show('check', ['class' => 'am-2 state-ok', 'title' => 'Attività completata']);
                                    break;
                                default:
                                     $statusIcon = '';
                            }


                            return  $statusIcon;
                        } else {
                            return '';
                        }
                    }
                ],
                //'url',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => $actionColumn,
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if ($model['uservisible']) {
                                if ($model['modname'] == "scorm" || $model['modname'] == "certificate") {

                                    //green
                                    $btn = Html::a('Entra', null, [
                                                'class' => 'btn btn-primary',
                                                'data-toggle' => 'modal',
                                                'data-lesson-id' => $model["id"],
                                                'data-lesson-name' => $model["name"],
                                                'data-lesson-instance' => $model["instance"],
                                                'data-lesson-modname' => $model["modname"],
                                                'data-target' => '#lesson-info',
                                                'title' => 'Entra nell\'attività',
                                    ]);
                                } else {
                                    //grey - deve essere link?
                                    $btn = Html::a('Entra', MoodleHelper::getMoodleOAuthLink($model['url']), [
                                                'title' => Yii::t('amoscore', 'Leggi'),
                                                'class' => 'btn btn-primary disabled',
                                                'target' => '_blank',
                                                'title' => 'Non è possibile entrare in questa attività',
                                            ]
                                    );
                                }
                            } else {
                                $btn = AmosIcons::show('forward', ['class' => 'btn btn-secondary']);
                            }

                            return $btn;
                        },
                    ]
                ],
            ],
        ],
    ]);
    ?>

</div>
