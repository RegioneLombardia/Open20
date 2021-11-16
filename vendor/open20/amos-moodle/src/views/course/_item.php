<?php

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\moodle\AmosMoodle;

/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */
?>
<div class="listview-container news-item grid-item nop">
    <div class="post col-xs-12">
        <div class="post-content col-xs-12 nop">
            <div class="post-title col-xs-10">
                <?php
                if (!empty($model->community_id)) {
                    if (!$model->userEnrolled && !Yii::$app->getUser()->can(AmosMoodle::MOODLE_ADMIN)) {
                        if ($model->isPaypalCourse()) {
                            $urlParams = [
                                '/moodle/course/paypal-course',
                                'id' => $model->id,
                                'uid' => $uid,
                                'org' => $org
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
                    ?>
                    <?= Html::a(Html::tag('h2', $model->name), Yii::$app->urlManager->createUrl($urlParams)) ?>
                <?php } else { ?>
                    <?= Html::tag('h2', $model->name) ?>
                <?php } ?>
            </div>
            <div class="post-image col-xs-12">
            <?php
                $url = '/img/img_default.jpg';
                if (!is_null($model->imageurl)) {
                    $url = $model->imageurl;
                }
                $contentImage = Html::img($url, [
                    'width' => '50',
                    'class' => 'img-responsive',
                    'alt' => AmosMoodle::t('amosmoodle', '#course_image')
                ]);
                if (!empty($model->community_id)) { 
                    if (!empty($urlParams)) {
                        echo Html::a($contentImage, Yii::$app->urlManager->createUrl($urlParams));
                    } else {
                        $mess = ($uid != null) ? '#already_enrolled' : '#you_are_enrolled';
                        echo AmosMoodle::t('amosmoodle', $mess, [
                            'nome_cognome' => Html::encode($uidNameSurname)
                        ]);
                    }
                } else {
                    echo $contentImage;
                } 
            ?>
            </div>
            <div class="post-text col-xs-12">
                <p>
                <?php
                echo $model->summary;
                if ((!empty($model->community_id)) && (!empty($urlParams))) {
                    echo Html::a(
                        AmosMoodle::t('amosmoodle', '#course_enter'), 
                        Yii::$app->urlManager->createUrl($urlParams), [
                            'class' => 'underline',
                            'title' => AmosMoodle::t('amosmoodle', '#course_enter_title')
                        ]
                    );
                }
                ?>
                </p>
            </div>

        </div>
    </div>
</div>