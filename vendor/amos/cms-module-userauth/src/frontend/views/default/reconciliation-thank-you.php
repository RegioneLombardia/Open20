<?php

use open20\amos\admin\AmosAdmin;
use amos\userauth\frontend\Module;

$this->title = Module::t( "Rinconciliazione completata");
?>
<div class="container py-5">

    <div class="col-xs-12">
        <h4><?= Module::t("Gentile {nome} {cognome},<br> grazie per aver riconciliato il tuo profilo Open Innovation con la tua Identità Digitale.", [
                'nome' => $model->nome,
                'cognome' => $model->cognome,
            ]) ?>
            <br><br>
            <?= Module::t("Clicca <a href='/'>qui</a> per accedere alla piattaforma") ?>
        </h4>
    </div>
</div>
