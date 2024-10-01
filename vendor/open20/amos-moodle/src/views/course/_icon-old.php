<?php

use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\helpers\MoodleHelper;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;

use Yii;

/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */
$skipPlatform = Yii::$app->getModule('moodle')->skipPlatformGotoMoodleDirectly;

$linkOptions = [];
if ($skipPlatform == true) {
    $courseUrl = MoodleHelper::getMoodleOAuthLink(
        Yii::$app->getModule('moodle')->moodleUrl . '/course/view.php?id=' . $model->moodle_courseid
    );
    $linkOptions = ['target' => '_blank'];
}

?>
<div class="listview-container moodle-item nop">
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
                    
                    if ($skipPlatform == false) {
                        $courseUrl = Yii::$app->urlManager->createUrl($urlParams);
                    }
                    
                    echo Html::a(Html::tag('h2', $model->name), $courseUrl, $linkOptions);
                } else {
                    echo Html::tag('h2', $model->name);
                }
            ?>
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
                    'alt' => AmosMoodle::_t('#course_image')
                ]);
                if (!empty($model->community_id)) { 
                    if (!empty($urlParams)) {
                        echo Html::a($contentImage, Yii::$app->urlManager->createUrl($urlParams));
                    } else {
                        $mess = ($uid != null) ? '#already_enrolled' : '#you_are_enrolled';
                        echo AmosMoodle::_t($mess, [
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
                    if ($skipPlatform == false) {
                        $courseUrl = Yii::$app->urlManager->createUrl($urlParams, [
                            'class' => 'underline',
                            'title' => AmosMoodle::_t('#course_enter_title')
                        ]);
                    }
                    echo Html::a(AmosMoodle::_t('#enter'), $courseUrl, $linkOptions);
                }
                ?>
                </p>
            </div>

        </div>
    </div>
</div>