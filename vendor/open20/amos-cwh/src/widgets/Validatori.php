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
use open20\amos\core\user\User;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\search\CwhNodiSearch;
use kartik\widgets\Select2;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class Validatori extends Widget
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

        if (!count(AmosCwh::getInstance()->validateOnStatus)) {
            throw new InvalidConfigException(AmosCwh::t('amoscwh', 'E\' necessario impostare il campo validateOnStatus nella configuazione della CWH per il model {classname}', [
                'classname' => get_class($this->model)
            ]));
        } else {
            $config = AmosCwh::getInstance()->validateOnStatus[get_class($this->model)];
        }
        $isUpdate = false;
        if (!in_array($this->model->{$config['attribute']}, $config['statuses'])) {
            $isUpdate = true;
        }

        $data = [];
        $nodi = CwhNodiSearch::findByModel($this->getModel());
        $data = ArrayHelper::merge($data, ArrayHelper::map(
            $nodi, 'id', 'text'
        ));
        $validators = [];
        $i = 0;
        $scope = AmosCwh::getInstance()->getCwhScope();
        $scopeFilter = (empty($scope))? false : true;
        $myown_rule = null;
        foreach ($data as $key => $value){
            if($scopeFilter){
                $pos = strpos($key,'-');
                $scopeKey = substr($key, 0 ,$pos);
                if(isset($scope[$scopeKey]) && $scope[$scopeKey] == $nodi[$i]->record_id) {
                    $validators[$key] = $data[$key];
                }
            }else {
                if ( strpos($key, 'user-') !== false) {
                    $user = User::findOne($nodi[$i]->record_id);
                    if (!is_null($user)) {
                        if (\Yii::$app->getUser()->id == $user->id) {
                            $myown_rule = array($key => AmosCwh::t('amoscwh', 'My own'));
                        } else {
                            $data[$key] = $user->getProfile()->getNomeCognome();
                        }
                    }
                    $validators[$key] = $data[$key];
                } else {
                    $networkObject = \Yii::createObject($nodi[$i]->classname);
                    if ($isUpdate || $networkObject->isValidated($nodi[$i]->record_id)) {
                        $validators[$key] = $data[$key];
                    }
                }
            }
            $i++;
        }
        $data = $validators;
        /***
         * for add My own key at the beginning of array.
         */
        if(!empty($myown_rule)){
            $data = $myown_rule + $data;
        }

        if(count($data) == 0) {
            $data['user-' . \Yii::$app->getUser()->getId()] = AmosCwh::t('amoscwh', 'My own');
        }

        if ($this->getModel() instanceof ModelLabelsInterface) {
            $labelSuffix = ' ' . $this->getModel()->getGrammar()->getArticleSingular() . ' ' . $this->getModel()->getGrammar()->getModelSingularLabel();
        } else {
            $labelSuffix = ' ' . AmosCwh::t('amoscwh', 'il contenuto');
        }

        return $this->getForm()->field($this->getModel(), 'validatori')->widget(
            Select2::className(), [
                'name' => $this->getNameField() . '[validatori]',
                'disabled' => $isUpdate && !$this->model->isNewRecord,
                'data' => $data,
                'options' => [
                    //'placeholder' => AmosCwh::t('amoscwh', '#3col_sender_placeholder'),
                    'name' => $this->getNameField() . '[validatori]'
                ],
                'pluginOptions' => [

                    'maximumInputLength' => 10
                ],
            ]
        )->label(AmosCwh::t('amoscwh', '#3col_sender_label{labelSuffix}', ['labelSuffix' => $labelSuffix]));
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