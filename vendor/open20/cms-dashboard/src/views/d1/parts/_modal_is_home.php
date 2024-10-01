<?php

use open20\cms\dashboard\Module;

/**
 * @var $modalBody string
 */
?>

<div class="modal" id="modal-is-home" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Module::txt('Conferma') ?></h5>
            </div>
            <div class="modal-body">
                <p><?= $modalBody ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Module::txt('Annulla') ?></button>
                <button type="submit" class="btn btn-primary"><?= Module::txt('Conferma') ?></button>
            </div>
        </div>
    </div>
</div>