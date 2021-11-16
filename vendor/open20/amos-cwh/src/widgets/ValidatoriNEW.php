<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\widgets;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\search\CwhNodiSearch;
use kartik\widgets\Select2;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class ValidatoriNEW
 * @package open20\amos\cwh\widgets
 */
class ValidatoriNEW extends Validatori
{
    public
        $moduleCwh;

    /**
     * @inheritDoc
     */
    public function run()
    {
        if (!count($this->moduleCwh->validateOnStatus)) {
            throw new InvalidConfigException(
                AmosCwh::t(
                    'amoscwh',
                    'E\' necessario impostare il campo validateOnStatus nella configuazione della CWH per il model {classname}',
                    ['classname' => get_class($this->model)]
                )
            );
        }

        $config = $this->moduleCwh->validateOnStatus[get_class($this->model)];

        $isUpdate = false;
        if (!in_array($this->model->{$config['attribute']}, $config['statuses'])) {
            $isUpdate = true;
        }

        $nodi = CwhNodiSearch::findByModel($this->getModel());
        $data = ArrayHelper::merge(
            [],
            ArrayHelper::map(
                $nodi, 'id', 'text'
            )
        );

        $i = 0;
        $validators = [];
        $scope = $this->moduleCwh->getCwhScope();
        $scopeFilter = (empty($scope)) ? false : true;
        $myown_rule = null;
            
        $name = \Yii::$app->user->identity->profile->getNomeCognome();

        $networkModels = CwhConfig::find()
            ->andWhere(['<>', 'tablename', 'user'])
            ->all();

        $networkIds = [];
        $usersId = [];

        $uid = Yii::$app->user->id;
        if (!$this->model->isNewRecord) {
            $uid = $this->model->created_by;
        }

        foreach ($networkModels as $networkModel) {
            $networkIds[$networkModel->classname] = [];
            $usersId[$networkModel->classname] = [];

            $networkObject = new $networkModel->classname;

            $i = 0;
            foreach ($data as $key => $value) {
                if ($scopeFilter) {
                    $pos = strpos($key, '-');
                    $scopeKey = substr($key, 0, $pos);
                    if (isset($scope[$scopeKey]) && $scope[$scopeKey] == $nodi[$i]->record_id) {
                        $validators[$key] = $name; //. ' (' . $data[$key] . ')'; // nomecognome utente
                    }
                } else {
                    if (strpos($key, 'user-') !== false) {
                        $user = User::findOne($nodi[$i]->record_id);
                        if (!is_null($user)) {
                            $myown_rule = array($key => $name);
                        }
                        $validators[$key] = $name; //.' for '.$data[$key];
                    } else {
                        if (!$scopeFilter) {
                            if ($nodi[$i]->classname == $networkModel->classname) {
                                $networkIds[$nodi[$i]->classname][$nodi[$i]->record_id] = $nodi[$i]->record_id;
                                $usersId[$nodi[$i]->classname][$nodi[$i]->record_id] = $uid;
                            }
                        }
                    }
                }
                $i++;
            }

            // Retrieve all records corresponding to the $networkModel->classname via sql
            $rows = [];
            if (isset($networkIds[$networkModel->classname]) && $networkObject->hasMethod('getListOfRecipients')) {
                $rows = $networkObject->getListOfRecipients(
                    array_keys($networkIds[$networkModel->classname]),
                    $usersId[$networkModel->classname]
                );
            }

            if (($isUpdate) || ($rows)) {
                if (array_key_exists('open20\amos\core\interfaces\OrganizationsModelInterface', class_implements($networkObject))) {
                    if(!empty($rows)) {
                        $key = AmosCwh::t('amoscwh', 'Organizzazioni');
                        $validators[$key] = [];
                        foreach ($rows as $k => $v) {
                            $validators[$key][$v['id']] = $name . ' (' . $v['name'] . ')';
                        }
                    }
                }
            }
        }
        
        /**
         * Check if workflow is on or off
         * if the case add the user itself as validator
         */        
        $key = 'user-' . $uid;
        $user = User::findOne($uid);
        if (($myown_rule == null) && ($scope == 'community') ) {
            $hideWorkflow = isset(Yii::$app->params['hideWorkflowTransitionWidget']) && Yii::$app->params['hideWorkflowTransitionWidget'];
            /** @var AmosCommunity $amosCommunity */
            $amosCommunity = Yii::$app->getModule('community');
            $hideWorkflow = $hideWorkflow || $amosCommunity->bypassWorkflow;
            if (($hideWorkflow === false) && (!is_null($user))) {
                $myown_rule = array($key => $name);
            }
            $validators[$key] = $name; //.' for '.$data[$key];
        } else if ((count($validators) == 0) && (!is_null($user))) {
            $myown_rule = array($key => $name);
            $validators[$key] = $name; //.' for '.$data[$key];
        }
        
        $data = $validators;

        /**
         * for add My own key at the beginning of array.
         */
        if (!empty($myown_rule)) {
            $data = array_merge($myown_rule, $data);
        }

        $creator = $this->model->created_by;
        $creatorName = '';
        if ($creator) {
            $creatorKey = 'user-' . $creator;
            if (!array_key_exists($creator, $data)) {
                $user = UserProfile::findOne(['user_id' => $creator])->getNomeCognome();
                $creatorName = $user;
                $data[$creatorKey] = $user;
            }
        }

        if($this->getModel()->isNewRecord)
        {
            //get the first element of an array
            reset($data);
            $first_key = key($data);
            $value = $first_key ? $first_key : [];
        }
        else
        {
            $value = $this->getModel()->validatori;
        }
        
        return $this->getForm()->field($this->getModel(), 'validatori')->widget(
            Select2::className(),
            [
                'name' => $this->getNameField() . '[validatori]',
                'disabled' => $isUpdate && !$this->model->isNewRecord,
                'data' => $data,
                'options' => [
                    'id' => 'validatori-cwh',
                    'placeholder' => AmosCwh::t('amoscwh', '#3col_sender_placeholder'),
                    'name' => $this->getNameField() . '[validatori]',
                    'value' => $value,
                    'disabled' => !$this->getModel()->isNewRecord,
                ],
                'pluginOptions' => [
                    'maximumInputLength' => 10
                ],
            ]
        )->label(AmosCwh::t('amoscwh', 'Scegli la firma'));
    }
}
