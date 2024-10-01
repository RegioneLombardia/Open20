<?php
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\prodottiservizi\assets\ModuleProdottiServiziAsset;


ModuleProdottiServiziAsset::register($this);


?>
<!-- Modal -->
<div class="modal fade" id="accordionUpdateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel"><?= Yii::t('prodottiservizi', 'Aggiorna sezione Accordion') ?></h5>
            </div>
            <div class="modal-body">
                <div id="accordionUpdateModalForm" class="prodotti-servizi-accordion-form col-xs-12 nop">

                    <?php     $form = ActiveForm::begin([
                        'action' => ['update-ajax'],
                        'enableClientValidation' => true,
                        'options' => [
                            'id' => 'prodotti-servizi-update-accordion-form',
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
                                'options' => ['id' => 'isVisibleItemUpdate'.time()]
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
                                    'options' => ['placeholder' => Yii::t('prodottiservizi', 'Inserisci...'), 'id' => 'descriptionUpdate'.time()],
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
                                <?= $form->field($model, 'id')->hiddenInput(['value' => $model->id])->label(false)?>
                                <?= RequiredFieldsTipWidget::widget(); ?>
                            </div>
                            <div class="col-md-4 col xs-12"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('prodottiservizi', 'Close') ?></button>
                <button id="saveUpdateAccordion" type="button" class="btn btn-primary"><?= Yii::t('prodottiservizi', 'Save') ?></button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>