<?php
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\prodottiservizi\assets\ModuleProdottiServiziAsset;


ModuleProdottiServiziAsset::register($this);
?>
<!-- Modal -->
<div class="modal fade" id="accordionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= Yii::t('prodottiservizi', 'Crea nuova sezione Accordion') ?></h5>
            </div>
            <div class="modal-body">
                <div id="accordionModalForm" class="prodotti-servizi-accordion-form col-xs-12 nop">

                    <?php $form = ActiveForm::begin([
                        'enableClientValidation' => true,
                        'options' => [
                            'id' => 'prodotti-servizi-accordion-form'
                        ]
                    ]);
                    ?>
                    <div class="row">
                        <div class="col-xs-9">
                            <?= $form->field($model, 'title')->textInput()?>
                        </div>
                        <div class="col-xs-3">
                            <?= $form->field($model, 'is_visible')->widget(\kartik\widgets\SwitchInput::classname(), [
                                'pluginOptions' => [
                                    'size' => 'normal',
                                    'onColor' => 'success',
                                    //             'offColor' => 'danger',
                                    'onText' => Yii::t('amosdashboards', 'Yes'),
                                    'offText' => Yii::t('amosdashboards', 'No'),
                                ],
                                'options' => ['id' => 'isVisibleItem'.time()]
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <?php
                            $clientOption = [
                                'lang' => substr(Yii::$app->language, 0, 2),
                                'plugins' => 'insertdatetime paste textcolor',
                                'toolbar' => 'fontselect styleselect | fontsizeselect | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',

                            ];
                            $hint = '';

                            echo $form->field($model, 'description')->widget(
                                TextEditorWidget::class,
                                [
                                    'options' => ['placeholder' => Yii::t('prodottiservizi', 'Inserisci...'), 'id' => 'description-'.time()],
                                    'clientOptions' => $clientOption,
                                ]
                            )->hint($hint);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-md-8 col xs-12">
                                <?= $form->field($model, 'prodotto_id')->hiddenInput(['value' => $idProdotto])->label(false)?>
                                <?= RequiredFieldsTipWidget::widget(); ?>
                            </div>
                            <div class="col-md-4 col xs-12"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div id="accordionModalAjaxMessage" style="display: none">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('prodottiservizi', 'Close') ?></button>
                <button id="saveAccordion" type="submit" class="btn btn-primary"><?= Yii::t('prodottiservizi', 'Save') ?></button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>