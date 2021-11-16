<?php 
use open20\amos\layout\Module;
?>
<div class="it-search-wrapper flexbox">
    <span class="d-none d-md-block text-primary cerca-label"><?= Module::t('amoslayout','Cerca')?></span>
        <a class="search-link rounded-icon " aria-label="<?= Module::t('amoslayout','Cerca')?>" title="<?= Module::t('amoslayout','Vai alla pagina di ricerca della piattaforma') . ' ' . \Yii::$app->name?>" href="<?= $pageSearchLink ?>">
            <span class="dash dash-search "> </span>
        </a>
</div>