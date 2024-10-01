<?php
    foreach ($dataProvider->getModels() as $destinatario): ?>
        <button type="button" id="dest-<?= $destinatario->id ?>" class="chip JqueryButtonFilter">
            <span class="mdi mdi-<?= $destinatario->icon ?> icon-chip"></span>
            <span class="chip-label"><?= $destinatario->name ?></span>
        </button>
    <?php endforeach;
?>