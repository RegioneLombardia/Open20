<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\icons\AmosIcons;
use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\models\Topic;

$this->title = $course->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="moodle-not-enrolled">
<?php
if (!$courseEnrolled) {
    if ($selfEnrollment) {
        $enrolUrl = [
            'enrol-in-course',
            'id' => $course->id,
            'uid' => $uid,
            'org' => $org
        ];
        ?>
        <div class="text-center col-sm-8 col-sm-offset-2 col-xs-12">
            <h2>
                <?= AmosIcons::show('check-circle', ['class' => 'am-4 text-success']) ?>
                <br /><?= AmosMoodle::t('amosmoodle', '#ok_iscrizione_corso') ?>
            </h2>
            <?= Html::a(AmosMoodle::t('amosmoodle', '#iscrivimi', [
                'modelClass' => 'Moodle Topic',
            ]), $enrolUrl, ['class' => 'btn btn-amministration-primary'])
            ?>
        </div>
        <?php
    } else {
        $askEnrolmentClosedCourseUrl = [
            'ask-enrolment-in-closed-course',
            'id' => $course->id,
            'uid' => $uid,
            'org' => $org
        ];
    ?>
        <div class="text-center">
            <h2 class="text-danger"><?= AmosIcons::show('alert-circle', ['class' => 'am-4']) ?>
                <br /><?= AmosMoodle::t('amosmoodle', '#warning') ?>
            </h2>
            <?= AmosIcons::show('chevron-down', ['class' => 'am-4 text-danger']) ?>
            <h3><strong><?= AmosMoodle::t('amosmoodle', '#gentle_user') ?></strong>,
                <br /><?=  AmosMoodle::t('amosmoodle', '#not_public_course') ?>
                <br />
                <br />
              <?= AmosMoodle::t('amosmoodle', '#need_subscription_request') ?>
            </h3>

            <?= Html::a(AmosMoodle::t('amosmoodle', '#request_subscription', [
                'modelClass' => 'Moodle Topic',
            ]), $askEnrolmentClosedCourseUrl, ['class' => 'btn btn-amministration-primary'])
            ?>
            <br /> <br />
            <p><?= AmosMoodle::t('amosmoodle', '#subscription_already_request') ?></p>
        </div>
        <?php
    }
} else {
?>
    <div class="text-center">
        <h2 class="text-success"><?= AmosIcons::show('check-circle', ['class' => 'am-4']) ?>
            <br /><?=    AmosMoodle::t('amosmoodle', '#congrats') ?>
        </h2>
        <?= AmosIcons::show('chevron-down', ['class' => 'am-4 text-success']) ?>
        <h3><strong><?= AmosMoodle::t('amosmoodle', '#gentle_user') ?></strong>,
            <br /><?= AmosMoodle::t('amosmoodle', '#user_already_subscribed') ?>
        </h3>
    </div>
<?php } ?>

</div>
