<?php

use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\helpers\MoodleHelper;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;

use Yii;
use yii\helpers\StringHelper;

$uidNameSurname = $this->params['uidNameSurname'];

$skipPlatform = Yii::$app->getModule('moodle')->skipPlatformGotoMoodleDirectly;

$isEnrolled = new open20\amos\moodle\models\ServiceCall();
$iscritto = $isEnrolled->isUserEnrolledInCourse($model->moodle_courseid);

$linkOptions = [];
if (($skipPlatform == true) && ($iscritto)) {
    $courseUrl = MoodleHelper::getMoodleOAuthLink(
        Yii::$app->getModule('moodle')->moodleUrl . '/course/view.php?id=' . $model->moodle_courseid
    );
    $linkOptions = ['target' => '_blank'];
} else {

if (!empty($model->community_id)) {
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
                'uid' => $this->params['uid'],
                'org' => $this->params['org']
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
}
}
?>

<div class="moodle-topic-index card-container col-xs-12 nop">
    <div class="icon-header">
        <div class="col-xs-12 nop">
            <?php // contenitore-square-img
            $url = '/img/img_default.jpg';
            if (!is_null($model->imageurl)) {
                $url = $model->imageurl;
            }
            $roundImage = Yii::$app->imageUtility->getRoundImage($url);
            $class = $roundImage['class'];
            //$class = 'full-width';
            if($class == 'full-width'){
                $style = "width: 100%; height: auto; margin-top:". ($roundImage['margin-top'])  . "%;";
            } elseif ($class == 'full-height') {
                $style = "height: 100%; width: auto; margin-left: " . $roundImage['margin-left'] . "%;";
            } else {
                $style = " width: 100%; height: auto;";
            }
            
            $contentImage = Html::img($url, [
                'class' => $roundImage['class'],
                'style' => $style,
                'alt' => AmosMoodle::_t('#course_image')
            ]);

            if (!empty($model->community_id)) {
                echo Html::a($contentImage, $courseUrl, $linkOptions);
            } else {
                echo $contentImage; 
            } 
            ?>
        </div>
        
    </div>
    
    <div class="icon-body">
        <?php 
            if ($iscritto) {
                $mess = ($uid != null) ? '#already_enrolled' : '#you_are_enrolled';
                echo AmosMoodle::_t($mess, [
                    'nome_cognome' => Html::encode($uidNameSurname)
                ]);
            }

            echo Html::a(Html::tag('h2', $model->name), $courseUrl, $linkOptions);
        echo  Html::tag('h2', $model->name);
        ?>
        <?php if($model->summary){ ?>
            <div class="course-description">
                <?= StringHelper::truncate($model->summary, 255, '', null, true)  ?>
            </div>
        <?php } ?>
        
        <div class="read-more">
        <?php
            if ((!empty($model->community_id)) && (!empty($urlParams))) {
                $label = $model->userEnrolled 
                    ? '#enter'
                    : '#enrolme'
                ;

                echo Html::a(AmosMoodle::_t($label), $courseUrl, $linkOptions);
            }
        ?>
        </div>
    </div>
    
</div>