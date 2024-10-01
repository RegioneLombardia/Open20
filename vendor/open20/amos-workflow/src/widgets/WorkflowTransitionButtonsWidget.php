<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\workflow\widgets
 * @category   CategoryName
 */

namespace open20\amos\workflow\widgets;

use DOMDocument;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\forms\ActiveField;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\module\AmosModule;
use open20\amos\core\record\ContentModel;
use open20\amos\core\record\Record;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\utilities\WorkflowTransitionWidgetUtility;
use open20\amos\workflow\AmosWorkflow;
use kartik\base\Widget;
use raoul2000\workflow\base\Status;
use raoul2000\workflow\base\Transition;
use raoul2000\workflow\base\Workflow;
use yii\helpers\ArrayHelper;

/**
 * Class WorkflowTransitionButtonsWidget
 * Renders the widget useful to change a model workflow status.
 * @package open20\amos\workflow\widgets
 */
class WorkflowTransitionButtonsWidget extends Widget
{
    /**
     * @var string $containerWidgetClass
     */
    public $containerWidgetClass = 'workflow-transition-buttons-widget col-xs-12';

    /**
     * @var string $icon to validate
     */
    public $icon = 'refresh-alt';

    /**
     * @var string $icon validate
     */
    public $iconValidate = 'check-all';

    /**
     * @var string $icon edit
     */
    public $iconEdit = 'edit';

    /**
     * @var array Array of the icon options
     */
    public $iconOptions = ''; //['style' => 'font-size: 50px;'];

    /**
     * @var string $buttonLayout
     */
    public $buttonLayout = "{buttonSubmit}";

    /**
     * If the initialMessage is not set the default value is 'CURRENT STATE'
     * @var string $initialMessage
     */
    public $initialMessage;

    /**
     * If the label is not set the default value is 'Not set'
     * @var string $initialLabel
     */
    public $initialLabel;

    /**
     * @var string $classHr
     */
    public $classHr = 'workflow';

    /**
     * @var string $classDivIcon
     */
    public $classDivIcon;

    /**
     * @var string $classDivLabel
     */
    public $classDivLabel;

    /**
     * @var string $classDivMessage
     */
    public $classDivMessage;

    /**
     * @var string $classDivButton
     */
    public $classDivButton;

    /**
     * @var string $customJs
     */
    public $customJs;

    /**
     * If it is not set the default value is 'Are you sure you want to change status?'
     * @var string $dataConfirm
     */
    public $dataConfirm;

    /**
     * @var bool
     */
    public $enableDataConfirm = true;

    /**
     * @var bool $viewWidgetOnNewRecord If true force to view the widget when the model is in new record state
     */
    public $viewWidgetOnNewRecord = false;

    /**
     * @var Record $model
     */
    protected $model;

    /**
     * @var ActiveForm $form
     */
    protected $form;

    /**
     * @var string $workflowId
     */
    protected $workflowId;

    /**
     * @var array $metadata
     */
    protected $metadata;

    /**
     * @var array $statuses
     */
    protected $statuses = [];

    /**
     * @var AmosModule $module
     */
    protected $module;

    /**
     * @var string $translationCategory
     */
    protected $translationCategory;
    public $closeSaveButtonWidget;
    public $closeButton;
    public $initialStatusName;
    public $initialStatus;
    public $customStatusLabelDescription;

    /**
     * @var array $statusToRender Stati da renderizzare obbligatoriamente in fase di creazione (quando il record non e' ancora inserito nel db)
     */
    public $statusToRender;
    public $hideSaveDraftStatus;
    public $draftButton;
    public $draftLabel;
    public $enableConfirmNotification = true;

    /**
     * For multiple instance of this widget in the same page
     * default value "workflow-status_id"
     * @var $internalWorkflowStatusId
     */
    public $internalWorkflowStatusId = 'workflow-status_id';

    /**
     *  You can insert the save button for a specific status
     * @var $draftButtons
     */
    public $draftButtons;

    /**
     *  You can insert additional buttons button for a specific status
     * @var $additionalButtons
     */
    public $additionalButtons;

    /**
     * @var bool $inView If true means the widget is used in view, not in form.
     */
    public $inView = false;

    /**
     * @var string $statusField
     */
    public $statusField = 'status';

    /**
     * @var bool
     */
    public $personalized_render_buttons;

    /**
     *
     * Set of the permissionSave
     */
    public function init()
    {
        /** @var CrudController $controller */
        $controller = \Yii::$app->controller;
        $moduleName = $controller->module->uniqueId;
        $this->module = \Yii::$app->getModule($moduleName);
        if (!$this->translationCategory) {
            $this->translationCategory = preg_replace('/[^aA-zZ]/i', '', ((strpos($moduleName, 'amos') === false) ? 'amos' : '') . $moduleName);
        }
        parent::init();

//        if (!isset($this->initialStatus)) {
//            $this->initialStatus = $this->model->getWorkflowSource()->getWorkflow($this->workflowId)->getInitialStatusId();
//        }
    }

    /**
     * @return string
     */
    public function getWorkflowId()
    {
        return $this->workflowId;
    }

    /**
     * @param string $workflowId
     */
    public function setWorkflowId($workflowId)
    {
        $this->workflowId = $workflowId;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return string
     */
    public function getTranslationCategory()
    {
        return $this->translationCategory;
    }

    /**
     * @param string $translationCategory
     */
    public function setTranslationCategory($translationCategory)
    {
        $this->translationCategory = $translationCategory;
    }

    /**
     * @return Record
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Record $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return ActiveForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param ActiveForm $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->model->isNewRecord || $this->model->isNewRecord && $this->viewWidgetOnNewRecord) {
            if (isset(\Yii::$app->params['hideWorkflowTransitionWidget']) && \Yii::$app->params['hideWorkflowTransitionWidget']) {
                return '';
            }

            $hiddenActions = null;

            // Buttons to display in page
            $btns = $this->getAdditionalButtonsToRender();

            // Retrieve reset and save button
            $btns = ArrayHelper::merge($btns, $this->getDraftButtonsToRender());

            /*
             * Retrieve generated buttons from workflow, passed to the view
             */

            // Se ho lo status settato, mostro i bottoni generati dal workflow
            if ($this->model->hasWorkflowStatus()) {
                $buttonsGot = $this->getButtonsToRender(null, null, null, $this->customStatusLabelDescription);
                $workflowStatusId = $this->model->getWorkflowStatus()->getId();

            }  else {
                // Altrimenti passo dei "fake status" (passati come parametro in _form) per generare i vari
                // pulsanti
                $buttonsGot = $this->getButtonsToRender($this->initialStatusName, $this->initialStatus, $this->statusToRender, $this->customStatusLabelDescription);
                $workflowStatusId = $this->initialStatus;
            }

            $js = "
                $('form').on('submit', function (e) {
                    var buttonId = $('[clicked=true]').attr('id');
                    if (!buttonId) {
                        buttonId = '" . $workflowStatusId . "' 
                    }
                    $('#" . $this->internalWorkflowStatusId . "').val(buttonId);
                });
                
                $('[type=submit]').on('click', function(){
                    $('[type=submit]').removeAttr('clicked');
                    $(this).attr('clicked', true);
                });
                "
                . ((isset($this->customJs)) ? $this->customJs : "");

            //$buttonsDom = new DOMDocument();
            //$buttonsDom->loadHTML($buttonsGot);
            $this->getView()->registerJs($js, \yii\web\View::POS_READY);

            // Per ogni bottone generato attraverso il workflow widget (e inserito nella variabile $buttonsGot)
            // attraverso il dom lo estraggo e lo inserisco in un array (in modo tale da ottenere il bottone
            // separato dallo stateDescriptor e renderizzare separatamente i due componenti. Questa suddivisione
            // è stata fatta per non stravolgere il funzionamento attuale della funzione di generazione dei pulsanti
            foreach ($buttonsGot as $key => $button) {
                $buttonsDom = new DOMDocument();
                $buttonsDom->loadHTML($button['button']);
                if (!empty($buttonsDom->getElementsByTagName('button')[0])) {
                    $buttonsDom->getElementsByTagName('button')[0]->setAttribute('class',
                        ($key == sizeof($buttonsGot) - 1) ? 'btn btn-navigation-primary' : 'btn btn-workflow');
                    $btn = $buttonsDom->saveHTML($buttonsDom->getElementsByTagName('button')[0]);

                    $btns[] = [
                        "button" => $btn,
                        "stateDescriptor" => $button['stateDescriptor']
                    ];
                }
            }
            $hiddenActions = $this->form->field($this->model, $this->statusField)->hiddenInput(['id' => $this->internalWorkflowStatusId])->label(false);

            $notificationInput = $this->renderInputForNotify();

            if(!empty($this->personalized_render_buttons)){
                return  $this->render($this->personalized_render_buttons, [
                    'widgetClass' => $this->containerWidgetClass,
                    'resetButton' => $this->closeButton,
                    'buttons' => $btns,
                    'hiddenActions' => $hiddenActions,
                    'renderStatusError' => $this->renderStatusError($hiddenActions),
                ]);
            }

            // Renderizzo separatamente una view con tutti i bottoni
            return $this->render("transition-buttons-render", [
                'widgetClass' => $this->containerWidgetClass,
                'resetButton' => $this->closeButton,
                'buttons' => $btns,
                'hiddenActions' => $hiddenActions,
                'renderStatusError' => $this->renderStatusError($hiddenActions),
                'notificationInput' => $notificationInput
            ]);
        } else {
            return '';
        }
    }

    /**
     * @param ActiveField $hiddenActions
     * @return string
     */
    private function renderStatusError($hiddenActions)
    {
        $statusErrors = $this->model->getErrors($this->statusField);
        $renderStatusError = '';
        if (!empty($statusErrors)) {
            $renderStatusError = Html::error($this->model, $this->statusField, $hiddenActions->errorOptions);
        }
        return $renderStatusError;
    }

    /**
     * @param string|null $fakeStatusName
     * @param string|null $fakeStatus
     * @param string|null $statusToRender
     * @param string|null $customStatusLabelDescription
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getButtonsToRender($fakeStatusName = null, $fakeStatus = null, $statusToRender = null, $customStatusLabelDescription = null)
    {
        if ($this->inView) {
            return [];
        }

        $statusField = $this->statusField;
        $this->statuses = !$statusToRender ? $this->getStatuses() : $statusToRender;

        $currentStatus = !$fakeStatusName ? $this->getCurrentStatus() : $fakeStatusName;
        $buttonsArr = [];
        $buttons = [];
        $module = ($this->module);
        $User = \Yii::$app->getUser();
        $inState = !$fakeStatus ? $this->model->getWorkflowStatus()->getId() : $fakeStatus;

        /** @var Record $nameClass */
        $nameClass = $this->model->className();
        $pk = $this->model->getPrimaryKey();
        $findOneKey = (!empty($pk) ? $pk : $this->model->id);
        $realState = $nameClass::findOne($findOneKey);

        if ($realState) {
            $this->model->{$statusField} = $realState->status;
        } else if($this->model->isNewRecord && !empty($this->model->$statusField)){
            $this->model->{$statusField} = $this->model->$statusField;
        } else {
            $this->model->{$statusField} = $fakeStatus;
        }

        foreach ($this->statuses as $key => $State) {
            /** @var Status $state */
            $state = $this->model->getWorkflowSource()->getStatus($key, $this->model);
            if ($state && ($key != $inState)) {
                if ($User->can($state->getId(), ['model' => $this->model])) {
                    $statusesArr[$key] = $state;
                    $metadati = $state->getMetaData();
                    $hiddenRoles = false;
                    if (isset($metadati['hiddenRoles'])) {
                        if (strpos($metadati['hiddenRoles'], ',')) {
                            $arrRoles = explode(',', $metadati['hiddenRoles']);
                            foreach ($arrRoles as $Role) {
                                if ($User->can(trim($Role))) {
                                    $hiddenRoles = TRUE;
                                }
                            }
                        } else {
                            $hiddenRoles = $User->can($metadati['hiddenRoles']);
                        }
                    }
                    if ((!isset($metadati['hidden']) || (isset($metadati['hidden']) && strtolower($metadati['hidden']) != 'true'))
                        && !$hiddenRoles) {

                        $buttonLabel = WorkflowTransitionWidgetUtility::getStatusButtonLabel($module, $this->model, $metadati, $currentStatus, $this->translationCategory);
                        $stateDescriptor = WorkflowTransitionWidgetUtility::getStatusButtonDescription($module, $this->model, $metadati, $currentStatus, $this->translationCategory);

                        // Override button label and description on custom conditions defined by
                        // widget params
                        if ($customStatusLabelDescription != null) {
                            if (is_array($customStatusLabelDescription)) {
                                foreach ($customStatusLabelDescription as $keyCurrentStatus => $currStatus) {
                                    if ($this->model->{$statusField} == $keyCurrentStatus) {
                                        if (is_array($currStatus)) {
                                            foreach ($currStatus as $keyButtonStatus => $buttonStatus) {
                                                if ($keyButtonStatus == $state->getId()) {
                                                    if (is_array($buttonStatus)) {
                                                        if (array_key_exists("buttonLabel", $buttonStatus)) {
                                                            if (is_string($buttonStatus["buttonLabel"])) {
                                                                $buttonLabel = $buttonStatus["buttonLabel"];
                                                            }
                                                        }
                                                        if (array_key_exists("stateDescriptor", $buttonStatus)) {
                                                            if (is_string($buttonStatus["stateDescriptor"])) {
                                                                $stateDescriptor = $buttonStatus["stateDescriptor"];
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $dataConfirm = null;
                        if($this->enableDataConfirm) {
                            $dataConfirm = WorkflowTransitionWidgetUtility::getStatusButtonDataConfirm($module, $this->model, $metadati, $currentStatus, $this->translationCategory);
                        }

                        $closeSaveButtonConfig = [
                            'model' => $this->model,
                            'layout' => $this->buttonLayout,
                            'buttonSaveLabel' => $buttonLabel,
                            'buttonNewSaveLabel' => $buttonLabel,
                            'buttonClassSave' => 'btn',
                            'buttonId' => $key,
                        ];

                        $hideConfirm = isset($metadati['hideConfirm']) && $metadati['hideConfirm'];
                        if(!$hideConfirm) {
                            $closeSaveButtonConfig['dataConfirm'] = $dataConfirm;
                        }

                        $buttons[] = [
                            'button' => CloseSaveButtonWidget::widget($closeSaveButtonConfig),
                            'stateDescriptor' => $stateDescriptor,
                            'order' => ((isset($metadati['order']) && is_numeric($metadati['order'])) ? $metadati['order'] : 0)
                        ];

                    }
                }
            }
        }
        // Order status by sw_metadata order
        if (!empty($buttons[0]['order'])) {
            usort($buttons, function ($a, $b) {
                return $a['order'] - $b['order'];
            });
        }

        return $buttons;
    }

    /**
     * @return array
     */
    protected function getStatuses()
    {
        $workFlowStatus = [];   // Stati del workflow

        if ($this->model->hasWorkflowStatus()) {  // Ho già lo stato. Model già salvato una volta.
            $allStatus = $this->model->getWorkflow()->getAllStatuses();   // Tutti gli stati del workflow
            $modelStatus = $this->model->getWorkflowStatus()->getId();    // Stato del model
            /** @var Status $actualStatusObj */
            $actualStatusObj = $allStatus[$modelStatus];
            $workFlowStatus[$actualStatusObj->getId()] = $actualStatusObj->getLabel();    // Aggiungo lo stato iniziale a quelli da visualizzare.
            // Composizione di tutti gli altri stati possibili a partire dall'attuale, ovvero le transazioni possibili.
            $transitions = $this->model->getWorkflowSource()->getTransitions($modelStatus, $this->model);
            foreach ($transitions as $transition) {
                /** @var Transition $transition */
                $workFlowStatus[$transition->getEndStatus()->getId()] = $transition->getEndStatus()->getLabel();
            }
        } else {                                // Non ho lo stato. Model mai salvato. Faccio vedere solo quello iniziale.
            /** @var Workflow $contentDefaultWorkflow */
            $contentDefaultWorkflow = $this->model->getWorkflowSource()->getWorkflow($this->workflowId);
            $allStatus = $contentDefaultWorkflow->getAllStatuses();     // Tutti gli stati del workflow
            /** @var Status $initialStatusObj */
            $initialStatusObj = $allStatus[$contentDefaultWorkflow->getInitialStatusId()];
            $workFlowStatus[$initialStatusObj->getId()] = $initialStatusObj->getLabel();
        }

        return $workFlowStatus;
    }

    /**
     * Return the id of the current state of the model without the prefix of the workflow
     * @return string
     */
    protected function getCurrentStatus()
    {
        /** @var Status $state */
        $state = $key = $this->model->getWorkflowStatus()->getId();
        if (!empty(trim($key)) && strpos($key, '/') !== false) {
            $pos = strpos($key, '/');
            $state = substr($state, $pos + 1);
        }
        return $state;
    }

    /**
     * Render the save bottons
     * @return array
     */
    public function getDraftButtonsToRender()
    {
        $statusField = $this->statusField;
        $btns = [];
        $draftClass= 'draft-button';

        if ($this->inView) {
            return $btns;
        }

        /*
         * Se non ho lo status nel workflow, mostro il pulsante di salvataggio a prescindere
         */
        if (!$this->model->getWorkflowStatus()) {
            //$saveButton = $closeSaveButtonWidgetD->saveHTML($closeSaveButtonWidgetD->getElementsByTagName('button')[0]);

            if (!empty($this->draftButtons)) {
                if (array_key_exists($this->initialStatus, $this->draftButtons)) {
                    $btns[] = [
                        "button" => $this->draftButtons[$this->initialStatus]['button'],
                        "stateDescriptor" => $this->draftButtons[$this->initialStatus]['description'],
                        "class" => $draftClass
                    ];
                } else {
                    $btns[] = [
                        "button" => $this->draftButtons['default']['button'],
                        "stateDescriptor" => $this->draftButtons['default']['description'],
                        "class" => $draftClass
                    ];
                }
            }
        } else {
            /*
             * Altrimenti verifico se non mi trovo nello stato di validato passato a parametro (option hideSaveDraftStatus)
             */

            // Se non e' un array verifico l'unico workflow id passato a parametro
            if (!is_array($this->hideSaveDraftStatus)) {
                if ($this->model->getWorkflowStatus()->getId() != $this->hideSaveDraftStatus) {
                    //$saveButton = $closeSaveButtonWidgetD->saveHTML($closeSaveButtonWidgetD->getElementsByTagName('button')[0]);
                    if (!empty($this->draftButtons)) {
                        if (array_key_exists($this->model->{$statusField}, $this->draftButtons)) {
                            $btns[] = [
                                "button" => $this->draftButtons[$this->model->{$statusField}]['button'],
                                "stateDescriptor" => $this->draftButtons[$this->model->{$statusField}]['description'],
                                "class" => $draftClass

                            ];
                        } else {
                            $btns[] = [
                                "button" => $this->draftButtons['default']['button'],
                                "stateDescriptor" => $this->draftButtons['default']['description'],
                                "class" => $draftClass

                            ];
                        }
                    }
                }
            } else { // altrimenti verifico i parametri passati come array
                if (!empty($this->draftButtons) && !in_array($this->model->getWorkflowStatus()->getId(),
                        $this->hideSaveDraftStatus)) {
                    if (array_key_exists($this->model->{$statusField}, $this->draftButtons)) {
                        $btns[] = [
                            "button" => $this->draftButtons[$this->model->{$statusField}]['button'],
                            "stateDescriptor" => $this->draftButtons[$this->model->{$statusField}]['description'],
                            "class" => $draftClass
                        ];
                    } else {
                        $btns[] = [
                            "button" => $this->draftButtons['default']['button'],
                            "stateDescriptor" => $this->draftButtons['default']['description'],
                            "class" => $draftClass

                        ];
                    }
                }
            }
        }

        $btns = $this->cleanEmptyButtons($btns);
        return $btns;
    }

    /**
     * Get additional buttons
     * @return array
     */
    public function getAdditionalButtonsToRender()
    {
        $statusField = $this->statusField;
        $btns = [];
        $current_status = !empty($this->model->{$statusField}) ? $this->model->{$statusField} : null;
        if (!empty($this->additionalButtons)) {
            foreach ($this->additionalButtons as $status => $additionalButtons) {
                if ($status == 'default') {
                    foreach ($additionalButtons as $additionalButton) {
                        $btns[] = [
                            "button" => $additionalButton['button'],
                            "stateDescriptor" => $additionalButton['description']
                        ];
                    }
                } else {
                    if ($status == $current_status) {
                        foreach ($additionalButtons as $additionalButton) {
                            $btns[] = [
                                "button" => $additionalButton['button'],
                                "stateDescriptor" => $additionalButton['description']
                            ];
                        }
                    }
                }
            }
        }
        return $btns;
    }

    /**
     * @return string
     */
    public function renderInputForNotify()
    {
        $notifyModule = \Yii::$app->getModule('notify');
        if (!empty($notifyModule)
            && $this->model instanceof \open20\amos\notificationmanager\record\NotifyRecord
            && $this->model instanceof \open20\amos\core\interfaces\WorkflowModelInterface
            && $this->model instanceof ContentModel
        ) {
            if ($this->enableConfirmNotification == true) {
                if ($this->model->hasProperty('saveNotificationSendEmail') && !empty($notifyModule->confirmEmailNotification) && $notifyModule->confirmEmailNotification == true) {
                    $validatedStatus = $this->model->getValidatedStatus();
                    $isValidatedOnce = $this->model->getValidatedOnce();
                    $isCurrentStatusValidated = ($this->model->getValidatedStatus() == $this->model->status) ? 1 : 0;
                    $emailNotificated = \open20\amos\notificationmanager\models\NotificationSendEmail::findOne(['classname' => get_class($this->model), 'content_id' => $this->model->id]);
                    // ---- se non esisite il notification_send_email ----
                    if (empty($emailNotificated)) {
                        // the modal is shown if you click (Validate/publish) or after the validation if you have not selected yes on send notification
                        // the first time you click for submit open the modal enc do the prevent default, if in the modal click yes, trigger again the submit without open the modal
                        $js = <<<JS
                    var clickedWorkflowTransitionButton = false; // this variable is used to avoid to open the modal if you don't click on submit buttons that aren't in the transition widget
                    var modalShown = false;
                    var clickedButtonsConfirm = false;
                    var clickValidateButton = false;
                    var form = $('#modal-notify-send-email').parents('form');
                    var isValidatedOnce = '$isValidatedOnce';
                    
                    $(document).on('submit','form', function(e){
                        if(!modalShown && clickedWorkflowTransitionButton && (clickValidateButton || isValidatedOnce === '1') ) {
                            e.preventDefault();
                             $('#modal-notify-send-email').modal('show');
                        }
                        clickedWorkflowTransitionButton = false;
                    });
                    
                    $('#confirm-true').click(function(e){
                         e.preventDefault();
                         if(!clickedButtonsConfirm){
                             modalShown = true;
                             clickedButtonsConfirm = true;
                            $('#save-notification-send-email').val(1);
                            $(form).trigger('submit');
                        }
                    });
                    
                     $('#confirm-false').click(function(e){
                         e.preventDefault();
                         if(!clickedButtonsConfirm){
                             modalShown = true;
                             clickedButtonsConfirm = true;
                            $(form).trigger('submit');
                        }
                    });
                     
                     $('#modal-notify-send-email').on('hidden.bs.modal', function () {
                         modalShown = true;
                         if(!clickedButtonsConfirm){
                            $(form).trigger('submit');
                        }
                     });
                     
                     $('.workflow-transition-button-widget button[type="submit"]').click(function(){
                         clickedWorkflowTransitionButton = true;
                           if($(this).attr('id') === '$validatedStatus'){
                              clickValidateButton = true;
                          } else {
                              clickValidateButton = false;
                          }
                     });
JS;
                        $this->getView()->registerJs($js);

                        ModalUtility::createConfirmModal([
                            'id' => 'modal-notify-send-email',
                            'containerOptions' => ['class' => 'modal-utility'],
                            'modalDescriptionText' => AmosWorkflow::tHtml('amosworkflow', 'Vuoi inviare le email di avviso agli utenti per la pubblicazione di questo contenuto?'),
                            'confirmBtnOptions' => ['id' => 'confirm-true', 'class' => 'btn btn-navigation-primary'],
                            'cancelBtnOptions' => ['id' => 'confirm-false', 'class' => 'btn btn-secondary', 'data-dismiss' => 'modal'],
                            'cancelBtnLabel' => AmosWorkflow::tHtml('amosworkflow', 'No'),
                            'confirmBtnLabel' => AmosWorkflow::tHtml('amosworkflow', 'Si'),
                        ]);

                        return Html::hiddenInput('saveNotificationSendEmail', $this->model->saveNotificationSendEmail, ['id' => 'save-notification-send-email'])
                            .Html::hiddenInput('createUpdateNotification', 0, ['id' => 'create-update-notification']);

                    } else if(\Yii::$app->user->can('RESEND_NOTIFICATION')){
                    // ---- se esisite già il notification_send_email ----
                        if (!$this->model->isNewRecord) {

                            $js2 = <<<JS
                    var clickedWorkflowTransitionButton = false; // this variable is used to avoid to open the modal if you don't click on submit buttons that aren't in the transition widget
                    var modalShown = false;
                    var clickedButtonsConfirm = false;
                    var form = $('#modal-update-notification').parents('form');
                    var isValidatedOnce = '$isValidatedOnce';
                    var canUpdateNotification = false;
                    
                    var sometingChanged = false;
                    
                    //listeners per la modifica dei campi del form
                    $(document).on('textEditorChange','.mce-tinymce + textarea', function(){
                        sometingChanged = true;
                    });
                    $(document).on('change','input,select,textarea', function(){
                        sometingChanged = true;
                    });
                    
                    
                    // al submit controlla se aprire  il modal
                    $(document).on('submit','form', function(e){
                         // e.preventDefault();
                        if(!modalShown  && clickedWorkflowTransitionButton && sometingChanged && isValidatedOnce === '1' && canUpdateNotification) {
                            e.preventDefault();
                            $('#modal-update-notification').modal('show');
                        }
                        clickedWorkflowTransitionButton = false;
                    });
                    
                    //evento di chiusura del modale
                    $('#modal-update-notification').on('hidden.bs.modal', function () {
                         modalShown = true;
                         if(!clickedButtonsConfirm){
                            $('#create-update-notification').val(0);
                            $(form).trigger('submit');
                        }
                     });
                    
                    //evento di clik ok al modale
                    $('#update-confirm-true').click(function(e){
                         e.preventDefault();
                         if(!clickedButtonsConfirm){
                             modalShown = true;
                             clickedButtonsConfirm = true;
                            $('#create-update-notification').val(1);
                            $(form).trigger('submit');
                        }
                    });
                    
                     //evento di clik annulla al modale
                     $('#upadate-confirm-false').click(function(e){
                         e.preventDefault();
                         if(!clickedButtonsConfirm){
                             modalShown = true;
                             clickedButtonsConfirm = true;
                             $('#create-update-notification').val(0);
                            $(form).trigger('submit');
                        }
                    });
                     
                       // verifico che ho cliccato un submit nel widget del workflow e se posso aprire  il modale per l'aggiornamento notifiche
                       $('.workflow-transition-button-widget button[type="submit"]').click(function(){
                         clickedWorkflowTransitionButton = true;
                           var isDraftAndValidated  = false;
                           var clickValidateButton = false;
                           if($(this).attr('id') === '$validatedStatus'){
                              clickValidateButton = true;
                          } else {
                              clickValidateButton = false;
                          }
                          
                          if('$isCurrentStatusValidated' === '1' && $(this).parents('.draft-button').length){
                               isDraftAndValidated = true;
                          }
                          canUpdateNotification = isDraftAndValidated || clickValidateButton;
                     });
    
JS;
                            $this->getView()->registerJs($js2);

                            ModalUtility::createConfirmModal([
                                'id' => 'modal-update-notification',
                                'containerOptions' => ['class' => 'modal-utility'],
                                'modalDescriptionText' => AmosWorkflow::tHtml('amosworkflow', 'Vuoi reinviare una notifica per questo contenuto?'),
                                'confirmBtnOptions' => ['id' => 'update-confirm-true', 'class' => 'btn btn-navigation-primary'],
                                'cancelBtnOptions' => ['id' => 'update-confirm-false', 'class' => 'btn btn-secondary', 'data-dismiss' => 'modal'],
                                'cancelBtnLabel' => AmosWorkflow::tHtml('amosworkflow', 'No'),
                                'confirmBtnLabel' => AmosWorkflow::tHtml('amosworkflow', 'Si'),
                            ]);
                            return Html::hiddenInput('createUpdateNotification', 0, ['id' => 'create-update-notification']);

                        }
                    }else{
                        return Html::hiddenInput('createUpdateNotification', 0, ['id' => 'create-update-notification']);
                    }
                }
            }
        }
        return '';
    }

    /**
     * @param $buttons
     * @return array
     */
    public function cleanEmptyButtons($buttons)
    {
        $btns = [];
        foreach ($buttons as $button) {
            if (!empty($button['button'])) {
                $btns [] = $button;
            }
        }
        return $btns;
    }
}
