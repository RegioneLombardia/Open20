<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh\widgets
 * @category   CategoryName
 */

namespace open20\amos\cwh\widgets;

use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhRegolePubblicazione;
use open20\amos\cwh\utility\CwhUtil;
use kartik\select2\Select2;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class RegolaPubblicazione
 * @package open20\amos\cwh\widgets
 */
class RegolaPubblicazione extends Widget
{
    protected $data = [];

    protected $default = null;
    /**
     * @var \yii\widgets\ActiveForm $form
     */
    protected $form = null;

    /**
     * @var \yii\db\ActiveRecord $model
     */
    protected $model = null;

    /**
     * @var string
     */
    protected $nameField = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $cwhModule = AmosCwh::getInstance();
        $scope = $cwhModule->getCwhScope();

        //if we are working under a specific network scope (eg. community dashboard)
        $scopeFilter = (empty($scope) ? false : true);

        $regolePubblicazioneQuery = CwhUtil::getPublicationRulesQuery();
        $regolePubblicazione = $regolePubblicazioneQuery->all();
        //if working in a network scope only rules based on the network membership are available
        if ($scopeFilter) {
            $this->setDefault($regolePubblicazione[0]);
        }
        $this->setData($regolePubblicazione);

        if (!isset($this->nameField)) {
            $refClass = new \ReflectionClass(get_class($this->getModel()));
            $this->setNameField($refClass->getShortName());
        }
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param \yii\db\ActiveRecord $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    public function run()
    {
        if ($this->getModel() instanceof ModelLabelsInterface) {
            $labelSuffix = ' ' . $this->getModel()->getGrammar()->getArticleSingular() . ' ' . $this->getModel()->getGrammar()->getModelSingularLabel();
        } else {
            $labelSuffix = ' ' . AmosCwh::t('amoscwh', 'il contenuto');
        }
        if (count($this->getData()) > 1) {
            /** @var CwhRegolePubblicazione $defaultRule */
            $defaultRule = $this->getDefault();
            $value = (!empty($defaultRule) ? $defaultRule->id : null);
            $widget = $this->getForm()->field($this->getModel(), 'regola_pubblicazione')->widget(
                Select2::className(), [
                    'name' => $this->getNameField() . '[regola_pubblicazione]',
                    'data' => ArrayHelper::map($this->getData(), 'id', 'nome'),
                    'options' => [
                        //'placeholder' => AmosCwh::t('amoscwh','#3col_recipients_placeholder'),
                        'name' => $this->getNameField() . '[regola_pubblicazione]',
                        'id' => 'cwh-regola_pubblicazione',
                        'value' => $value,
                    ]
                ]
            )->label(AmosCwh::t('amoscwh', '#3col_recipients_label{labelSuffix}', ['labelSuffix' => $labelSuffix]));
        } else {
            $regolaDiPubblicazioneField = $this->getForm()->field($this->getModel(),
                'regola_pubblicazione')->label(AmosCwh::t('amoscwh', 'Regola di pubblicazione'));
            $RegolaDiPubblicazione = $this->getData()[0];

            $regolaDiPubblicazioneField->template = "<div class=\"row\">
                <div class=\"col-xs-6\">{label}</div>
                <div class=\"col-xs-6\"> <span class=\"tooltip-field pull-right\"> {hint} </span> <span class=\"tooltip-error-field pull-right\"> {error} </span> </div>
            \n<div class=\"col-xs-12\"><strong>" . $RegolaDiPubblicazione['nome'] . "</strong>{input}</div>
            </div>";

            $widget = $regolaDiPubblicazioneField->hiddenInput(['value' => $RegolaDiPubblicazione['id'], 'id' => 'cwh-regola_pubblicazione',]);
        }

        return $widget;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return \yii\widgets\ActiveForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param \yii\widgets\ActiveForm $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getNameField()
    {
        return $this->nameField;
    }

    /**
     * @param string $nameField
     */
    public function setNameField($nameField)
    {
        $this->nameField = $nameField;
    }

    /**
     * @return null
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param null $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }
}
