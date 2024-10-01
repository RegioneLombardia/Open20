<?php

use open20\amos\admin\AmosAdmin;
use open20\amos\core\module\BaseAmosModule;

$js = <<< JS
    $(document).on('click', '.linkToCommunityJquery', function(event){ // Click su un link community
        event.preventDefault();
        var link = $(this).attr('href');
        $('#modalConfirmLinkCommunity').modal('show');
        $('#confirmOkModalButton').attr('href', link);
    });
JS;

$this->registerJs($js);
?>

<div id="modalConfirmLinkCommunity" class="modal bootstrap-dialog krajee-amos-modal type-warning fade size-normal in" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="bootstrap-dialog-title"><?= BaseAmosModule::t('amosadmin', 'Confirm'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="display:none">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?= AmosAdmin::t('amosadmin', '#confirm_exit_without_saving'); ?></p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal">
                    <span class="glyphicon glyphicon-ban-circle"> </span>&nbsp;
                    <?= BaseAmosModule::t('amosadmin', 'Annulla'); ?>
                </a>
                <a id="confirmOkModalButton" class="btn btn-primary">
                    <span class="glyphicon glyphicon-ok"> </span>&nbsp;
                    <?= BaseAmosModule::t('amosadmin', 'Ok'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
