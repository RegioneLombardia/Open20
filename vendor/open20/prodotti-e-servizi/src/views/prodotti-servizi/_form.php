<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\prodottiservizi/views 
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\views\DataProviderView;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use open20\amos\core\forms\TextEditorWidget;
use yii\helpers\Inflector;
use open20\prodottiservizi\Module;
use open20\amos\attachments\components\CropInput;
use open20\prodottiservizi\models\ProdottiServiziAccordion;

/**
 * @var yii\web\View $this
 * @var open20\prodottiservizi\models\ProdottiServizi $model
 * @var yii\widgets\ActiveForm $form
 */
$labelCreate = "<span class=\"am am-plus-circle-o\"></span><span>Aggiungi</span>";
$titleCreate = "aggiungi accordion";
$modelClass = get_class($model);
$parameters['class'] = "cta link-create-$modelClass flexbox align-items-center btn btn-xs btn-primary";
$parameters['title'] = $titleCreate;
$urlCreate = Url::to(['prodotti-servizi-accordion-create']);

$buttonAccordion = \yii\helpers\Html::a("<span class=\"am am-plus-circle-o\"></span><span>$labelCreate</span>", $urlCreate, $parameters);
$buttonAccordion = "<button type='button' class='cta link-create-$modelClass flexbox align-items-center btn btn-xs btn-primary get-modal-create' >$labelCreate</button>";

// Modali e container per modali
if (!$model->isNewRecord) echo $this->render('parts/_sportelliModal');
echo '<div id="modalDiv"></div>';
echo '<div id="updateModalDiv"></div>';

$sportelloLoading = Module::t('prodottiservizi', '#SPORTELLOLOADING');
$sportelloOk = Module::t('prodottiservizi', '#SPORTELLOOK');
$sportelloError = Module::t('prodottiservizi', '#SPORTELLOERROR');

$moveOperationCode = [
    ProdottiServiziAccordion::UP_MOVE_OPERATION,
    ProdottiServiziAccordion::DOWN_MOVE_OPERATION
];
$jsAccordion = <<<JS
    const upOperation = '{$moveOperationCode[0]}';
    const downOperation = '{$moveOperationCode[1]}';
    const idProdotto = '{$model->id}';
    
    $(document).on('click', '.move-down', function(e){
        e.preventDefault();
        let idClicked = $(this).attr('data-accordion-id');
        ajaxSwapPositionCall(idClicked, downOperation, idProdotto);
    });

    $(document).on('click', '.move-up', function(e){
        e.preventDefault();
        var idClicked = $(this).attr('data-accordion-id');
        ajaxSwapPositionCall(idClicked, upOperation, idProdotto);
    });

    function ajaxSwapPositionCall(idClicked, operationType) {
        $.ajax({
            url: '/prodotti-e-servizi/prodotti-servizi-accordion/swap-position',
            type: 'get',
            dataType: 'json',
            data: {'idClicked': idClicked, 'operationType': operationType, 'idProdotto': idProdotto},
            success: function (response) {
                if (response.error) {
                    console(response.error.msg);
                } else {
                    $('#accordionDinamici').html(response);console.log(response);
                }
            },
            error: function (response) {
                console.log(response);
            }
        });
    }
    
    $(document).on('click', 'a[data-accordion-update]', function(e){
        e.preventDefault();
        $.ajax({
            url: '/prodotti-e-servizi/prodotti-servizi-accordion/get-accordion-ajax?id=' + $(this).attr('data-accordion-update'),
            type: 'get',
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    console(response.error.msg);
                } else {
                    $('#updateModalDiv').html(response);
                    $('#accordionUpdateModal').modal('show');
                }
            },
            error: function (response) {
                console.log(response);
            }
        });
    });

    $(document).on('click', 'a[data-accordion-delete]', function(e){
        e.preventDefault();
        $.ajax({
            url: '/prodotti-e-servizi/prodotti-servizi-accordion/delete-ajax?id=' + $(this).attr('data-accordion-delete'),
            type: 'get',
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    console(response.error.msg);
                } else {
                    $('#accordionDinamici').html(response);
                }
            },
            error: function (response) {
                console.log(response);
            }
        });
    });

   
    
    $(document).on('click', '.get-modal-create', function(e){
        e.preventDefault();
        $.ajax({
            url: '/prodotti-e-servizi/prodotti-servizi-accordion/get-create-form-ajax?idProdotto=' + idProdotto,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    console(response.error.msg);
                } else {
                    $('#modalDiv').html(response);
                    $('#accordionModal').modal('show');
                }
            },
            error: function (response) {
                console.log(response);
            }
        });
    });
    
    $(document).on('click', '#saveAccordion', function(e){
        e.preventDefault();
        var form = $('#prodotti-servizi-accordion-form');
        form.submit();
    });

    $(document).on('submit', '#prodotti-servizi-accordion-form', function(e){
        e.preventDefault();
        $('#accordionModal').modal('hide');
        var form = $(this);
        $.ajax({
            url: '/prodotti-e-servizi/prodotti-servizi-accordion/create-ajax',
            type: 'get',
            dataType: 'json',
            data: form.serialize(),
            success: function (response) {
                if (response.error) {
                    console(response.error.msg);
                } else {
                    $('#accordionDinamici').html(response);
                }
            },
            error: function (response) {
                console.log(response.error);
            }
        });
    });
    
    $(document).on('submit', '#prodotti-servizi-update-accordion-form', function(e){
        e.preventDefault();
        $('#accordionUpdateModal').modal('hide');
        var form = $('#prodotti-servizi-update-accordion-form');
        $.ajax({
            url: '/prodotti-e-servizi/prodotti-servizi-accordion/update-ajax',
            type: 'get',
            dataType: 'json',
            data: form.serialize(),
            success: function (response) {
                if (response.error) {
                    console(response.error.msg);
                } else {
                    
                    $('#accordionDinamici').html(response);
                }
            },
            error: function (response) {
                console.log(response.error);
                $('#accordionModalAjaxMessage').html('Errore durante il salvataggio');
            }
        });
    });
    
    $(document).on('click', '#saveUpdateAccordion', function(e){
        e.preventDefault();
        var form = $('#prodotti-servizi-update-accordion-form');
        form.submit();
    });
JS;


$jsSportello = <<<JS
    $(document).on('click', '#crea_sportello', function(){
        $.ajax({
            url: '/prodotti-e-servizi/prodotti-servizi/crea-sportello?idProdotto={$model->id}',
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                $('#sportelliModal').find('.modal-body').html('{$sportelloLoading}');
                $('#sportelliModal').modal('show');
            },
            success: function (response) {
                if (response.error) {
                    console(response.error.msg);
                } else {
                    $('#sportelliModal').find('.modal-body').html('{$sportelloOk}');
                }
            },
            error: function (response) {
                console.log(response);
                $('#sportelliModal').find('.modal-body').html('{$sportelloError}');
                //$('#ajax-error-comment-modal-id').modal('show');
            }
        });
    });
JS;

$js = <<< JS
    $(document).on('change', '#parent-ambito', function(){
        var ambito = $(this).val();
        $.ajax({
            url: '/prodotti-e-servizi/prodotti-servizi/get-destinatari-by-ambito',
            data: {'ProdottiDestinatariSearch[parent][0]': ambito},
            type: 'post',
            dataType: 'json',
            success: function (response) {
                var selectDestinatari = $('#destinatari_id');
                selectDestinatari.html(response);
                if(selectDestinatari.find('option').length == 0) {
                    selectDestinatari.prop('disabled', true);
                }
                else {
                    selectDestinatari.prop('disabled', false);
                }
            },
            error: function (response) {
                console.log(response);
            }
        });
    });
JS;

if (!$model->isNewRecord) $this->registerJs($jsSportello, $this::POS_READY);
$this->registerJs($js, $this::POS_READY);
$this->registerJs($jsAccordion, $this::POS_READY);

?>
<div class="prodotti-servizi-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'prodotti-servizi_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>

    <div class="row">
        <div class="m-t-20">
            <div class="content-form-cmsDashSidebarItem">

                <div class="col-xs-12 section-form">
                    <h2 class="subtitle-form"><?= Module::t('prodottiservizi', 'Informazioni di base') ?></h2>
                    <div class="row">
                        <div class="col-xs-12">
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                            <div>
                                <?= $form->field($model, 'icon')->widget(CropInput::class, [
                                    'hidePreviewDeleteButton' => false,
                                    'jcropOptions' => ['aspectRatio' => '1.7']
                                ])->label(Module::t('prodottiservizi', 'Image') . '<span class="text-danger"> *</span>')
                                ?>
                            </div>

                            <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 section-form">
                    <h2 class="subtitle-form"><?= Module::t('prodottiservizi', 'Campi descrittivi') ?></h2>
                    <div class="row">
                        <div class="col-xs-12">
                            <?= $form->field($model, 'description')->widget(
                                TextEditorWidget::class,
                                [
                                    'options' => ['placeholder' => Module::t('prodottiservizi', 'Inserisci...')],
                                    /*
                                    'clientOptions' => [
                                        'lang' => substr(Yii::$app->language, 0, 2),
                                        'plugins' => ['paste link'],
                                        'toolbar' =>'undo redo | link'
                                    ],*/
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 section-form">
                    <h2 class="subtitle-form flexbox" style="justify-content:space-between"><?= Module::t('prodottiservizi', 'Accordion dinamici') ?><?= $buttonAccordion ?></h2>
                    <div id="accordionDinamici" class="row">
                        <?= $this->render('/prodotti-servizi-accordion/fullsize/parts/_dataProviderViewAccordion', [
                            'dataProviderAccordion' => $dataProviderAccordion,
                            'currentViewAccordion' => $currentViewAccordion
                        ]) ?>
                    </div>
                </div>

                <div class="col-xs-12 section-form">
                    <h2 class="subtitle-form"><?= Module::t('prodottiservizi', 'Allegati') ?></h2>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="m-t-30 m-b-30">
                                <?= $form->field($model, 'attachments')->widget(\open20\amos\attachments\components\AttachmentsInput::classname(), [
                                    'options' => [ // Options of the Kartik's FileInput widget
                                        'multiple' => true, // If you want to allow multiple upload, default to false
                                    ],
                                    'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                                        'maxFileCount' => 100, // Client max files
                                        'showPreview' => false
                                    ]
                                ])->label(Module::t('prodottiservizi', 'Documents')) ?>

                                <?=
                                \open20\amos\attachments\components\AttachmentsList::widget([
                                    'model' => $model,
                                    'attribute' => 'attachments',
                                ])
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xs-12 section-form">
                    <?= $form->field($model, 'status')->widget(\kartik\select2\Select2::className(), [
                        'data' => \open20\prodottiservizi\utility\ProdottiServiziUtility::getStati(),
                        'hideSearch' => true,
                        'options' => ['id' => 'status', 'placeholder' => Module::t('prodottiservizi', 'Seleziona Stato')]
                    ]); ?>
                </div>
                <div class="col-xs-12 section-form">
                    <h2 class="subtitle-form"><?= Module::t('prodottiservizi', 'Categorie') ?></h2>
                    <div class="row">
                        <div class="col-xs-12">
                            <?= $form->field($model, 'scope')->widget(\kartik\select2\Select2::className(), [
                                'data' => \open20\prodottiservizi\utility\ProdottiServiziUtility::getAmbiti(),
                                'hideSearch' => true,
                                'options' => ['id' => 'parent-ambito', 'placeholder' => Module::t('prodottiservizi', 'Seleziona Ambito')]
                            ]); ?>

                            <?= $form->field($model, 'solution')->widget(\kartik\select2\Select2::className(), [
                                'data' => $solutions,
                                'hideSearch' => true,
                                'options' => [
                                    'id' => 'solution_id',
                                    'placeholder' => Module::t('prodottiservizi', 'Seleziona Soluzione')
                                ]
                            ]); ?>

                            <?= $form->field($model, 'prodotti_servizi_destinatari_mm')->widget(\kartik\select2\Select2::className(), [
                                'data' => $destinatari,
                                'options' => [
                                    'id' => 'destinatari_id',
                                    'placeholder' => Module::t('prodottiservizi', 'Seleziona Destinatari'),
                                    'multiple' => true,
                                    'disabled' => (count($destinatari) == 0)
                                ]
                            ]); ?>

                            <?= $form->field($model, 'prodotti_servizi_finalita_mm')->widget(\kartik\select2\Select2::className(), [
                                'data' => $finalita,
                                'options' => [
                                    'id' => 'finalita_id',
                                    'placeholder' => Module::t('prodottiservizi', 'Seleziona Finalità'),
                                    'multiple' => true,
                                    'selected' => 'all'
                                ]
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 m-b-30">
                    <?= RequiredFieldsTipWidget::widget(); ?>
                    <div class="clearfix"></div>
                    <?php if (!$model->isNewRecord) : ?>
                        <div class="m-b-30">

                            <button type="button" id="crea_sportello" class="btn btn-tertiary">Crea Sportello</button>
                        </div>
                    <?php endif; ?>
                    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <!--<div class="clearfix"></div>-->
    </div>
</div>