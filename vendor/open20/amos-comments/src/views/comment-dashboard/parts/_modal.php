<?php

use open20\amos\comments\AmosComments;

?>


<div class="modal modal-comments-moderation" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= AmosComments::t('amoscomments', 'Conferma') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?= AmosComments::t('amoscomments', 'Attenzione! Tutti i commenti attualmente in attesa di moderazione saranno automaticamente pubblicati') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= AmosComments::t('amoscomments', 'Annulla') ?></button>
                <button type="button" class="btn btn-primary" id="modalConfirmBtn"><?= AmosComments::t('amoscomments', 'Conferma') ?></button>
            </div>
        </div>
    </div>
</div>