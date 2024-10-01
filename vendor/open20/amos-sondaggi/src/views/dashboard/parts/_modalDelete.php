<?php

use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\AmosSondaggi;
use yii\helpers\Html;

?>
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel"><?= AmosSondaggi::t('amossondaggi', 'Eliminazione sondaggio') ?></h5>
            </div>
            <div class="modal-body">
                <?= AmosSondaggi::t('amossondaggi', 'E\' necessario disattivare il sondaggio per procedere con l\'eliminazione. Procedere con la disattivazione del sondaggio?') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" title="<?= AmosSondaggi::t('amossondaggi', 'Annulla') ?>"><?= AmosSondaggi::t('amossondaggi', 'Annulla') ?></button>
                <?= Html::a(
                        AmosIcons::show('minus-circle') . '&nbsp;' . AmosSondaggi::t('amossondaggi', 'Disattiva'),
                        ['/sondaggi/pubblicazione/depubblica',
                            'idSondaggio' => $model->id,
                            'url' => '/sondaggi/dashboard/dashboard?id=' . $model->id
                        ],
                        [
                            'class' => 'btn btn-danger',
                            'title' => AmosSondaggi::t('amossondaggi', 'Disattiva')
                        ]
                ); ?>
            </div>
        </div>
    </div>
</div>