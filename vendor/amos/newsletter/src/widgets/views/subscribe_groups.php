<?php

use \open20\amos\core\forms\ActiveForm;

?>
<div class="newsletter-container">
    <?php
    $form = ActiveForm::begin([
        'action' => ['/newsletter/service-email/subscribe-to-array-group', 'idGroup' => $model->idgroups, 'enableClientValidation' => true]
    ]);
    ?>

    <div class="newsletter-fields">
        <?php if ($widget->enableName) { ?>
            <?php echo $form->field($model, 'name') ?>
        <?php } ?>

        <?php echo $form->field($model, 'campo1')->label('Nome') ?>
        <?php echo $form->field($model, 'campo2')->label('Cognome') ?>
        <?php echo $form->field($model, 'campo5')->label('Provincia') ?>
        <?php echo $form->field($model, 'campo3')->label('Azienda') ?>
        <?php echo $form->field($model, 'ruolo')->label('Ruolo') ?>
        <?= $form->field($model, 'email') ?>

        <div class="clearfix"></div>
         <br />

        <div class="col-xs-12 col-md-6 newsletterscontainer">
            <h2>Scegli la newsletter</h2>
			<?= $form->field($model, 'idgroups')->checkboxList([
			    '136' => 'Istituzionale',
			    '80'=>'Foreste di Lombardia',
                '44'=>'Servizio Fitosanitario',
                '7' => 'Bollettino Nitrati',
                '20' => 'LIFE HelpSoil',
//                '0' => 'Contratti di Fiume'
            ])->label(false) ?>
                <!--input type="checkbox" name="idGroups[]" value="< ?=$widget->id_newsletter_group['foreste']?>" /> Foreste di Lombardia <br />
                <input type="checkbox" name="idGroups[]" value="< ?=$widget->id_newsletter_group['fitosanitario']?>" required/> Servizio Fitosanitario<br />
                <input type="checkbox" name="idGroups[]" value="< ?=$widget->id_newsletter_group['nitrati']?>" /> Bollettino Nitrati <br />
                <input type="checkbox" name="idGroups[]" value="< ?=$widget->id_newsletter_group['helpSoil']?>" required/> LIFE HelpSoil <br />
                <input type="checkbox" name="idGroups[]" value="< ?=$widget->id_newsletter_group['fiume']?>" /> Contratti di Fiume <br /-->
        </div>

        <div id="nl" class="col-xs-12 col-md-6 tematichecontainer">
            <h2>Ti interessa anche</h2>
                <input type="checkbox" name="tematiche[]" value="Territorio" /> Territorio <br />
                <input type="checkbox" name="tematiche[]" value="Politiche per la montagna" /> Politiche per la montagna <br />
                <input type="checkbox" name="tematiche[]" value="Foreste Alpeggi e Aree Protette" /> Foreste Alpeggi e Aree Protette <br />
                <input type="checkbox" name="tematiche[]" value="Agricoltura e Zootecnia" /> Agricoltura e Zootecnia <br />
                <input type="checkbox" name="tematiche[]" value="Ricerca e innovazione" /> Ricerca e innovazione <br />
        </div>
        <br />

        <div class="clearfix"></div>

        <div hidden>
            <?= $form->field($model, 'fields[28]')->hiddenInput(['id' => 'tematichediinteresse']) ?>
        </div>
        <div hidden>
            <?= $form->field($model, 'fields[29]')->hiddenInput(['id' => 'ruolo']) ?>
        </div>
        <div hidden>
            <?= $form->field($model, 'redirect_url')->hiddenInput(['value' => $widget->redirect_url]) ?>
        </div>
        <div hidden>
            <?= $form->field($model, 'confirmEmail')->hiddenInput(['value' => $widget->confirmEmail]) ?>
        </div>
        <hr>
        <div class="col-xs-12">
            <?= $form->field($model, 'privacy')->checkbox()
                ->label('<a href="/it/privacy" target="_blank">' . \amos\newsletter\Module::t('amosnewsletter', 'Visualizza e accetta il documento della privacy') . '</a>')
                ->label("<a  >" . \amos\newsletter\Module::t('amosnewsletter', "Dichiaro di aver letto l'informativa.") . "</a>") ?>

            <div class="newsletter-button">
                <?= \yii\helpers\Html::submitButton(\amos\newsletter\Module::t('amosnewsletter', 'Iscriviti'), ['class' => 'btn btn-navigation-primary']) ?>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

    <div class="modal fade" id="modalPrivacy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"><?= \Yii::t('amosadmin', '#privacy_label') ?></h4>
                </div>
                <div class="modal-body">
                    <?= $this->render($widget->pathPrivacy); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= \Yii::t('amosadmin', '#close') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
