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

use open20\amos\core\controllers\CrudController;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\record\Record;
use open20\amos\core\utilities\WorkflowTransitionWidgetUtility;
use kartik\base\Widget;
use raoul2000\workflow\base\Status;

/**
 * Class WorkflowTransitionStateDescriptorWidget
 * Renders the widget useful to view the workflow status of a model.
 *
 * @package open20\amos\core\forms
 */
class WorkflowTransitionStateDescriptorWidget extends Widget
{
    /**
     * @var string $containerWidgetClass
     */
    public $containerWidgetClass = 'workflow-transition-state-descriptor-widget col-xs-12';

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
    public $iconOptions = '';//['style' => 'font-size: 50px;'];

    /**
     * @var string $buttonLayout
     */
    public $buttonLayout = ''; //"<div id=\"workflow-form-actions\" class=\"pull-right\">{buttonSubmit}</div>";

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
     * @var bool $viewWidgetOnNewRecord If true force to view the widget when the model is in new record state
     */
    public $viewWidgetOnNewRecord = false;

    /**
     * @var Record $model
     */
    private $model;

    /**
     * @var ActiveForm $form
     */
    private $form;

    /**
     * @var string $workflowId
     */
    private $workflowId;

    /**
     * @var array $metadata
     */
    private $metadata;

    /**
     * @var AmosModule $module
     */
    private $module;

    /**
     * @var string $translationCategory
     */
    private $translationCategory;

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
            $this->translationCategory = preg_replace('/[^aA-zZ]/i', '', 'amos' . $moduleName);
        }

        parent::init();
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
        if (isset(\Yii::$app->params['hideWorkflowTransitionWidget']) && \Yii::$app->params['hideWorkflowTransitionWidget']) {
            return '';
        }
        $content = '';
        $module = ($this->module);
        if ($this->model->hasWorkflowStatus()) {
            $status = $module::t($this->translationCategory, WorkflowTransitionWidgetUtility::getLabelStatus($this->model));
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

            $this->getView()->registerJs($js, \yii\web\View::POS_READY);

            $content = '<div class="' . $this->containerWidgetClass . '">'
                . '<div' . (isset($this->classDivMessage) ? ' class="' . $this->classDivMessage . '"' : '') . '>'
                . '<span>' . ((isset($status) && (strlen($status) > 0)) ? $status : (isset($this->initialLabel) ? $this->initialLabel : BaseAmosModule::t('amoscore', 'Not set'))) . '</span>'
                . '</div>';
            $content .= '</div>';
        } elseif ($this->viewWidgetOnNewRecord && $this->model->isNewRecord && $this->model->{$this->model->statusAttribute}) {
            /**
             * This piece of code is used for print widget when the model is new and not yet saved.
             * It print the status of the model if it is set.
             */
            $modelStatus = $this->model->{$this->model->statusAttribute};
            $status = $module::t($this->translationCategory, WorkflowTransitionWidgetUtility::getLabelStatus($this->model, $modelStatus));
            $content = '
            <div class="' . $this->containerWidgetClass . '">
                    <div' . (isset($this->classDivMessage) ? ' class="' . $this->classDivMessage . '"' : '') . '>
                        <span>' . ((isset($status) && (strlen($status) > 0)) ? $status : (isset($this->initialLabel) ? $this->initialLabel : BaseAmosModule::t('amoscore', 'Not set'))) . '</span>
                    </div>
            </div>
            ';
        }
        return $content;
    }

    /**
     * This method return the workflow status icon if is set in the metadata field.
     * @param string $key
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    private function getIconStatus($key = '')
    {
        if (!$key) {
            $key = $this->model->getWorkflowStatus()->getId();
        }
        /** @var Status $state */
        $state = $this->model->getWorkflowSource()->getStatus($key, $this->model);
        $icon = '';
        if ($state) {
            $metadati = $state->getMetaData();
            if (isset($metadati['icon'])) {
                $icon = $metadati['icon'];
            }
        }
        return $icon;
    }
}
