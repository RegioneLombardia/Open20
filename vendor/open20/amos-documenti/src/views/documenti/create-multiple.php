<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\admin\models\search\UserProfileSearch;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 * @var yii\widgets\ActiveForm $form
 */
/** @var \open20\amos\documenti\controllers\DocumentiController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
//$isFolder = $controller->documentIsFolder($model);
if ($controller->documentIsFolder($model)) {
    $this->title = AmosDocumenti::t('amosdocumenti', '#create_folder_title');
} else {
    $this->title = AmosDocumenti::t('amosdocumenti', 'Inserisci documento');
}

$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', Yii::$app->session->get('previousTitle')), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

/** @var \open20\amos\documenti\controllers\DocumentiController $appController */
//$appController = Yii::$app->controller;
//$isFolder = $appController->documentIsFolder($model);
$enableCategories = AmosDocumenti::instance()->enableCategories;
$moduleGroups = Yii::$app->getModule('groups');
$moduleCommunity = Yii::$app->getModule('community');
$moduleCwh = Yii::$app->getModule('cwh');

\yii\web\YiiAsset::register($this);
\open20\amos\layout\assets\SpinnerWaitAsset::register($this);

$enableGroupNotification = AmosDocumenti::instance()->enableGroupNotification;

if ($enableGroupNotification) {

    $modelSearchProfile = new UserProfileSearch();
    $dataProviderProfiles = $modelSearchProfile->search(Yii::$app->request->get());
    $dataProviderProfiles->setSort([
        'defaultOrder' => [
            'nome' => SORT_ASC
        ]
    ]);
    $dataProviderProfiles->pagination = false;
    $idCommunityMembers = implode(',', $dataProviderProfiles->keys);

    $idsNotifichePreferenzeProfili = $model->getNotifichePreferenzeProfili();
    $idsProfili = implode(',', $idsNotifichePreferenzeProfili);
    $idsNotifichePreferenzeGruppi = $model->getNotifichePreferenzeGruppi();
    $idsGruppi = implode(',', $idsNotifichePreferenzeGruppi);

    $isNew = $model->isNewRecord ? 'true' : 'false';

    $js = <<< JS
$(document).ready(function () {
    var selectedProfiles = [$idCommunityMembers];
    var preferenseProfili = [$idsProfili];
    var preferenseGruppi = [$idsGruppi];
    
    initialize();

    function setChecked() {
        $('#grid-members tbody tr').each(function () {
            var valore = $(this).find('input').val();
            var flag = 0;

            var lista = preferenseProfili;
            if ($isNew) {
                lista = selectedProfiles
            }
        
            for (var i = 0; i < lista.length; i++) {
                if (lista[i] == valore) {
                    $(this).find('input').attr('checked', true);
                    $(this).addClass('success');
                    flag = 1;
                }
            }

            if (flag == 0) {
                $(this).removeClass('success');
                $(this).find('input').removeAttr('checked');
            }
            
        });
        
        $('#w4 tbody tr').each(function () {
            var valore = $(this).find('input').val();
            var flag = 0;
            
            for (var i = 0; i < preferenseGruppi.length; i++) {
                if (preferenseGruppi[i] == valore) {
                    $(this).find('input').attr('checked', true);
                    $(this).addClass('success');
                    flag = 1;
                }
            }

            if (flag == 0) {
                $(this).removeClass('success');
                $(this).find('input').removeAttr('checked');
            }
        });
    }
    
    //check SINGOLO partecipanti
    $(document).on('click', '#grid-members .kv-row-checkbox', function () {
        var tr = $(this).closest('tr');
        var user_profile_id = $(tr).attr('data-key');
        if (this.checked) {
            selectedProfiles.push(user_profile_id);
            $('<input>').attr({
                type: 'hidden',
                id: 'profile-' + user_profile_id,
                name: 'selection-profiles[]',
                value: user_profile_id
            }).appendTo('form');
        }
        else {
            //remove selection
            for (var i = selectedProfiles.length - 1; i >= 0; i--) {
                if (selectedProfiles[i] === user_profile_id) {
                    selectedProfiles.splice(i, 1);
                }
            }
            $('#profile-' + user_profile_id).remove();
        }

    });

    $(document).on('pjax:end', function (data, status, xhr, options) {
        setChecked();
    });
    
    //check ALL partecipanti
    $('#grid-members .select-on-check-all').click(function () {
        
        if (!this.checked) {
            for (var i = 0; i < selectedProfiles.length; i++) {
                $('#profile-' + selectedProfiles[i]).remove();
                $('#grid-members tbody tr[data-key=' + selectedProfiles[i] + ']').removeClass('success');
            }
            selectedProfiles = [];
        }
        else {
            selectedProfiles = [$idCommunityMembers];
            for (var j = 0; j < selectedProfiles.length; j++) {
                if ($('#profile-' + selectedProfiles[j]).length == 0) {
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'profile-' + selectedProfiles[j],
                        name: 'selection-profiles[]',
                        value: selectedProfiles[j]
                    }).appendTo('form');
                    $('#grid-members tbody tr[data-key=' + selectedProfiles[j] + ']').addClass('success');
                }
            }
        }
    });

    function initialize() {
        var lista = preferenseProfili;
        if ($isNew) {
            lista = selectedProfiles
        }
        for (var i = 0; i < lista.length; i++) {
            $('<input>').attr({
                type: 'hidden',
                id: 'profile-' + lista[i],
                name: 'selection-profiles[]',
                value: lista[i]
            }).appendTo('form');
        }

        setChecked();
    }
    
    $(document).on('click', '.button-delete-attach', function(event){
       event.preventDefault();
           if(confirm('Sei sicuro di volere eliminare questo elemento?')){
           $.ajax({
              type: "POST",
              url: $(this).attr('href')
            }).done(function(){
                $.pjax.reload({container:"#pjax-container-attach", timeout:20000});
            });
       }
       return false;
    });
});

JS;

    $this->registerJs($js);
}

$js = <<<JS
var lockForm = true;
    
    //Whe user clicks the upload button trigger the upload action
    $('#uploadArea').on('click', function() {
        $('#fileinput').fileinput('upload');
    });

    $('#fileinput').on('filebatchuploadcomplete', function() {
        lockForm = false;
        //Make Visible the save button
        $('#uploadArea').hide();
        
        $('#changeStatusArea').show();
    });
    
    $('#fileinput').on('fileselect', function(event, numFiles, label) {
        lockForm = true;
        //Make Visible the save button
        $('#changeStatusArea').hide();
        $('#uploadArea').show();
    });
    
    $('#fileinput').on('batchselect', function(event, numFiles, label) {
        lockForm = true;
        //Make Visible the save button
        $('#changeStatusArea').hide();
        $('#uploadArea').show();
    });
    
    $('body').on('beforeValidate', '#create-multiple', function (e) {
        //
        if(lockForm) {
            return false;
        }
        
        $(':input[type="submit"]', this).attr('disabled', 'disabled');
        
        $(':input[type="submit"]', this).each(function (i) {
            if ($(this).prop('tagName').toLowerCase() === 'input') {
                $(this).data('enabled-text', $(this).val());
                $(this).val($(this).data('disabled-text'));
            } else {
                $(this).data('enabled-text', $(this).html());
                $(this).html($(this).data('disabled-text'));
            }
        });
    }).on('afterValidate', '#create-multiple', function (e) {
        if ($(this).find('.has-error').length > 0) {
            $(':input[type="submit"]', this).removeAttr('disabled');
            
            $(':input[type="submit"]', this).each(function (i) {
                if ($(this).prop('tagName').toLowerCase() === 'input') {
                    $(this).val($(this).data('enabled-text'));
                } else {
                    $(this).html($(this).data('enabled-text'));
                }
            });
        } else {
            $('.loading').show();
        }
    });
    
    $('#cancell_all').on('click', function() {
        console.log($('#fileinput').fileinput('destroy'));
    });
    
JS;

$this->registerJs($js, View::POS_READY);
$this->registerCss(".error-summary { 
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
    backgrosund-color: #f2dede;
    border-color: #ebccd1;
    }"
);
?>

<div class="loading" hidden></div>
<?php
$form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'create-multiple'
]);

$customView = Yii::$app->getViewPath() . '/imageField.php';
$errorSummary = $form->errorSummary($model);
$moduleGroups = Yii::$app->getModule('groups');
$moduleCommunity = Yii::$app->getModule('community');
$moduleCwh = Yii::$app->getModule('cwh');

/**
 * Check if workflow is on or off
 * if the case add the user itself as validator
 */
$hideWorkflow = isset(Yii::$app->params['hideWorkflowTransitionWidget']) && Yii::$app->params['hideWorkflowTransitionWidget'];
/** @var AmosCommunity $amosCommunity */
$amosCommunity = Yii::$app->getModule('community');
$hideWorkflow = $hideWorkflow || $amosCommunity->bypassWorkflow;

$status = ($hideWorkflow == false)
    ? Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA
    : Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
?>

<div class="documenti-form col-xs-12">
    <div class="row">
        <div class="col-xs-12">
            <?= \kartik\widgets\FileInput::widget([
                'name' => 'files',
                'id' => 'fileinput',
                'options' => [
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    //                    'layoutTemplates' => [
                    //                        'actions' => '<div class="file-actions kv-preview-thumb">' .
                    //                            '    <div class="file-footer-buttons">' .
                    //                            '        {zoom} {delete}' .
                    //                            '    </div>' .
                    //                            '    {drag}' .
                    //                            '    <!-- <div class="file-upload-indicator" title="{indicatorTitle}">{indicator}</div> -->' .
                    //                            '    <div class="clearfix"></div>' .
                    //                            '</div>'
                    //                    ],
                    'uploadUrl' => \yii\helpers\Url::toRoute([
                        '/documenti/documenti/upload',
                        'attribute' => 'file',
                        'communityId' => $communityId,
                        'parentId' => $parentId,
                        'status' => $status
                    ]),
                    'showRemove' => false,
                    'showUpload' => false,
                    'uploadAsync' => true,
                    //'previewFileIcon' => '<i class="fa fa-file"></i>',
                    //'allowedPreviewTypes' => null, // set to empty, null or false to disable preview for all types
                ],
            ]);
            ?>
        </div>
    </div>

    <!--
    <div class="col-lg-12 col-sm-12">
        <h4><?php // echo AmosDocumenti::t('amosdocumenti', '#email_notification_tex1') ?></h4>
    </div>
    -->

    <?php
    if (!empty($moduleGroups) && !empty($moduleCommunity) && !empty($moduleCwh)) :
        echo ' ';
        $entityId = null;
        $this->params['idUserprofileCommunity'] = [];

        if (isset($moduleCommunity)) {
            $query = \open20\amos\groups\models\Groups::getGroupsByParent();
        } else {
            $query = \open20\amos\groups\models\Groups::find()->andWhere(0);
        }

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        ?>

        <div class="col-xs-12 col-lg-6">
            <div class="col-lg-12">
                <h3><?= AmosDocumenti::t('amosdocumenti', 'Partecipanti') ?></h3>
            </div>
            <?php
            \yii\widgets\Pjax::begin([
                'id' => 'pjax-container',
                'timeout' => 2000,
                'clientOptions' => ['data-pjax-container' => 'grid-members']
            ]);

            echo \open20\amos\core\views\AmosGridView::widget([
                'dataProvider' => $dataProviderProfiles,
                'id' => 'grid-members',
                'columns' => [
                    'nomeCognome',
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'rowSelectedClass' => \kartik\grid\GridView::TYPE_SUCCESS,
                        'name' => 'element-profiles'
                    ],
                ]
            ]);

            \yii\widgets\Pjax::end();
            ?>
        </div>

        <div class="col-xs-12 col-lg-6">
            <div class="col-lg-12">
                <h3><?= AmosDocumenti::t('amosdocumenti', 'Gruppi') ?></h3>
            </div>

            <?php
            echo \open20\amos\core\views\AmosGridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'name',
                    'description',
                    [
                        'attribute' => 'numberGroupMembers',
                        'label' => AmosDocumenti::t('amosdocumenti', '#number_group_members')
                    ],
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'rowSelectedClass' => \kartik\grid\GridView::TYPE_SUCCESS,
                        'name' => 'selection-groups'
                        // you may configure additional properties here
                    ],
                ]
            ]);
            ?>
        </div>

        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'inviaNotifiche')->checkbox() ?>
        </div>

    <?php endif; ?>

    <hr/>

    <div id="closeSaveArea" class="row" style="display: none;">
        <?php
        $enableCategories = AmosDocumenti::instance()->enableCategories;
        $enableVersioning = $controller->documentsModule->enableDocumentVersioning;
        $isNewVersion = !empty(\Yii::$app->request->get('isNewVersion'))
            ? \Yii::$app->request->get('isNewVersion')
            : false;

        $disableStandardWorkflow = $controller->documentsModule->disableStandardWorkflow;
        $closeButtonText = ($enableVersioning && !$model->isNewRecord && $isNewVersion)
            ? AmosDocumenti::t('amosdocumenti', '#CANCEL_NEW_VERSION')
            : AmosDocumenti::t('amosdocumenti', 'Annulla');

        $statusToRenderToHide = $model->getStatusToRenderToHide();
        $daValidareDescription = $model->is_folder
            ? AmosDocumenti::t('amosdocumenti', 'le modifiche e mantieni la cartella in "richiesta di pubblicazione"')
            : AmosDocumenti::t('amosdocumenti', 'le modifiche e mantieni il documento in "richiesta di pubblicazione"');

        $validatoDescription = $model->is_folder
            ? AmosDocumenti::t('amosdocumenti', 'le modifiche e mantieni la cartella "pubblicato"')
            : AmosDocumenti::t('amosdocumenti', 'le modifiche e mantieni il documento "pubblicato"');

        $draftButtons = [];
        if ($disableStandardWorkflow == false) {
            $draftButtons = [
                Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE => [
                    'button' => Html::submitButton(AmosDocumenti::t('amosdocumenti', 'Salva'), ['class' => 'btn btn-workflow']),
                    'description' => $daValidareDescription,
                ],
                Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO => [
                    'button' => Html::submitButton(AmosDocumenti::t('amosdocumenti', 'Salva'), ['class' => 'btn btn-workflow']),
                    'description' => $validatoDescription,
                ],
                Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA => [
                    'button' => Html::submitButton(
                        AmosDocumenti::t('amosdocumenti', 'Salva in bozza'),
                        ['class' => 'btn btn-workflow', 'id' => Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA]
                    ),
                    'description' => AmosDocumenti::t('amosdocumenti', 'potrai richiedere la pubblicazione in seguito'),
                ]
            ];
        }
        ?>
    </div>

    <div id="uploadArea" class="row bk-btnFormContainer" style="display: none;">
        <div class="workflow-transition-button-widget col-xs-12">
            <div class="workflow-buttons-container col-lg-10 col-lg-push-2 col-xs-12 nop">
                <div class="workflow-form-actions workflow-button-container">
                    <?= Html::a(
                        $closeButtonText,
                        $controller->getFormCloseUrl($model),
                        ['class' => 'btn btn-secondary', 'id' => 'cancell_all']
                    ); ?>
                </div>
                <div class="workflow-form-actions workflow-button-container">
                    <?= Html::a(\Yii::t('amosdocumenti', 'Carica i Documenti'), '#', ['class' => 'btn btn-navigation-primary']); ?>
                </div>
            </div>
            <div class="workflow-button-container col-lg-2 col-lg-pull-10 col-xs-12 nop"></div>
        </div>
    </div>

    <div id="changeStatusArea" class="row bk-btnFormContainer" style="display: none;">
        <?php
        echo \open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget::widget([
            // parametri ereditati da verioni precedenti del widget WorkflowTransition
            'form' => $form,
            'model' => $model,
            'workflowId' => Documenti::DOCUMENTI_WORKFLOW,
            'viewWidgetOnNewRecord' => true,
            'closeButton' => Html::a(
                $closeButtonText,
                $controller->getFormCloseUrl($model),
                ['class' => 'btn btn-secondary', 'id' => 'cancell_all']
            ),
            // fisso lo stato iniziale per generazione pulsanti e comportamenti
            // "fake" in fase di creazione (il record non e' ancora inserito nel db)
            'initialStatusName' => Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA,
            'initialStatus' => $model->getWorkflowSource()->getWorkflow(Documenti::DOCUMENTI_WORKFLOW)->getInitialStatusId(),
            'statusToRender' => $statusToRenderToHide['statusToRender'],
            'hideSaveDraftStatus' => $statusToRenderToHide['hideDraftStatus'],
            'draftButtons' => $draftButtons
        ]); ?>
    </div>

</div>
<?php ActiveForm::end(); ?>
