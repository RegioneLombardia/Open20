<?php

use backend\modules\userimporter\Module;


$appLink = Yii::$app->params['platform'] ['backendUrl'];
$link = $appLink. "/userimporter/user-import-task/optout?token=" . $token;
?>

<div>
    <?= Module::t('amosuserimporter', '#gentile',['nome' => $profile->getNomeCognome()]) ?>
    <div>
    <?= Module::t('amosuserimporter', "#out_mail_text") ?>
        <a href="<?= $link ?>"><?= Module::t('amosuserimporter', "#link") ?></a>
    </div>
   
</div>

