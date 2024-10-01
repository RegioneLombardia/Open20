<?php
use open20\amos\admin\AmosAdmin;

$this->title = AmosAdmin::t('amosadmin',"Completa il tuo profilo utente");
$assetBundle = \open20\amos\admin\assets\ModuleUserProfileAsset::register($this);

$url = \yii\helpers\Url::to(['/'.AmosAdmin::getModuleName().'/user-profile/update', 'id' => $model->id]);
?>
<div class="col-xs-12 m-t-15">
    <h4><?= AmosAdmin::t('amosadmin',"Porta a termine la tua registrazione utente e richiedi la validazione del profilo, <br> così potrai partecipare attivamente alla creazione di contenuti in piattaforma, accedendo a <a href='{link}'>'Il mio profilo'</a>.",[
            'link' => $url
        ]) ?>
    </h4>
</div>
<div class="col-xs-12 m-t-15">
    <?= \yii\helpers\Html::img($assetBundle->baseUrl.'/img/example_complete_profile.jpg')?>
</div>
<div class="col-xs-12 m-t-15">
    <?= \yii\helpers\Html::a(AmosAdmin::t('amosadmin', 'Chiudi'), '/dashboard', [
        'class' => 'btn btn-navigation-secondary pull-left',
        'title' => AmosAdmin::t('amosadmin', 'Chiudi')
    ]);
    ?>
    <?= \yii\helpers\Html::a(AmosAdmin::t('amosadmin', 'Completa il profilo'), $url, [
        'class' => 'btn btn-navigation-primary pull-right',
        'title' => AmosAdmin::t('amosadmin', 'Completa il profilo')
    ]);
    ?>
</div>
