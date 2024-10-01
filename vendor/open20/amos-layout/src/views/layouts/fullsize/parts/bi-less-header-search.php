<?php

use open20\amos\layout\Module;
?>
<div class="it-search-wrapper flexbox">
    <span class="d-none d-md-block text-primary cerca-label"><?= Module::t('amoslayout', 'Cerca') ?></span>
    <a class="search-link rounded-icon " aria-label="<?= Module::t('amoslayout', 'Cerca') ?>" title="<?= Module::t('amoslayout', 'Vai alla pagina di ricerca della piattaforma') . ' ' . \Yii::$app->name ?>" href="<?= $pageSearchLink ?>">
        <svg class="icon" viewBox="0 0 24 24" id="it-search" xmlns="http://www.w3.org/2000/svg">
            <path d="M21.9 21.1L16 15.3a8.3 8.3 0 002-5.3 8 8 0 10-8 8 8.3 8.3 0 005.3-2l5.8 5.9zM10 17a7 7 0 117-7 7 7 0 01-7 7z" />
            <path fill="none" d="M0 0h24v24H0z" />
        </svg>
    </a>
</div>