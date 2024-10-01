<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\widgets;

use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\CwhNodi;
use open20\amos\cwh\models\CwhRegolePubblicazione;
use open20\amos\cwh\utility\CwhUtil;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class Destinatari extends Widget
{
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

    public function init()
    {
        parent::init();
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
        $destinatari = [];
        $networkModels = CwhConfig::find()->andWhere(['<>','tablename','user'])->all();
        $networks = CwhNodi::find()->andWhere(['NOT LIKE', 'id', 'user'])->all();

        $scope = AmosCwh::getInstance()->getCwhScope();
        $scopeFilter = (empty($scope))? false : true;
        /** @var CwhConfig $networkModel */
        foreach ($networkModels as $networkModel){
            $networkObject = Yii::createObject($networkModel->classname);
            /** @var CwhNodi $network */
            foreach ($networks as $network){
                if($scopeFilter){
                    if(isset($scope[$networkModel->tablename]) && $network->record_id == $scope[$networkModel->tablename]) {
                        $destinatari[] = $network;
                    }
                }else {
                    if ($network->classname == $networkModel->classname && $networkObject->isValidated($network->record_id) && $networkObject->isNetworkUser($network->record_id)) {
                        $destinatari[] = $network;
                    }
                }
            }
        }

        if ($this->getModel() instanceof ModelLabelsInterface) {
            $labelSuffix = ' ' . $this->getModel()->getGrammar()->getArticleSingular() . ' ' . $this->getModel()->getGrammar()->getModelSingularLabel();
        } else {
            $labelSuffix = ' ' . AmosCwh::t('amoscwh', 'il contenuto');
        }

        //if we are not under a specific network domain (destinatari and publication rule 3 are not automatically selected)
        if(!$scopeFilter){
            // if publication rule is not set or a publication rule not based on network domain is selected
            if (!isset($this->getModel()->regola_pubblicazione) || $this->getModel()->regola_pubblicazione == CwhRegolePubblicazione::ALL_USERS || $this->getModel()->regola_pubblicazione == CwhRegolePubblicazione::ALL_USERS_WITH_TAGS) {
                //disable input field
                $disabled = true;
            }else{
                $disabled = false;
            }
            return $this->getForm()->field($this->getModel(), 'destinatari')->widget(
                \kartik\select2\Select2::className(), [
                    'name' => $this->getNameField() . '[destinatari]',
                    'data' => ArrayHelper::map($destinatari, 'id', 'text'),
                    'options' => [
                        'multiple' =>  true ,
//                        'value' => $scopeFilter ? $destinatari[0]['id'] : null,
//                        'placeholder' => AmosCwh::t('amoscwh', '#3col_target_placeholder'),
                        'name' => $this->getNameField() . '[destinatari]',
                        'id' => 'cwh-destinatari'
                    ],
                    'pluginOptions' => [
                        'tags' => true,
                        'maximumInputLength' => 10,
                        'disabled' => $disabled,
                    ],
                ]
            )->label(AmosCwh::t('amoscwh', '#3col_target_label{labelSuffix}', ['labelSuffix' => $labelSuffix]));
        } else {
            //if we are under a network domain, publication is blocked for that network
            $destinatariField = $this->getForm()->field($this->getModel(),
                'destinatari')->label(AmosCwh::t('amoscwh', 'Ambito di pubblicazione'));

            $destinatariField->template = "<div class=\"row\">
                <div class=\"col-xs-6\">{label}</div>
                <div class=\"col-xs-6\"> <span class=\"tooltip-field pull-right\"> {hint} </span> <span class=\"tooltip-error-field pull-right\"> {error} </span> </div>
            \n<div class=\"col-xs-12\"><strong>" .$destinatari[0]['text'] . "</strong>{input}</div>
            </div>";
           $destinatariField->hiddenInput(['value' => $destinatari[0]['id'], 'id' => 'cwh-destinatari', 'name' => $this->getNameField() . '[destinatari][]']);
           return $destinatariField;

        }

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
}