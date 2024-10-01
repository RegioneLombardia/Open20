<?php
use open20\design\Module;
?>
<div class="cookiebar">
    <p><?= Module::t('amosdesign','Questo sito utilizza cookie tecnici, analytics e di terze parti. <br>Proseguendo nella navigazione accetti l’utilizzo dei cookie.')?></p>
    <div class="cookiebar-buttons">
        <a href="<?= ($cookiePolicyLink)?: '/it/cookie-policy' ?>" class="cookiebar-btn"><?= Module::t('amosdesign','Informativa<span class="sr-only">cookies</span>')?></a>
        <button data-accept="cookiebar" class="btn btn-primary ml-4 cookiebar-confirm"><?= Module::t('amosdesign','Accetto<span class="sr-only"> i cookies</span>')?></button>
    </div>
</div>