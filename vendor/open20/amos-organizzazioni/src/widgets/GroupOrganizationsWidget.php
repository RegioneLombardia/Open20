<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\widgets
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\widgets;

use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\JsUtility;
use open20\amos\organizzazioni\controllers\ProfiloGroupsController;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloGroups;
use open20\amos\organizzazioni\models\ProfiloTypesPmi;
use open20\amos\organizzazioni\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\web\View;
use yii\widgets\PjaxAsset;

/**
 * Class GroupOrganizationsWidget
 * @package open20\amos\organizzazioni\widgets
 */
class GroupOrganizationsWidget extends Widget
{
    /**
     * @var ProfiloGroups $model
     */
    public $model = null;
    
    /**
     * @var Module $organizzazioniModule
     */
    public $organizzazioniModule = null;
    
    /**
     * @var string $addPermission
     */
    public $addPermission = 'PROFILOGROUPS_UPDATE';
    
    /**
     * @var string $manageAttributesPermission
     */
    public $manageAttributesPermission = 'PROFILOGROUPS_UPDATE';
    
    /**
     * @var string $gridId
     */
    public $gridId = 'group-organizations-grid';
    
    /**
     * @var bool|false true if we are in edit mode, false if in view mode or otherwise
     */
    public $isUpdate = false;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->organizzazioniModule = Module::instance();
        if (!$this->model) {
            throw new InvalidConfigException($this->throwErrorMessage('model'));
        }
    }
    
    /**
     * @param string $field
     * @return string
     */
    protected function throwErrorMessage($field)
    {
        return Module::t(
            'amosorganizzazioni',
            'Wrong widget configuration: missing field {field}',
            ['field' => $field]
        );
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $gridId = $this->gridId;
        $model = $this->model;
        $profiloGroupId = $model->id;
        $params = [];
        $params['isUpdate'] = $this->isUpdate;
        
        $url = \Yii::$app->urlManager->createUrl(
            [
                '/organizzazioni/profilo-groups/group-organizations',
                'id' => $model->id,
                'classname' => $model->className(),
                'params' => $params
            ]
        );
        $searchPostName = 'searchGroupOrganizationsName';
        
        $js = JsUtility::getSearchM2mFirstGridJs($gridId, $url, $searchPostName);
        PjaxAsset::register($this->getView());
        $this->getView()->registerJs($js, View::POS_LOAD);
        
        /** @var Profilo $profiloModel */
        $profiloModel = $this->organizzazioniModule->createModel('Profilo');
        
        $itemsMittente = [
            'name',
            'partita_iva',
            'codice_fiscale',
            'typology' => [
                'attribute' => 'tipologiaDiOrganizzazione.name',
                'label' => $profiloModel->getAttributeLabel('tipologia_di_organizzazione')
            ]
        ];
        
        if ($this->organizzazioniModule->enableWorkflow && Yii::$app->user->can('PROFILO_GROUPS_MANAGER')) {
            $itemsMittente['status'] = [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var \open20\amos\organizzazioni\models\Profilo $model */
                    return $model->getWorkflowBaseStatusLabel();
                }
            ];
        }
        
        if ($this->isUpdate) {
            $actionColumnsTemplate = '{deleteRelation}';
            $actionColumnButtons = [
                'deleteRelation' => function ($url, $model) use ($profiloGroupId) {
                    /** @var \open20\amos\organizzazioni\models\Profilo $model */
                    $url = '/organizzazioni/profilo-groups/elimina-m2m';
                    $urlDelete = Yii::$app->urlManager->createUrl([
                        $url,
                        'id' => $profiloGroupId,
                        'targetId' => $model->id
                    ]);
                    $loggedUser = Yii::$app->getUser();
                    $btnDelete = '';
                    if ($loggedUser->can($this->addPermission, ['model' => $this->model])) {
                        $btnDelete = Html::a(
                            AmosIcons::show('close', ['class' => '']),
                            $urlDelete,
                            [
                                'title' => Module::t('amosorganizzazioni', 'Delete'),
                                'data-confirm' => Module::t('amosorganizzazioni', '#group_organizations_widget_ask_remove_organization_from_group'),
                                'class' => 'btn btn-danger-inverse'
                            ]
                        );
                    }
                    return $btnDelete;
                },
            ];
        } else {
            $actionColumnsTemplate = '';
            $actionColumnButtons = [];;
        }
        
        $query = $this->getM2mWidgetQuery($model, $searchPostName);
        
        $widget = M2MWidget::widget([
            'model' => $model,
            'modelId' => $model->id,
            'modelData' => $query,
            'overrideModelDataArr' => true,
            'gridId' => $gridId,
            'firstGridSearch' => true,
            'createAssociaButtonsEnabled' => $this->isUpdate,
            'disableCreateButton' => true,
            'btnAssociaLabel' => Module::t('amosorganizzazioni', '#group_organizations_widget_add_organizations'),
            'btnAssociaClass' => 'btn btn-primary',
            'deleteRelationTargetIdField' => 'profilo_id',
            'targetUrl' => '/organizzazioni/profilo-groups/associa-m2m',
            'targetUrlController' => 'profilo-groups',
            'moduleClassName' => Module::className(),
            'postName' => 'Profilo',
            'postKey' => 'profilo',
            'permissions' => [
                'add' => $this->addPermission,
                'manageAttributes' => $this->manageAttributesPermission
            ],
            'actionColumnsButtons' => $actionColumnButtons,
            'actionColumnsTemplate' => $actionColumnsTemplate,
            'itemsMittente' => $itemsMittente,
        ]);
        
        $html = Html::tag('div',
            Html::tag('h2', Module::t('amosorganizzazioni', '#group_organizations'), ['class' => 'subtitle-form m-b-15 group-widget-title']) .
            $widget,
            [
                'id' => $gridId,
                'data-pjax-container' => $gridId . '-pjax',
                'data-pjax-timeout' => 10000,
                'class' => 'col-xs-12 table-responsive group-widget-container',
            ]
        );
        
        return $html;
    }
    
    /**
     * @param ProfiloGroups $model
     * @param string $searchPostName
     * @return \yii\db\ActiveQuery
     */
    protected function getM2mWidgetQuery($model, $searchPostName)
    {
        /** @var ProfiloGroupsController $appController */
        $appController = Yii::$app->controller;
        $query = $appController->getGroupOrganizationsQuery($model);
        
        if (isset($_POST[$searchPostName])) {
            $searchName = $_POST[$searchPostName];
            if (!empty($searchName)) {
                $profiloTable = Profilo::tableName();
                $profiloTypesPmiTable = ProfiloTypesPmi::tableName();
                $query->innerJoinWith('tipologiaDiOrganizzazione');
                $query->andWhere([
                    'or',
                    ['like', $profiloTable . '.name', $searchName],
                    ['like', $profiloTable . '.partita_iva', $searchName],
                    ['like', $profiloTable . '.codice_fiscale', $searchName],
                    ['like', $profiloTypesPmiTable . '.name', $searchName],
                ]);
            }
        }
        
        return $query;
    }
}
