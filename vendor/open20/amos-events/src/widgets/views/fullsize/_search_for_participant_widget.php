<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\EventAccreditationList;
use kartik\widgets\DatePicker;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\search\EventSearch $model
 * @var yii\widgets\ActiveForm $form
 * @var string $container
 * @var string pjaxId
 * @var open20\amos\events\models\Event $event
 * @var string|array $resetUrl
 * @var array $searchParamsArray
 */

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();
/** @var EventAccreditationList $eventAccreditationListModel */
$eventAccreditationListModel = $eventsModule->createModel('EventAccreditationList');

$fieldData = $container . "-search";
$searchContainerName = $fieldData . "-container";

$encodeEventTitle = htmlentities($event->title);

// NOTA:
// style > display:none; serve per tenere nascosta di default il container
?>
<div class="event-search" style="<?= ((array_key_exists('searchOpen', $searchParamsArray) && !empty($searchParamsArray['searchOpen'])) ? '' : 'display:none;'); ?>" id="<?= $searchContainerName; ?>">

    <?php
    $form = ActiveForm::begin([
        'action' => $resetUrl,
        'id' => $searchContainerName.'-form',
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]);
    ?>

    <h2><?= AmosEvents::txt("Cerca per:"); ?></h2>

    <?= Html::hiddenInput($fieldData . '[searchOpen]', 1); ?>

    <div class="col-md-4">
        <div class="form-group field-partecipantisearch-nomecognome">
            <label class="control-label" for="partecipantisearch-nomecognome"><?= AmosEvents::txt('Nome e cognome'); ?></label>
            <?= Html::input('string', $fieldData . '[nomeCognome]', ((array_key_exists('nomeCognome', $searchParamsArray) && !empty($searchParamsArray['nomeCognome'])) ? $searchParamsArray['nomeCognome'] : null), ['id' => 'partecipantisearch-nomecognome', 'class' => 'form-control']); ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group field-partecipantisearch-azienda">
            <label class="control-label" for="partecipantisearch-azienda"><?= AmosEvents::txt('#participant_azienda'); ?></label>
            <?= Html::input('string', $fieldData . '[azienda]', ((array_key_exists('azienda', $searchParamsArray) && !empty($searchParamsArray['azienda'])) ? $searchParamsArray['azienda'] : null), ['id' => 'partecipantisearch-azienda', 'class' => 'form-control']); ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group field-partecipantisearch-listaaccreditamento">
            <label class="control-label" for="partecipantisearch-listaaccreditamento"><?= AmosEvents::txt('Accreditation list'); ?></label>
            <?php
            $accreditationTypesModels = $eventAccreditationListModel::find()->andWhere(['event_id' => $event->id])->orderBy('position ASC')->all();
            $accreditationTypes = [
                null => AmosEvents::txt("Non selezionato"),
                '_WITHOUTACCREDITATIONLIST' => AmosEvents::txt("Senza lista di accreditamento")
            ];
            foreach ($accreditationTypesModels as $atModel) {
                $accreditationTypes[$atModel->id] = $atModel->title;
            }

            echo Select::widget([
                'auto_fill' => true,
                'hideSearch' => true,
                'theme' => 'bootstrap',
                'data' => $accreditationTypes,
                'name' => $fieldData . '[listaAccreditamento]',
                //'model' => $invitation,
                //'attribute' => 'accreditation_list_id',
                /*'value' => isset($accreditationTypes[$invitation->accreditation_list_id]) ? AmosEvents::txt($accreditationTypes[$invitation->accreditation_list_id])
                    : null,*/
                'value' => ((array_key_exists('listaAccreditamento', $searchParamsArray) && $searchParamsArray['listaAccreditamento'] != '') ? $searchParamsArray['listaAccreditamento'] : null),
                'options' => [
                    //                                    'prompt' => AmosCommunity::t('amoscommunity', 'Select') . '...',
                    'disabled' => false,
                    //'id' => $selectId
                    'id' => 'partecipantisearch-listaaccreditamento',
                    'class' => 'form-control'
                ],
                'pluginOptions' => [
                    'allowClear' => false,
                ]
            ])
            ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group field-partecipantisearch-bigliettospedito">
            <label class="control-label" for="partecipantisearch-bigliettospedito"><?= AmosEvents::txt('Tickets sent'); ?></label>
            <?php
            echo Select::widget([
                'auto_fill' => true,
                'hideSearch' => true,
                'theme' => 'bootstrap',
                'data' => [
                        null => AmosEvents::txt("Non selezionato"),
                        0 => Yii::t('amoscore', 'No'),
                        1 => Yii::t('amoscore', 'Yes'),
                ],
                'name' => $fieldData . '[bigliettoSpedito]',
                /*'value' => isset($accreditationTypes[$invitation->accreditation_list_id]) ? AmosEvents::txt($accreditationTypes[$invitation->accreditation_list_id])
                    : null,*/
                'value' => ((array_key_exists('bigliettoSpedito', $searchParamsArray) && $searchParamsArray['bigliettoSpedito'] != '') ? $searchParamsArray['bigliettoSpedito'] : null),
                'options' => [
                    //                                    'prompt' => AmosCommunity::t('amoscommunity', 'Select') . '...',
                    'disabled' => false,
                    //'id' => $selectId
                    'id' => 'partecipantisearch-bigliettospedito',
                    'class' => 'form-control'
                ],
                'pluginOptions' => [
                    'allowClear' => false,
                ]
            ])
            ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group field-partecipantisearch-presenza">
            <label class="control-label" for="partecipantisearch-presenza"><?= AmosEvents::txt('Attendant'); ?></label>
            <?php

            echo Select::widget([
                'auto_fill' => true,
                'hideSearch' => true,
                'theme' => 'bootstrap',
                'name' => $fieldData . '[presenza]',
                'data' => [
                    null => AmosEvents::txt("Non selezionato"),
                    0 => Yii::t('amoscore', 'No'),
                    1 => Yii::t('amoscore', 'Yes'),
                ],
                /*'value' => isset($accreditationTypes[$invitation->accreditation_list_id]) ? AmosEvents::txt($accreditationTypes[$invitation->accreditation_list_id])
                    : null,*/
                'value' => ((array_key_exists('presenza', $searchParamsArray) && $searchParamsArray['presenza'] != '') ? $searchParamsArray['presenza'] : null),
                'options' => [
                    //                                    'prompt' => AmosCommunity::t('amoscommunity', 'Select') . '...',
                    'disabled' => false,
                    //'id' => $selectId
                    'id' => 'partecipantisearch-presenza',
                    'class' => 'form-control'
                ],
                'pluginOptions' => [
                    'allowClear' => false,
                ]
            ])
            ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group field-partecipantisearch-scaricatoil">
            <label class="control-label" for="partecipantisearch-scaricatoil"><?= AmosEvents::txt('Ticket downloaded at'); ?></label>
            <?= DatePicker::widget([
                    'name' => $fieldData . '[scaricatoIl]',
                    'value' => ((array_key_exists('scaricatoIl', $searchParamsArray) && !empty($searchParamsArray['scaricatoIl'])) ? $searchParamsArray['scaricatoIl'] : null),
                    'options' => [
                        'id' => 'partecipantisearch-scaricatoil',
                        'class' => 'form-control',
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]);
            ?>
            <?php Html::input('string', $fieldData . '[scaricatoIl]', ((array_key_exists('scaricatoIl', $searchParamsArray) && !empty($searchParamsArray['scaricatoIl'])) ? $searchParamsArray['scaricatoIl'] : null), ['id' => 'partecipantisearch-scaricatoil', 'class' => 'form-control']); ?>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(
                    Yii::t('amoscore', 'Annulla'),
                    $resetUrl,
                    [
                        'class' => 'btn btn-secondary',
                        'onClick' => "
                            window.history.pushState('$encodeEventTitle', '$encodeEventTitle', window.getWidgetCurrentPageUrl());
                        "
                        /*'onClick' => "
                            setTimeout(function() {
                               $.pjax.reload({url: '{$resetUrl}', container: '#$pjaxId', async: false, timeout: false});
                               if(window.sendAllTickets) {sendAllTickets();}
                            }, 350);
                        ",*/
                    ]
                );
            ?>
            <?= Html::submitButton(
                Yii::t('amoscore', 'Search'),
                [
                    "class" => "btn btn-navigation-primary",
                    /*'onclick' => "
                                    var container = '$container-search-container';
                                    var searchParams = {
                                        nomeCognome: $('#' + container + ' input[name=nomeCognome]').val(),
                                        azienda: $('#' + container + ' input[name=azienda]').val(),
                                        listaAccreditamento: $('#' + container + ' select[name=listaAccreditamento]').val(),
                                        bigliettoSpedito: $('#' + container + ' select[name=bigliettoSpedito]').val(),
                                        presenza: $('#' + container + ' select[name=presenza]').val(),
                                        scaricatoIl: $('#' + container + ' input[name=scaricatoIl]').val(),
                                    };
                                    $.ajax({
                                        url : '/events/event/set-session-search-params',
                                        type: 'POST',
                                        async: true,
                                        data: {
                                            'events-sessions-search-params': JSON.stringify(searchParams),
                                        },
                                        success: function(response) {
                                           setTimeout(function() {
                                               $.pjax.reload({url: '{$resetUrl}', container: '#$pjaxId', async: false, timeout: false});
                                               if(window.sendAllTickets) {sendAllTickets();}
                                           }, 350);
                                       }
                                    });
                                return false;
                    ",*/
                ]
            );
            ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
