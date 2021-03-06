<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\community
 * @category   CategoryName
 */

namespace lispa\amos\projectmanagement\widgets;

use lispa\amos\community\models\CommunityUserMm;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\module\AmosModule;
use lispa\amos\projectmanagement\models\Projects;
use lispa\amos\projectmanagement\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class ProjectOrganizationsWidget
 * @package lispa\amos\community\widgets
 */
class ParticipantsTasksWidget extends Widget
{

    /**
     * @var Projects $model
     */
    public $model = null;

    /**
     * (eg. ['PARTICIPANT'] - thw widget will show only member with role participant)
     * @var array Array of roles to show
     */
    public $showRoles = null;

    /**
     * @var bool $forceReadOnly
     */
    public $forceReadOnly = false;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!$this->model) {
            throw new InvalidConfigException($this->throwErrorMessage('model'));
        }
    }

    protected function throwErrorMessage($field)
    {
        return Module::t('amosproject_management', 'Wrong widget configuration: missing field {field}', [
            'field' => $field
        ]);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {

        $model = $this->model;
        //pr($model->getJoinedOrganizations()->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
        $widget = \lispa\amos\core\forms\editors\m2mWidget\M2MWidget::widget([
            'model' => $model,
            'modelId' => $model->id,
            'modelData' => $model->getJoinedOrganizations(),
            'overrideModelDataArr' => true,
            'forceListRender' => true,
            'createAssociaButtonsEnabled' => true,
            'disableCreateButton' => true,
            'disableAssociaButton' => false,
            'btnAssociaLabel' => Module::t('amosproject_management', 'Add Organizations'),
            //'createNewBtnLabel' => Module::t('amosproject_management', 'Create New Task'),
            'actionColumnsTemplate' => '{deleteRelation}',
            'deleteRelationTargetIdField' => 'id',
            'targetUrl' => '/project_management/projects-tasks/add-organization',
            'moduleClassName' => Module::className(),
            'targetUrlController' => 'projects-tasks',
            'deleteActionName' => 'delete-organization',
            'postName' => 'Activity',
            'postKey' => 'ProjectsActivities',
            'permissions' => [
                'add' => ($this->forceReadOnly ? null : 'PROJECT_MANAGER'),
            ],
            'actionColumnsButtons' => [
                Html::button('Test')
            ],
            'itemsMittente' => [
                'name' => [
                    'attribute' => 'name',
                    'label' => Module::t('amosproject_management', 'Name'),
                    'headerOptions' => [
                        'id' => Module::t('amosproject_management', 'name'),
                    ],
                    'contentOptions' => [
                        'headers' => Module::t('amosproject_management', 'name'),
                    ]
                ],
                'indirizzo',
//                'numero_civico',
//                'cap',
                [
                    'attribute' => 'organization',
                    'format' => 'raw',
                    'label' => Module::t('amosproject_management', 'Reference Organization'),
                    'value' => function ($model, $index, $dataColumn) {
                        $labelClass = 'hidden';
                        $buttonClass = '';
                        $refferenceOrganization = $this->model->organization;
                        if (isset($refferenceOrganization->id)) {
                            if ($refferenceOrganization->id == $model->id) {
                                $labelClass = '';
                                $buttonClass = 'hidden';
                            } else {
                                $labelClass = 'hidden';
                                $buttonClass = '';
                            }
                        }

                        return
                            Html::tag('span', Module::t('amosproject_management', 'Refference Organization'), [
                                'class' => 'refference-label ' . $labelClass,
                                'data-id' => $model->id
                            ]) .
                            Html::button(Module::t('amosproject_management', 'Set As Reference'), [
                                'class' => 'btn btn-primary refference-button ' . $buttonClass,
                                'data-id' => $model->id
                            ]);
                    }
                ],
            ]
        ]);

        return $widget;
    }
}