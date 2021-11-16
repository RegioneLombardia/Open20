<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\workflow\widgets
 * @category   CategoryName
 */

namespace open20\amos\workflow\widgets;

use DOMDocument;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\record\Record;
use raoul2000\workflow\base\Status;
use yii\base\InvalidConfigException;

/**
 * Class WorkflowTransitionSimplifiedButtonsWidget
 * Renders the widget useful to change a model workflow status showing
 * only a save button for the user.
 *
 * @package open20\amos\workflow\widgets
 */
class WorkflowTransitionSimplifiedButtonsWidget extends WorkflowTransitionButtonsWidget
{
    public $transitionStatuses = [];
    public $buttonLabel;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->model->isNewRecord || $this->model->isNewRecord && $this->viewWidgetOnNewRecord) {

            if (isset(\Yii::$app->params['hideWorkflowTransitionWidget']) && \Yii::$app->params['hideWorkflowTransitionWidget']) {
                return '';
            }

            /*
             * Buttons to display in page
             */
            $btns = [];

            /*
             * Retrieve reset and save button
             */

            $hiddenActions = null;

            /*
             * Retrieve generated buttons from workflow, passed to the view
             */

            // Se ho lo status settato, mostro i bottoni generati dal workflow

            if ($this->model->hasWorkflowStatus()) {
                $buttonsGot = $this->getButtonToRender();

                $js = "
                $('form').on('submit', function (e) {
                    var buttonId = $('[clicked=true]').attr('id');
                    if (!buttonId) {
                        buttonId = '" . $this->model->getWorkflowStatus()->getId() . "'
                    }
                    $('#workflow-status_id').val(buttonId);
                });
                
                $('[type=submit]').on('click', function(){
                    $('[type=submit]').removeAttr('clicked');
                    $(this).attr('clicked', true);
                });
                "
                    . ((isset($this->customJs)) ? $this->customJs : "");

            } else {
                // Altrimenti passo dei "fake status" (passati come parametro in _NEWform) per generare i vari
                // pulsanti
                $buttonsGot = $this->getButtonToRender();

                $js = "
                $('form').on('submit', function (e) {
                    var buttonId = $('[clicked=true]').attr('id');
                    if (!buttonId) {
                        buttonId = '" . $this->model->getWorkflowSource()->getWorkflow($this->workflowId)->getInitialStatusId() . "'
                    }
                    $('#workflow-status_id').val(buttonId);
                });
                
                $('[type=submit]').on('click', function(){
                    $('[type=submit]').removeAttr('clicked');
                    $(this).attr('clicked', true);
                });
                "
                    . ((isset($this->customJs)) ? $this->customJs : "");
            }

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
                $buttonsDom->getElementsByTagName('button')[0]->setAttribute('class', $buttonsDom->getElementsByTagName('button')[0]->getAttribute('class') . ' ' . (($key == sizeof($buttonsGot) - 1) ? 'btn-navigation-primary' : 'btn-workflow'));
                $btn = $buttonsDom->saveHTML($buttonsDom->getElementsByTagName('button')[0]);

                $btns[] = [
                    "button" => $btn,
                    "stateDescriptor" => $button['stateDescriptor']
                ];

            }

            $hiddenActions = $this->form->field($this->model, 'status')->hiddenInput(['id' => 'workflow-status_id'])->label(false);

            $notificationInput = $this->renderInputForNotify();

            // Renderizzo separatamente una view con tutti i bottoni
            return $this->render("transition-buttons-render", [
                'widgetClass' => $this->containerWidgetClass,
                'resetButton' => $this->closeButton,
                'buttons' => $btns,
                'hiddenActions' => $hiddenActions,
                'notificationInput' => $notificationInput
            ]);
        } else {
            return '';
        }
    }

    /**
     * @param string $fakeStatus
     * @return array
     * @throws InvalidConfigException
     */
    protected function getButtonToRender()
    {
        $this->statuses = $this->getStatuses();

        $module = ($this->module);

        $User = \Yii::$app->getUser();
        //$inState = !$fakeStatus ? $this->model->getWorkflowStatus()->getId() : $fakeStatus;

        $currentStatus = '';
        if (!$this->model->isNewRecord) {
            $currentStatus = $this->getCurrentStatus();
        }

        /** @var Record $nameClass */
        $nameClass = $this->model->className();
        $pk = $this->model->getPrimaryKey();
        $findOneKey = (!empty($pk) ? $pk : $this->model->id);
        $realState = $nameClass::findOne($findOneKey);

        if ($realState) {
            $this->model->status = $realState->status;
        } else {
            $this->model->status = $this->model->status;
        }

        $inState = $this->model->status;

        if (!is_array($this->transitionStatuses)) {
            throw new InvalidConfigException("\\open20\amos\workflow\widgets\WorkflowTransitionSimplifiedButtonsWidget:\n the param transitionStatuses must be an array.");
        }

        $saveButton = [];
        if (empty($this->buttonLabel)) {
            $buttonLabel = $module::t($this->translationCategory, "Salva");
        } else {
            $buttonLabel = $module::t($this->translationCategory, $this->buttonLabel);
        }
        $stateDescriptor = $module::t($this->translationCategory, "#workflow_simplified_button_default_description");

        if (array_key_exists($this->model->status, $this->transitionStatuses)) {

            /** @var Status $state */
            $state = $this->model->getWorkflowSource()->getStatus($this->transitionStatuses[$this->model->status], $this->model);
            $currentState = $this->model->getWorkflowSource()->getStatus($this->model->status, $this->model);
            if ($state && ($this->transitionStatuses[$this->model->status] != $inState)) {
                if ($User->can($state->getId(), ['model' => $this->model])) {
                    $statusesArr[$this->transitionStatuses[$this->model->status]] = $state;
                    $metadati = $state->getMetaData();
                    $metadatiCurrentStatus = $currentState->getMetadata();
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
                    if ((!isset($metadati['hidden']) || (isset($metadati['hidden']) && strtolower($metadati['hidden']) != 'true')) && !$hiddenRoles) {

                        if (isset($metadati[$currentStatus . '_simplifiedDescription'])) {
                            $stateDescriptor = $module::t($this->translationCategory, $metadati[$currentStatus . '_simplifiedDescription']);
                        } elseif (isset($metadati['simplifiedDescription'])) {
                            $stateDescriptor = $module::t($this->translationCategory, $metadati['simplifiedDescription']);
                        }

                        $simplifiedDataConfirm = '';
                        if (isset($metadati[$currentStatus . '_simplifiedMessage'])) {
                            $simplifiedDataConfirm = $module::t($this->translationCategory, $metadati[$currentStatus . '_simplifiedMessage']);
                        } elseif (isset($metadati['simplifiedMessage'])) {
                            $simplifiedDataConfirm = $module::t($this->translationCategory, $metadati['simplifiedMessage']);
                        } else {
                            $simplifiedDataConfirm = (isset($this->dataConfirm) ? $this->dataConfirm : BaseAmosModule::t('amoscore', 'Are you sure you want to change status?'));
                        }

                        $saveButton[] = [
                            'button' => CloseSaveButtonWidget::widget([
                                'model' => $this->model,
                                'layout' => $this->buttonLayout,
                                'buttonSaveLabel' => $buttonLabel,
                                'buttonNewSaveLabel' => $buttonLabel,
                                'buttonClassSave' => 'btn',
                                'buttonId' => $this->transitionStatuses[$this->model->status],
                                'dataConfirm' => $simplifiedDataConfirm
                            ]),

                            'stateDescriptor' => $stateDescriptor,
                        ];
                    } else {

                        $saveButton[] = [
                            'button' => CloseSaveButtonWidget::widget([
                                'model' => $this->model,
                                'layout' => $this->buttonLayout,
                                'buttonSaveLabel' => $buttonLabel,
                                'buttonNewSaveLabel' => $buttonLabel,
                                'buttonClassSave' => 'btn saveBtn',
                            ]),

                            'stateDescriptor' => $stateDescriptor,
                        ];

                    }
                }
            }

        } else {

            $saveButton[] = [
                'button' => CloseSaveButtonWidget::widget([
                    'model' => $this->model,
                    'layout' => $this->buttonLayout,
                    'buttonSaveLabel' => $buttonLabel,
                    'buttonNewSaveLabel' => $buttonLabel,
                    'buttonClassSave' => 'btn saveBtn',
                ]),

                'stateDescriptor' => $stateDescriptor,
            ];

        }

        return $saveButton;
    }
}
