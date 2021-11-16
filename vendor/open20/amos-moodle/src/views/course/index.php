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

$this->title = AmosMoodle::t('amosmoodle', '#courses');
$this->params['breadcrumbs'][] = $this->title;

$uid = $this->params['uid'];
$org = $this->params['org'];
$uidNameSurname = $this->params['uidNameSurname'];
$dataProvider = $this->params['dataProvider'];
?>
<div class="moodle-topic-index">
    <?php
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider, // $parametro,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'immagine' => [
                    'label' => AmosMoodle::t('amosmoodle', '#image'),
                    'format' => 'html',
                    'value' => function ($model) {
                        /** @var MoodleCourse $model */
                        $url = '/img/img_default.jpg';
                        if (!is_null($model->imageurl)) {
                            $url = $model->imageurl;
                        }
                        
                        return Html::img($url, [
                            'class' => 'gridview-image', 
                            'alt' => AmosMoodle::t('amosmoodle', '#course_image'), 
                            'title' => $model->name
                        ]);
                    }
                ],
                'name',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => $actionColumn,
                    'buttons' => [
                        'view' => function ($url, $model) use ($uid, $org, $uidNameSurname) {
                            if (!$model->userEnrolled && !Yii::$app->getUser()->can(AmosMoodle::MOODLE_ADMIN)) {
                                if ($model->isPaypalCourse()) {
                                    $urlParams = [
                                        '/moodle/course/paypal-course',
                                        'id' => $model->id,
                                        'uid' => $this->params['uid'],
                                        'org' => $this->params['org']
                                    ];
                                } else {
                                    $urlParams = [
                                        '/moodle/course/not-enrolled-course',
                                        'id' => $model->id,
                                        'uid' => $uid,
                                        'org' => $org
                                    ];
                                }
                            } else {
                                if (!empty($model->community_id)) {
                                    if (($uid != null) && ($uid != \Yii::$app->getUser()->identity->id)) {
                                        $urlParams = [];
                                    } else {
                                        $urlParams = [
                                            '/community/join',
                                            'id' => $model->community_id,
                                        ];
                                    }
                                }
                            }

                            if (!empty($urlParams)) {
                                $icon = $model->userEnrolled
                                    ? 'sign-in'
                                    : 'file'
                                ;
                                return Html::a(
                                    AmosIcons::show(
                                        $icon,
                                        ['class' => 'btn btn-tool-secondary']
                                    ),
                                    Yii::$app->urlManager->createUrl($urlParams), [
                                        'title' => Yii::t('amoscore', '#course_enter_link', [
                                            'course_name' => $model->name
                                        ]),
                                        'class' => 'bk-btnView',
                                    ]
                                );
                            } else {
                                $mess = ($uid != null) ? '#already_enrolled' : '#you_are_enrolled';
                                return AmosMoodle::t('amosmoodle', $mess, [
                                    'nome_cognome' => Html::encode($uidNameSurname)
                                ]);
                            }
                        },
                    ]
                ],
            ],
        ],
        'iconView' => [
            'itemView' => '_icon',
            'masonry' => true,
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
