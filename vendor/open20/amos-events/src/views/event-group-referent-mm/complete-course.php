<?php
use open20\amos\events\AmosEvents;
$this->title = AmosEvents::t('amosevents', 'Completa il corso');

?>
    <div class="col-md-12">
        <h4><?= AmosEvents::t('amosevents',"Prima di diventare operatore è necessario iscriversi e completare il corso di formazione.")?>
        <br>
            <?= AmosEvents::t('amosevents', "Successivamente sarai abilitato da un membro dello staff.")?>
        </h4>
    </div>
<?php if(!$courseEnrolled){ ?>
    <div class="col-xs-12 m-t-20">
        <?= \yii\helpers\Html::a(AmosEvents::t('amosevents', "COMPLETA IL CORSO"), ['/moodle/course/enrol-in-course', 'id' => $course_id, 'user_id' => \Yii::$app->user->id, 'send_email' => false], [
            'class' => 'btn btn-primary'
        ]); ?>
    </div>
<?php } else { ?>
<div class="col-xs-12 m-t-20">
    <?= \yii\helpers\Html::a(AmosEvents::t('amosevents', "COMPLETA IL CORSO"), ['/community/join', 'id' => $course->community_id], [
        'class' => 'btn btn-primary'
    ]); ?>
</div>
<?php } ?>

