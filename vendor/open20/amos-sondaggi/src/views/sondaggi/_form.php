<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\sondaggi
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\assets\ModuleSondaggiPublicAsset;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\Sondaggi $model
 * @var yii\widgets\ActiveForm $form
 * @var \open20\amos\cwh\AmosCwh $moduleCwh
 * @var array $scope
 */
ModuleSondaggiPublicAsset::register($this);

$postPublic = 'null';
if (isset($public)) {
    if (strlen($public)) {
        $postPublic = $public;
    }
}
$js = 'var publicPost = \'' . $postPublic . '\';';
$this->registerJs($js, View::POS_BEGIN);

$statusToRenderToHide = $model->getStatusToRenderToHide();

$standardId = \open20\amos\sondaggi\models\base\SondaggiTypes::SONDAGGI_TYPE_STANDARD;
$liveId = \open20\amos\sondaggi\models\base\SondaggiTypes::SONDAGGI_TYPE_LIVE;

$js2 = <<<JS

    var criteri = $('#abilita_criteri_valutazione-id').val();

    if(criteri == 1){
        $('#n_max_valutatori-id').prop('disabled', false);
    } else {
        $('#n_max_valutatori-id').val(0);
        $('#n_max_valutatori-id').prop('disabled', true); 
    }

    $("#abilita_criteri_valutazione-id").change(function() {
        var criterio1 = $('#abilita_criteri_valutazione-id').val();

        if(criterio1 == 1){
            $('#n_max_valutatori-id').prop('disabled', false);
        } else {
            $('#n_max_valutatori-id').val(0);
            $('#n_max_valutatori-id').prop('disabled', true);
        }
    });

    var front = $('#sondaggi-frontend').val();
    if(front == 1){
        $('#sondaggi-thank_you_page').prop('disabled', false);
        $('#sondaggi-forza_lingua').prop('disabled', false);
        $('#sondaggi-abilita_registrazione').prop('disabled', false);
        $('#sondaggi-url_sondaggio_non_compilabile').prop('disabled', false);
        $('#compilabile_in_frontend').show();
        $('#no-frontend').hide();
        $('#si-frontend').show();
    } else {
        $('#sondaggi-thank_you_page').prop('disabled', true);
        $('#sondaggi-forza_lingua').prop('disabled', true);
        $('#sondaggi-abilita_registrazione').prop('disabled', true);
        $('#sondaggi-url_sondaggio_non_compilabile').prop('disabled', true);
        $('#compilabile_in_frontend').hide();
        $('#no-frontend').show();
        $('#si-frontend').hide();
    }

    var confermaCommunity = $("#sondaggi-mail_conf_community").val();
    if(confermaCommunity == 1){
        $("#mail_custom-conferma-community").show();
    } else {
        $("#mail_custom-conferma-community").hide();
    }

    $("#sondaggi-mail_conf_community").change(function() {
        var confermaCommunity = $("#sondaggi-mail_conf_community").val();
        if(confermaCommunity == 1){
            $("#mail_custom-conferma-community").show();
        } else {
            $("#mail_custom-conferma-community").hide();
        }
    });
    
    var mailNewAccountCustom = $("#sondaggi-mail_registrazione_custom").val();
    if(mailNewAccountCustom == 1){
        $("#mail_custom-nuovi-utenti").show();
    } else {
        $("#mail_custom-nuovi-utenti").hide();
    }

    $("#sondaggi-mail_registrazione_custom").change(function() {
    var mailNewAccountCustom = $("#sondaggi-mail_registrazione_custom").val();
        if(mailNewAccountCustom == 1){
            $("#mail_custom-nuovi-utenti").show();
        } else {
            $("#mail_custom-nuovi-utenti").hide();
        }
    });

    $('#sondaggi-frontend').change(function() {
        var front = $('#sondaggi-frontend').val();
        if(front == 1){
            $('#sondaggi-thank_you_page').prop('disabled', false);
            $('#sondaggi-forza_lingua').prop('disabled', false);
            $('#sondaggi-abilita_registrazione').prop('disabled', false);
            $('#sondaggi-url_sondaggio_non_compilabile').prop('disabled', false);
            $('#compilabile_in_frontend').show();
            $('#no-frontend').hide();
            $('#si-frontend').show();
        } else {
            $('#sondaggi-thank_you_page').prop('disabled', true);
            $('#sondaggi-forza_lingua').prop('disabled', true);
            $('#sondaggi-abilita_registrazione').prop('disabled', true);
            $('#sondaggi-url_sondaggio_non_compilabile').prop('disabled', true);
            $('#compilabile_in_frontend').hide();
            $('#no-frontend').show();
            $('#si-frontend').hide();
        }
   });
    
    $('#sondaggio-type-id').on('select2:select', function(){
        hideShowSondaggiLive($(this).val());
    });
    
    
    function hideShowSondaggiLive(sondaggiotype){
          if(sondaggiotype == '$liveId'){
            $('.container-not-live').hide();
            $('.container-live').show();
        }else {
            $('.container-not-live').show();
            $('.container-live').hide();
        }
    }
    
    hideShowSondaggiLive($('#sondaggio-type-id').val());
JS;

$this->registerJs($js2, View::POS_READY);

$sondaggi_types = (new open20\amos\sondaggi\models\base\SondaggiTypes())->types;
?>

<?php
$form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]);
?>

<?=
WorkflowTransitionStateDescriptorWidget::widget([
    'form' => $form,
    'model' => $model,
    'workflowId' => Sondaggi::WORKFLOW,
    'classDivIcon' => '',
    'classDivMessage' => 'message',
    'viewWidgetOnNewRecord' => false
]);
?>
<div class="row">

<div class="sondaggi-form col-xs-12">
    <?php $this->beginBlock('generale'); ?>
    <div class="row">
        <div class="col-sm-7">
            <?php
            $textTootlip = AmosSondaggi::t('amossondaggi', "<strong>Completo</strong>: sondaggio completo con tutte le tipologie di risposta  <br> <strong>Live</strong>:  sondaggio live che prevede una sola domanda con risposte a insieme chiuso");
            $icon = " <span  data-toggle=\"tooltip\" data-html=\"true\" data-placement=\"top\" title=\"$textTootlip\">" . \open20\amos\core\icons\AmosIcons::show('info') . "</span>"; ?>
            <?=
            $form->field($model, 'sondaggio_type')->widget(\kartik\select2\Select2::className(), [
                'data' => $sondaggi_types,
                'options' => [
                    'id' => 'sondaggio-type-id',
                    'placeholder' => AmosSondaggi::t('amossondaggi', "Seleziona...")
                ]
            ])->label(AmosSondaggi::t('amossondaggi', "Tipologia sondaggio") . $icon);
            ?>
            <?= $form->field($model, 'titolo')->textarea(['rows' => 2]) ?>
            <?= $form->field($model, 'visualizza_solo_titolo')->checkbox() ?>
            <?= $form->field($model, 'descrizione')->textarea(['rows' => 4]) ?>

        </div>

        <div class="col-sm-5">
           
                <?=
                $form->field($model, 'file')->widget(AttachmentsInput::classname(),
                    [
                        'options' => [// Options of the Kartik's FileInput widget
                            'multiple' => false, // If you want to allow multiple upload, default to false
                            'accept' => "image/*"
                        ],
                        'pluginOptions' => [// Plugin options of the Kartik's FileInput widget
                            'maxFileCount' => 1,
                            'showRemove' => false, // Client max files,
                            'indicatorNew' => false,
                            'allowedPreviewTypes' => ['image'],
                            'previewFileIconSettings' => false,
                            'overwriteInitial' => false,
                            'layoutTemplates' => false
                        ]
                    ])->label(AmosSondaggi::t('amossondaggi', 'Immagine'))
                ?>
           
        </div>
    </div>

    <div class="row container-live" style="display:none">
    <div class="col-xs-12">
    <h5><?= AmosSondaggi::t('amossondaggi', "Configurazioni sondaggi live") ?></h5>
    </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'begin_date_hour_live')->widget(\kartik\datecontrol\DateControl::className(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'end_date_hour_live')->widget(\kartik\datecontrol\DateControl::className(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME
            ]) ?>
        </div>
        <div class="col-md-6">
            <?php if (empty($model->graphics_live)) {
                $model->graphics_live = Sondaggi::SONDAGGI_LIVE_CHART_PIE;
            } ?>
            <?= $form->field($model, 'graphics_live')->widget(\kartik\select2\Select2::className(), [
                'data' => Sondaggi::sondaggiLiveTypeCharts(),

            ])->label(AmosSondaggi::t('amossondaggi', "Grafico di sintesi da mostare")) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'how_show_live')->widget(\kartik\select2\Select2::className(), [
                'data' => [
                    '1' => 'Dopo compilazione',
                    '2' => 'Sempre',
                ]

            ])->label(AmosSondaggi::t('amossondaggi', "Mostra grafico andamento sondaggio"))?>
        </div>
       
    </div>
    <hr style="border-style:dashed" class="m-t-20 m-b-20">
    <div class="row">
        <div class="col-sm-6">
            <!--                <label>Opzioni</label>-->
            <?= $form->field($model, 'send_pdf_via_email')->checkbox() ?>
        </div>
        <div class="col-sm-12">
            <?=
            $form->field($model, 'additional_emails')->textarea(['placeholder' => 'email1@example.it; email2@example.it; email3@example.it'])
                ->label(AmosSondaggi::t('amossondaggi', 'Elenco di email a cui verrà inviato il sondaggio compilato.'))
            ?>
        </div>

        <div class="col-md-6 container-not-live">
            <?= $form->field($model, 'url_chiudi_sondaggio')->textInput() ?>
        </div>
        <div class="col-lg-6 col-sm-6 container-not-live">
            <?= $form->field($model, 'compilazioni_disponibili')->textInput() ?>
        </div>
        <?php
        if (!AmosSondaggi::instance()->forceOnlyFrontend) { // Se non è settata l'intenzione di forzare il campo frontend forzatamente a 1
            if (AmosSondaggi::instance()->enableFrontendCompilation) {
                ?>
                <div class="col-lg-6 col-sm-6 container-not-live">
                    <?=
                    $form->field($model, 'frontend')->dropDownlist([0 => AmosSondaggi::t('amossondaggi', 'No'), 1 => AmosSondaggi::t('amossondaggi',
                            'Si')])
                    ?>
                </div>
                <div class="col-md-6 container-not-live">
                    <?=
                    $form->field($model, 'sondaggio_chiuso_frontend')->dropDownlist([0 => AmosSondaggi::t('amossondaggi',
                            'No'),
                        1 => AmosSondaggi::t('amossondaggi', 'Si')])
                    ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <div class="row" id="compilabile_in_frontend">
        <div class="col-md-6">
            <?= $form->field($model, 'thank_you_page')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'url_sondaggio_non_compilabile')->textInput() ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'thank_you_page_sondaggio_chiuso')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'abilita_registrazione')->dropDownlist([0 => AmosSondaggi::t('amossondaggi', 'No'),
                1 => AmosSondaggi::t('amossondaggi', 'Si')])
            ?>
        </div>
        <div class="col-md-6">
            <?php
            $moduleTranslation = \Yii::$app->getModule('translation');
            //$languages         = ['it-IT' => 'IT', 'en-GB' => 'EN'];

            if (!empty($moduleTranslation)) {
                $arrLanguage = (new \yii\db\Query())->from('language')->andWhere(['=', 'status', 1])->select(['language_id',
                    'name'])->all();
                foreach ($arrLanguage as $k => $v) {
                    $languages[$v['language_id']] = $v['name'];
                }
            }
            ?>
            <?=
            $form->field($model, 'forza_lingua')->dropDownlist($languages,
                ['prompt' => AmosSondaggi::t('amossondaggi', 'Non forzare. Usa quella dell\'utente')]);
            ?>
        </div>
        <?php if ($sondaggiModule->enableForceLanguageByGet) { ?>
        <div class="col-md-6">
        <?=
            $form->field($model, 'use_get_language')->dropDownlist([0 => AmosSondaggi::t('amossondaggi', 'No'),
                1 => AmosSondaggi::t('amossondaggi', 'Si')])
            ?>
        </div>
        <?php } ?>
        <?php if ($sondaggiModule->enableExtraFieldByGet) { ?>
        <div class="col-md-6">
            <?=
            $form->field($model, 'field_extra')->dropDownlist([0 => AmosSondaggi::t('amossondaggi', 'No'),
                1 => AmosSondaggi::t('amossondaggi', 'Si')])
            ?>
        </div>
        <?php } ?>
    </div>

    <div class="row container-not-live" style="display:none">
        <?php if ($sondaggiModule->enableCriteriValutazione) { ?>
            <div class="col-md-6">
                <?=
                $form->field($model, 'abilita_criteri_valutazione')->dropDownlist([0 => AmosSondaggi::t('amossondaggi',
                        'No'),
                    1 => AmosSondaggi::t('amossondaggi', 'Si')], ['id' => 'abilita_criteri_valutazione-id'])
                ?>
            </div>
        <?php } ?>
        <div class="col-md-6" id="div_max_valutatori-id">
            <?= $form->field($model, 'n_max_valutatori')->textInput(['id' => 'n_max_valutatori-id']) ?>
        </div>
    </div>


    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>
    <?php
    $itemsTab[] = [
        'label' => AmosSondaggi::tHtml('amossondaggi', 'Generale'),
        'content' => $this->blocks['generale'],
    ];
    ?>

    <?php $this->beginBlock('messaggi'); ?>
    <div id="no-frontend">
        <div class="container-not-live">
            <div class="row">
                <div class="col-lg-12">
                    <?=
                    $form->field($model, 'text_not_compilable_html')->dropDownlist([0 => AmosSondaggi::t('amossondaggi',
                        'NO'), 1 => AmosSondaggi::t('amossondaggi', 'SI')])
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12" id="areatext-not-compilable">
                    <?=
                    $form->field($model, 'text_not_compilable')->textarea(['rows' => 4])
                    ?>
                </div>
                <div class="col-lg-12" id="htmltext-not-compilable">
                    <?=
                    $form->field($model, 'text_not_compilable')->widget(TextEditorWidget::className(),
                        [
                            'clientOptions' => [
                                'placeholder' => AmosSondaggi::t('amossondaggi', '#insert_text'),
                                'lang' => substr(Yii::$app->language, 0, 2)
                            ],
                            'options' => [
                                'id' => 'text_not_compilable-id-html',
                            ]
                        ])
                    ?>
                </div>
            </div>
        </div>
        <div class="row container-not-live">
            <div class="col-lg-12">
                <?=
                $form->field($model, 'text_end_html')->dropDownlist([0 => AmosSondaggi::t('amossondaggi', 'NO'), 1 => AmosSondaggi::t('amossondaggi',
                    'SI')])
                ?>
            </div>
        </div>

        <div class="row container-not-live">
            <div class="col-lg-12">
                <?= $form->field($model, 'text_end_title')->textinput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" id="areatext-end">
                <?= $form->field($model, 'text_end')->textarea(['rows' => 4]) ?>
            </div>
            <div class="col-lg-12" id="htmltext-end">
                <?=
                $form->field($model, 'text_end')->widget(TextEditorWidget::className(),
                    [
                        'clientOptions' => [
                            'placeholder' => AmosSondaggi::t('amossondaggi', '#insert_text'),
                            'lang' => substr(Yii::$app->language, 0, 2)
                        ],
                        'options' => [
                            'id' => 'text_end-id-html',
                        ]
                    ])
                ?>
            </div>
        </div>
    </div>


    <div id="si-frontend">
        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'link_landing_page')->textinput() ?>
            </div>
            <div class="col-lg-12">
                <?=
                $form->field($model, 'testo_sondaggio_non_compilabile_front')->textarea(['rows' => 3])
                ?>
            </div>
            <div class="col-lg-12">
                <?=
                $form->field($model, 'titolo_fine_sondaggio_front')->textinput()
                ?>
            </div>
            <div class="col-lg-12">
                <?=
                $form->field($model, 'testo_fine_sondaggio_front')->textarea(['rows' => 3])
                ?>
            </div>
            <div class="col-lg-6">
                <?=
                $form->field($model, 'mail_registrazione_custom')->dropDownList([0 => AmosSondaggi::t('amossondaggi',
                    'No'), 1 => AmosSondaggi::t('amossondaggi', 'Si')])
                ?>
            </div>
            <div class="col-lg-6">
                <?=
                $form->field($model, 'mail_conf_community')->dropDownList([0 => AmosSondaggi::t('amossondaggi', 'No'), 1 => AmosSondaggi::t('amossondaggi',
                    'Si')])
                ?>
            </div>
        </div>
        <div class="row">
            <h2><?= AmosSondaggi::t('amossondaggi', 'E-mail degli eventi') ?></h2>
            <h3><?= AmosSondaggi::t('amossondaggi', 'Messaggio inviato al completamento del sondaggio') ?></h3>
            <strong><?= AmosSondaggi::t('amossondaggi', 'Caso 1: nuovo utente') ?></strong>
            <div class="col-lg-12">
                <?= $form->field($model, 'mail_mittente_nuovo_utente')->textinput() ?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'mail_soggetto_nuovo_utente')->textinput() ?>
            </div>
            <div class="col-lg-12">
                <?=
                $form->field($model, 'mail_contenuto_nuovo_utente')->textarea(['rows' => 4])->label(AmosSondaggi::t('amossondaggi',
                        'Testo') . ' ' . AmosSondaggi::t('amossondaggi',
                        '(variabili comprese: {{{link}}} - {{{link_esteso}}} - {{{nome}}} - {{{cognome}}})'))
                ?>
            </div>
        </div>
        <div class="row">
            <strong><?= AmosSondaggi::t('amossondaggi', 'Caso 2: utente già registrato') ?></strong>
            <div class="col-lg-12">
                <?= $form->field($model, 'mail_mittente_utente_presente')->textinput() ?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'mail_soggetto_utente_presente')->textinput() ?>
            </div>
            <div class="col-lg-12">
                <?=
                $form->field($model, 'mail_contenuto_utente_presente')->textarea(['rows' => 4])->label(AmosSondaggi::t('amossondaggi',
                        'Testo') . ' ' . AmosSondaggi::t('amossondaggi',
                        '(variabili disponibili: {{{link}}} - {{{link_esteso}}} - {{{nome}}} - {{{cognome}}})'))
                ?>
            </div>
        </div>
        <div class="row" id="mail_custom-nuovi-utenti">

            <h3><?= AmosSondaggi::t('amossondaggi', 'E-mail personalizzata per le nuove iscrizioni') ?></h3>
            <div class="col-lg-12">
                <?= $form->field($model, 'mail_registrazione_mittente')->textinput() ?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'mail_registrazione_soggetto')->textinput() ?>
            </div>
            <div class="col-lg-12">
                <?=
                $form->field($model, 'mail_registrazione_corpo')->textarea(['rows' => 4])
                ?>
            </div>
        </div>
        <div class="row" id="mail_custom-conferma-community">

            <h3><?=
                AmosSondaggi::t('amossondaggi',
                    'E-mail inviata al primo accesso alla piattaforma per gli utenti registrati alla community collegata')
                ?></h3>
            <div class="col-lg-12">
                <?= $form->field($model, 'mail_conf_community_id')->textinput() ?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'mail_conf_community_mittente')->textinput() ?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'mail_conf_community_soggetto')->textinput() ?>
            </div>
            <div class="col-lg-12">
                <?=
                $form->field($model, 'mail_conf_community_corpo')->textarea(['rows' => 4])
                ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <?php $this->endBlock(); ?>
    <?php
    if (\Yii::$app->user->can('ADMIN')) {
        $itemsTab[] = [
            'label' => AmosSondaggi::tHtml('amossondaggi', 'Messaggi '),
            'content' => $this->blocks['messaggi'],
        ];
    }
    ?>
    <?php
    if ($this->context->module->enableNotificationEmailByRoles) {
        $this->beginBlock('notifica');
        ?>
        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'mail_subject')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?=
                $form->field($model, 'mail_message')->widget(TextEditorWidget::className(),
                    [
                        'clientOptions' => [
                            'placeholder' => AmosSondaggi::t('amossondaggi', '#insert_text'),
                            'lang' => substr(Yii::$app->language, 0, 2)
                        ]
                    ])
                ?>
            </div>
        </div>
        <div class="clearfix"></div>

        <?php $this->endBlock(); ?>
        <?php
        $itemsTab[] = [
            'label' => AmosSondaggi::tHtml('amossondaggi', 'Notifica '),
            'content' => $this->blocks['notifica'],
        ];
    }
    ?>

    <?php if (!empty($moduleCwh)): ?>
        <?php $this->beginBlock('survey_recipients'); ?>
        <div class="">
            <div class="col-xs-12 receiver-section">
                <?=
                \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
                    'model' => $model,
                    'moduleCwh' => $moduleCwh,
                    'scope' => $scope
                ]);
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php $this->endBlock(); ?>
        <?php
        $itemsTab[] = [
            'label' => AmosSondaggi::tHtml('amossondaggi', '#recipients'),
            'content' => $this->blocks['survey_recipients'],
        ];
        ?>
    <?php endif; ?>

    <?=
    Tabs::widget([
        'encodeLabels' => false,
        'items' => $itemsTab,
        'hideCwhTab' => true,
        'hideTagsTab' => true,
    ]);
    ?>

    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>

    <?=
    WorkflowTransitionButtonsWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => Sondaggi::WORKFLOW,
        'viewWidgetOnNewRecord' => true,
        'closeButton' => Html::a(AmosSondaggi::t('amossondaggi', 'Annulla'), \Yii::$app->request->referrer,
            ['class' => 'btn btn-secondary']),
        'initialStatusName' => "BOZZA",
        'initialStatus' => Sondaggi::WORKFLOW_STATUS_BOZZA,
        'statusToRender' => $statusToRenderToHide['statusToRender'],
        //gli utenti validatore/facilitatore o ADMIN possono sempre salvare il sondaggio => parametro a false altrimenti se stato VALIDATO => pulsante salva nascosto
        'hideSaveDraftStatus' => $statusToRenderToHide['hideDraftStatus'],
        'draftButtons' => [
            Sondaggi::WORKFLOW_STATUS_DAVALIDARE => [
                'button' => Html::submitButton(AmosSondaggi::t('amossondaggi', 'Salva'), ['class' => 'btn btn-workflow']),
                'description' => 'le modifiche e mantieni il sondaggio in "richiesta di pubblicazione"'
            ],
//            Sondaggi::WORKFLOW_STATUS_VALIDATO => [
//                'button' => Html::submitButton(AmosSondaggi::t('amossondaggi', 'Salva'), ['class' => 'btn btn-workflow']),
//                'description' => AmosSondaggi::t('amossondaggi', 'le modifiche e mantieni il sondaggio "pubblicato"'),
//            ],
            'default' => [
                'button' => Html::submitButton(AmosSondaggi::t('amossondaggi', 'Salva in bozza'),
                    ['class' => 'btn btn-workflow']),
                'description' => AmosSondaggi::t('amossondaggi', 'potrai richiedere la pubblicazione in seguito'),
            ]
        ]
    ]);
    ?>

    <?php ActiveForm::end(); ?>
</div>
</div>