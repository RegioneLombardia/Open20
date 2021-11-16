<?php

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\moodle\AmosMoodle;

use yii\helpers\StringHelper;

$uidNameSurname = $this->params['uidNameSurname'];

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
                'alt' => AmosMoodle::t('amosmoodle', '#course_image')
            ]);

            if (!empty($model->community_id)) {
                echo Html::a($contentImage, Yii::$app->urlManager->createUrl($urlParams), ['title' => $model->name]);
            } else {
                echo $contentImage; 
            } 
            ?>
        </div>
        <div class="under-img">
            <?php if ((!empty($model->community_id)) && (!empty($urlParams))) {
                $label = $model->userEnrolled
                    ? '#course_enter'
                    : '#iscrivimi'
                ;

                echo Html::a(
                    AmosMoodle::t('amosmoodle', $label),
                    Yii::$app->urlManager->createUrl($urlParams), [
                        'class' => 'btn btn-navigation-primary',
                        'title' => AmosMoodle::t('amosmoodle', '#course_enter_title')
                    ]
                );
            }
            ?>
        </div>
    </div>
    
    <div class="col-xs-12 nop icon-body">
    <?php 
        if ((empty($urlParams)) && ($model->userEnrolled)) {
            $mess = ($uid != null) ? '#already_enrolled' : '#you_are_enrolled';
            echo AmosMoodle::t('amosmoodle', $mess, [
                'nome_cognome' => Html::encode($uidNameSurname)
            ]);
        }
        
        echo Html::a(
            Html::tag('h2', $model->name), 
            Yii::$app->urlManager->createUrl($urlParams), [
                'title' => $model->name
            ]
        );
    ?>
<?php } else { ?>
        <?= Html::tag('h2', $model->name) ?>
<?php } ?>
        <p><?= StringHelper::truncate($model->summary, 255, '', null, true)  ?></p>
    </div>
</div>