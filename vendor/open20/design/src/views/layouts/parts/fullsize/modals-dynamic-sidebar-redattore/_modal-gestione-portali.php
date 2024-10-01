<?php

use open20\design\Module;
?>
<div class="modal fade" id="modalMyPlatform" tabindex="-1" role="dialog" aria-labelledby="modalMyPlatform" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel"><?= Module::t('amosdesign', 'Gestione portali') ?></h5>
<button class="close" type="button" data-dismiss="modal" aria-label="Close">
    <span class="mdi mdi-close"></span>
</button>
</div>
<div class="modal-body">
    <p class="mb-2">Clicca sul blocco ed effettua l'accesso alla dashboard del portale</p>
    <div class="row variable-gutters m-t-30">
        <div class="col-md-12 border-bottom mb-3">
            <div class="card-my-platform">
                <a href="javascript:void(0)" class="bullet-platform-online active" title="Loggato su Portale Regione Lombardia" data-toggle="tooltip"></a>
                <a href="/dashboards/d1/index" title="Vai alla dashboard">
                    <div class="img-platform">
                        <img src="/img/rl.png" alt="Portale di Regione Lombardia logo" class="img-fluid">
                    </div>
                    <div class="info-platform">
                        <div class="name-platform"><?= \Yii::$app->name ?></div>
                        <?php
                        $profilo = '';
                        $upc = \open20\amos\admin\models\UserProfileClassesUserMm::findOne(['user_id' => \Yii::$app->user->id]);
                        if (!empty($upc)) {
                            $profile = $upc->userProfileClasses;
                            if (!empty($profile)) {
                                $profilo = $profile->name;
                            }
                        }
                        ?>
                        <div class="ruolo-platform small"><?= $profilo ?></div>
                    </div>

                </a>
            </div>
        </div>
        <hr>
        <div class="col-md-12">
            <div class="card-my-platform disabled">
                <a tabindex="0" href="javascript:void(0)" role="button" data-toggle="popover" data-trigger="focus" title="Open Innovation" data-content="Accesso momentaneamente bloccato" data-placement="top">
                    <div class="img-platform">
                        <img src="/img/portale.jpg" alt="Open Innovation logo" class="img-fluid">
                    </div>
                    <div class="info-platform">
                        <div class="name-platform">Open Innovation</div>
                        <div class="ruolo-platform small">Capo Redattore</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card-my-platform disabled">
                <a tabindex="0" href="javascript:void(0)" role="button" data-toggle="popover" data-trigger="focus" title="Lombardia Informa" data-content="Accesso momentaneamente bloccato per demo" data-placement="top">
                    <div class="img-platform">
                        <img src="/img/li.png" alt="Lombardia Informa logo" class="img-fluid">
                    </div>

                    <div class="info-platform">
                        <div class="name-platform">Lombardia Informa</div>
                        <div class="ruolo-platform small">Redattore</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
</div>

</div>
</div>