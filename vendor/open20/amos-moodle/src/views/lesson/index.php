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

$this->title = AmosMoodle::_t('#activity');
$this->params['breadcrumbs'][] = $this->title;
$dataProvider = $this->params['dataProvider'];

Modal::begin([
    'id' => 'lesson-info',
    'header' => '<span id="modal-title"></span>',
]);
?>
<div>
    <p><?= AmosMoodle::_t('#no_info_available'); ?></p>
</div>

<?php Modal::end(); ?>

<div class="moodle-topic-index">
<?= DataProviderView::widget([
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
                        $fontType = 'am';
                        switch ($model->modname) {
                            case AmosMoodle::MOODLE_MODNAME_CERTIFICATE:
                            case AmosMoodle::MOODLE_MODNAME_CUSTOMCERT:
                                $icon_modname = 'my-certificate';
                                $fontType = 'dash';
                                $title = AmosMoodle::_t('#type_certificate');
                                break;
                            case AmosMoodle::MOODLE_MODNAME_QUIZ:
                            case AmosMoodle::MOODLE_MODNAME_QUESTIONNAIRE:
                                $fontType = 'dash';
                                $icon_modname = 'question-circle';
                                $title = AmosMoodle::_t('#type_quiz');
                                break;
                            case AmosMoodle::MOODLE_MODNAME_RESOURCE:
                            case AmosMoodle::MOODLE_MODNAME_PAGE:
                                $icon_modname = 'file-text';
                                $title = AmosMoodle::_t('#type_document');
                                break;
                            case AmosMoodle::MOODLE_MODNAME_SCORM:
                                $icon_modname = 'graduation-cap';
                                $title = AmosMoodle::_t('#type_scorm');
                                break;
                            default:
                                $icon_modname = '';
                        }

                        return AmosIcons::show(
                            $icon_modname, [
                                'class' => 'icon-mooodle',
                                'title' => $title,
                            ],
                            $fontType
                        );
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
                                $statusIcon = AmosIcons::show('check', [
                                    'class' => 'state-wait',
                                    'title' => AmosMoodle::_t('#activity_not_done')
                                ]);
                                break;
                            case ServiceCall::ACTIVITY_STATUS_COMPLETE:
                            case ServiceCall::ACTIVITY_STATUS_COMPLETE_PASS:
                                $statusIcon = AmosIcons::show('check', [
                                    'class' => 'am-2 state-ok',
                                    'title' => AmosMoodle::_t('#activity_done')
                                ]);
                                break;
                            default:
                                $statusIcon = '';
                        }

                        return  $statusIcon;
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
                            if (in_array($model['modname'], AmosMoodle::instance()->resourcesWhiteList)) {
                                //green
                                $btn = Html::a(AmosMoodle::_t('#enter'), null, [
                                    'class' => 'btn btn-primary',
                                    'data-toggle' => 'modal',
                                    'data-lesson-id' => $model['id'],
                                    'data-lesson-name' => $model['name'],
                                    'data-lesson-instance' => $model['instance'],
                                    'data-lesson-modname' => $model['modname'],
                                    'data-target' => '#lesson-info',
                                    'title' => AmosMoodle::_t('#go_activity'),
                                ]);
                            } else {
                                //grey - deve essere link?
                                $btn = Html::a(
                                    AmosMoodle::_t('#enter'),
                                    MoodleHelper::getMoodleOAuthLink($model['url']), [
                                        'class' => 'btn btn-primary disabled',
                                        'target' => '_blank',
                                        'title' => AmosMoodle::_t('#not_go_activity'),
                                    ]
                                );
                            }
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
