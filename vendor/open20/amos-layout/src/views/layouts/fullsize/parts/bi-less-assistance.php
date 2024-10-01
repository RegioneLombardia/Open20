<?php 
use open20\amos\layout\Module;
?>

<div class="bi-assistance z-index-1031">
    <a class="d-flex align-items-center justify-content-center border border-primary" href="<?= $isMail ? 'mailto:' . $mailAddress :  $urlAssistance ?>" data-toggle="tooltip" aria-label="<?= Module::t('amoslayout', 'Hai bisogno di assistenza?') ?>" title="<?= Module::t('amoslayout', 'Hai bisogno di assistenza?') ?>" target="_blank">
      <span class="mdi mdi-help"></span>
      <span class="sr-only"><?= Module::t('amoslayout', 'Verrà aperta una nuova finestra') ?></span>
    </a>
  </div>