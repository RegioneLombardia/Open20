<?php

use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\helpers\MoodleHelper;
use open20\amos\notificationmanager\forms\NewsWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\utilities\CurrentUser;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;

use Yii;

/*
 * Personalizzare a piacimento la vista
 * $model Ã¨ il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */
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
    if (!$iscritto) { //  && !Yii::$app->getUser()->can(AmosMoodle::MOODLE_ADMIN)) {
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
    if (($skipPlatform == true) && ($iscritto == false)) {
            $courseUrl = Yii::$app->urlManager->createUrl($urlParams);
    }
}
}
?>

<div class="listview-container corso-item nop">
    <div class="container-corso col-xs-12 nop">
        <div class="card-wrapper <?= $iscritto ? 'signed' : 'unsigned' ?>">
            <div class="card card-img">
                <div class="img-responsive-wrapper w-100 pr-xl-3">
                    <div class="image-wrapper position-relative w-100 h-100">
                        <?php
                            $url = '/img/img_default_moodle.png';
                            if (!is_null($model->imageurl)) {
                                $url = $model->imageurl;
                            }
                            $contentImage = Html::img($url, [
                                'width' => '50',
                                'class' => 'full-width',
                                'alt' => AmosMoodle::_t('#course_image')
                            ]);
                            if (!empty($model->community_id)) { 
                                echo Html::a($contentImage, $courseUrl);
                            } else {
                                echo $contentImage;
                            } 
                        ?>
                        <?= NewsWidget::widget(['model' => $model]); ?>
                        <!-- <div class="card-calendar d-flex flex-column justify-content-center position-absolute rounded-0">
                            <span class="card-day font-weight-bold text-600 lead">< ?= Html::tag('strong', \Yii::$app->getFormatter()->asDate($model->getPublicatedFrom(), 'd')) ?></span>
                            <span class="card-month text-uppercase font-weight-bold text-600 small">< ?= Html::tag('strong', \Yii::$app->getFormatter()->asDate($model->getPublicatedFrom(), 'MMM')) ?></span>
                            <span class="card-year font-weight-light text-600 small">< ?= \Yii::$app->getFormatter()->asDate($model->getPublicatedFrom(), 'y') ?></span>
                        </div> -->
                    </div>
                </div>
                <div class="card-body pl-0">
                    <?= 
                        ItemAndCardHeaderWidget::widget([
                                'model' => $model,
                                'publicationDateNotPresent' => true,
                                'enableLink' => !(CurrentUser::isPlatformGuest()), 
                            ]
                        ) 
                    ?>
                    <hr class="w-75 my-2 ml-0">
                    <div>
                        <?php
			if (!empty($model->community_id)) {
/*                                if (!$iscritto) { //  && !Yii::$app->getUser()->can(AmosMoodle::MOODLE_ADMIN)) {
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
                                
                                if (($skipPlatform == true) && ($iscritto == false)) {
					$courseUrl = Yii::$app->urlManager->createUrl($urlParams);
                                }
 */
                                echo Html::a(Html::tag('h3', $model->name, ['class' => 'card-title font-weight-bold']), $courseUrl, ['class' => 'link-list-title', 'title' => 'Vai al corso ' .  $model->name , 'target' => ($skipPlatform == true) ? 'blank' : 'self']);			
			} else {
                                echo Html::tag('h3', $model->name, ['class' => 'card-title font-weight-bold']);
                            }
                        ?>
                        <?php if (!empty(\open20\amos\core\utilities\CwhUtility::getTargetsString($model))) : ?>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="< ?= \open20\amos\core\utilities\CwhUtility::getTargetsString($model) ?>">
                                <span class="mdi mdi-account-supervisor-circle text-muted"></span>
                                <span class="sr-only"><?= \open20\amos\core\utilities\CwhUtility::getTargetsString($model) ?></span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <p class="card-description font-weight-light"><?= $model->summary ?></p>
                    
                    <?php 
                        if (!empty($model->community_id))  {
                            if ($skipPlatform == false) {
                                $courseUrl = Yii::$app->urlManager->createUrl($urlParams, [
                                    'class' => 'underline',
                                    'title' => AmosMoodle::_t('#course_enter_title')
                                ]);
                            } 
                    ?>
<?php if (!empty($model->community_id)) {
                                if (!$iscritto) {
                                    echo Html::a("Iscriviti", $courseUrl, ['class' => 'btn btn-primary px-5']);
                                } else {
                                    $mess = ($uid != null) ? '#already_enrolled' : '#you_are_enrolled';
                    ?>
                                <div class="container-cta">
                                    <em>
                                        <?php
                                        echo AmosMoodle::_t($mess, [
                                            'nome_cognome' => Html::encode($uidNameSurname)
                                        ]);
                                        ?>
                                    </em>
                                    <a class="read-more small" href="<?= $courseUrl ?>" title="Vai al corso <?= $model->name ?>" target="<?= ($skipPlatform == true) ? 'blank' : 'self'?>">
                                        <?= Html::tag('span', AmosMoodle::_t('#enter'), ['class' => 'text']) ?>
                                    </a>
                                </div>
                            <?php
                                }
                            }
                        ?>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>