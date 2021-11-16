<?php

use \open20\amos\core\forms\ActiveForm;

?>
<div class="newsletter-container">
    <?php
    $form = ActiveForm::begin([
        'action' => ['/newsletter/service-email/subscribe-to-group', 'idGroup' => $widget->id_service_mail_group]
    ]);
    ?>
    <?php if (!empty($newsletter) && !$widget->hideNewsletterName) { ?>
        <h3><?= $newsletter->name ?></h3>
    <?php } ?>

    <div class="newsletter-fields">
        <?php if ($widget->enableName) { ?>
            <?php echo $form->field($model, 'name') ?>
        <?php } ?>

        <?php foreach ((Array)$widget->service_mail_dynamic_fields as $id => $name) { ?>
            <?php echo $form->field($model, 'fields[' . $id . ']')->label($name) ?>
        <?php } ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'privacy')->checkbox()
            ->label('<a data-toggle="modal" data-target="#modalPrivacy">' . \amos\newsletter\Module::t('amosnewsletter', 'Visualizza e accetta il documento della privacy') . '</a>')
            ->label("<a  >" . \amos\newsletter\Module::t('amosnewsletter', "Dichiaro di aver letto l'informativa.") . "</a>") ?>


        <div hidden>
            <?= $form->field($model, 'redirect_url')->hiddenInput() ?>
        </div>

        <div class="newsletter-button">
            <?= \yii\helpers\Html::submitButton(\amos\newsletter\Module::t('amosnewsletter', 'Iscriviti'), ['class' => 'btn btn-navigation-primary ' . $widget->subscribeBtnAdditionalClasses]) ?>
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
