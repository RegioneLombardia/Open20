<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
//use open20\amos\moodle\models\Course;
use open20\amos\moodle\helpers\MoodleHelper;
use open20\amos\moodle\AmosMoodle;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yii\data\ArrayDataProvider $parametro
 */
$actionColumn = '{view}';

$this->title = AmosMoodle::_t('#courses');
$this->params['breadcrumbs'][] = $this->title;
$dataProvider = $this->params['dataProvider'];
?>
<div class="moodle-topic-index">
    <?php
    if ($userNotValid) {
        echo AmosMoodle::_t('#user_not_exist');
    } else {
        ?>
        <?php  echo $this->render('_search', ['model' => $model]);    ?>

        <h2 style="color:red;">
            <?php echo AmosMoodle::_t('#enrol_user', [
                'nomeUtente' => $userToEnrol->userProfile->nomeCognome,
            ]); ?>
        </h2>

        <?php
        //pr($parametro);
        echo DataProviderView::widget([
            'dataProvider' => $dataProvider, // $parametro,
            //'filterModel' => $model,
            'currentView' => $currentView,
            'gridView' => [
                'columns' => [
                    // ['class' => 'yii\grid\SerialColumn'],
                    //'moodle_courseid',
                    // 'userEnrolled',
                    'immagine' => [
                        'label' => AmosMoodle::_t('#image'),
                        'format' => 'html',
                        'value' => function ($model) {
                            /** @var MoodleCourse $model */
                            $url = '/img/img_default.jpg';
                            if (!is_null($model->imageurl)) {
                                $url = $model->imageurl;
                            }
                            $contentImage = Html::img($url, [
                                'class' => 'gridview-image',
                                'alt' => AmosMoodle::_t('#course_image'),
                                'title' => $model->name
                            ]);
                            
                            return $contentImage;
                        }
                    ],
                    'name',
                    'moodleCategory' => [
                        'label' => AmosMoodle::_t('#category'),
                        'format' => 'html',
                        'value' => function ($model) {
                           $model->moodleCategory->getMoodleCategoryData();
                           return $model->moodleCategory->name;
                        }
                    ],
                     
                    [
                        'class' => 'open20\amos\core\views\grid\ActionColumn',
                        'template' => $actionColumn,
                        'buttons' => [
                            'view' => function ($url, $model) use ($userToEnrol) {
                               if ($model->userEnrolled) {
                                    return AmosMoodle::_t('#already_subscribed');
                                } else {
                                    $btn = '';
                                    $urlParams = [
                                        '/moodle/course/enrol-in-course',
                                        'id' => $model->id,
                                        'userId' => $userToEnrol->id,
                                    ];

                                    if (!empty($urlParams)) {
                                        $btn = Html::a(
                                            AmosIcons::show(
                                                'book', [
                                                    'class' => 'btn btn-tool-secondary'
                                                ]),
                                                Yii::$app->urlManager->createUrl($urlParams),
                                                [
                                                    'title' => AmosMoodle::_t('#enrol_user', [
                                                        'nomeUtente' => $userToEnrol->userProfile->nomeCognome
                                                     ]),
                                                    'class' => 'bk-btnView',
                                                    'data-confirm' => AmosMoodle::_t('#confirm_popup', [
                                                        'nomeCognome' => $userToEnrol->userProfile->nomeCognome,
                                                        'courseName' => $model->name]
                                                    )
                                                ]
                                        );
                                    }
                                    return $btn;
                                }
                            },
                        ]
                    ],
                ],
            ],
        ]);
    }
    ?>

</div>
