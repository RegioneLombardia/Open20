<?php

use open20\amos\notificationmanager\AmosNotify;

$this->params['breadcrumbs'][] = $this->title;

$js = <<<JS
    $('#sql').on('click',function(e){
        e.preventDefault();
        if($(this).hasClass('nascosto')){
            $(this).show();
        }
    })
JS;

//$this->registerJs($js);


$labelsConf = \open20\amos\notificationmanager\models\NotificationsConfOpt::emailFrequencyValueAndLabels();
$userProfile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $user_id])->one();
if ($userProfile) {
    $nome = $userProfile->nomeCognome;
    $notifyConf = \open20\amos\notificationmanager\models\NotificationConf::find()->andWhere(['user_id' => $user_id])->one();
}
?>
<h5>
    <?= AmosNotify::t('amosnotify', "Riepilogo notifiche di <strong>{nome}</strong>", ['nome' => $nome]) ?>
    <br>
    <?= AmosNotify::t('amosnotify', "Destinatario riepilogo <strong>{email}</strong>", ['email' => $email]) ?>
</h5>
<?php if ($notifyConf) { ?>
    <br>
    <h5><?= "Configurazioni utente" ?></h5>
    <div class="row">
        <div class="col-md-6">
            <strong><?= 'Notifiche abilitate: ' ?></strong> <?= \Yii::$app->formatter->asBoolean($notifyConf->notifications_enabled) ?>
            <br><strong><?= 'Tipo riepilogo: ' ?></strong><?= $labelsConf[$notifyConf->email] ?>
            <br><strong><?= 'Ricevi notifiche di carattere generale: ' ?></strong> <?= \Yii::$app->formatter->asBoolean($userProfile->notify_from_editorial_staff) ?>
        </div>
        <div class="col-md-6">
            <strong><?= 'Notifica pubblicazione contenuti: ' ?></strong> <?= \Yii::$app->formatter->asBoolean($notifyConf->notify_content_pubblication) ?>
            <br><strong><?= 'Notifica commenti: ' ?></strong> <?= \Yii::$app->formatter->asBoolean($notifyConf->notify_comments) ?>
        </div>
    </div>
<?php } ?>
<br>

<?php echo \yii\helpers\Html::a('Mostra query sql', '#sql', ['data-toggle'=>"collapse", 'class' => 'btn btn-secondary']) ?>
<?php if (!empty($debugSqlNotifications[$user_id])) { ?>
    <br>
    <div id="sql" class="collapse">
        <h5><?= 'Query sql eseguita' ?></h5>
        <pre>
        <?= $debugSqlNotifications[$user_id]['generic'] ?>
    </pre>
        <?php foreach ($debugSqlNotifications[$user_id]['models'] as $classname => $sql) { ?>
            <h5><?= 'Query sql ' . $classname ?></h5>
            <pre>
            <?= $sql ?>
        </pre>
        <?php } ?>
    </div>
<?php } ?>

<?php if (!empty($text)) { ?>
    <br>
    <div>
        <h5><?= "Email inviata" ?></h5>
        <?= $text ?>
    </div>
<?php } else { ?>
    <br>
    <h5><?= 'Non è stata inviata nessuna email' ?></h5>
<?php }
?>

<?= \yii\helpers\Html::a('Indietro', ['/notify/notification-schedule/simulate'], ['class' => 'btn btn-secondary']) ?>

