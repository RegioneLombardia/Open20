<?php

use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;

?>
<div class="col-xs-12 form-group">
    <div class="col-xs-12">
        <label class="control-label"><?= AmosDocumenti::t('amosdocumenti','Sincronizza cartella con Google Drive')?></label>
    </div>
    <div class="col-xs-12">
        <div class="form-control fake-form-google-drive">
            <span id="drive-folder-filename"><?= AmosDocumenti::t('amosdocumenti','Seleziona la cartella ...')?></span>
            <?= Html::button(AmosIcons::show('google-drive'), ['id' => 'auth', 'class' => 'btn btn-primary']); ?>
        </div>
    </div>
</div>