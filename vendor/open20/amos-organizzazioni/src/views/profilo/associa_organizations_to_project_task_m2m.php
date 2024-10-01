<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\core\forms\editors\m2mWidget\M2MWidget;

/**
 * @var yii\web\View $this
 * @var \open20\amos\projectmanagement\models\Projects $model
 */

$activity = $model->projectsActivities;

$this->title = $model;
$this->params['breadcrumbs'][] = [
    'label' => \open20\amos\projectmanagement\Module::t('amosproject_management', 'Projects'),
    'url' => ['/project_management']
];
$this->params['breadcrumbs'][] = ['label' => strip_tags($activity->projects)];
$this->params['breadcrumbs'][] = [
    'label' => \open20\amos\projectmanagement\Module::t('amosproject_management', 'Project Activities'),
    'url' => ['/project_management/projects-activities/by-project', 'pid' => $activity->projects->id]
];
$this->params['breadcrumbs'][] = [
    'label' => strip_tags($model),
    'url' => ['update', 'id' => $model->id, '#' => 'tab-organizations']
];
$this->params['breadcrumbs'][] = \open20\amos\projectmanagement\Module::t('amosproject_management', 'Invite Organizations');

$organizationModelClass = \open20\amos\organizzazioni\Module::instance()->model('Profilo');

?>
<?= M2MWidget::widget([
    'model' => $model,
    'modelId' => $model->id,
    'modelData' => $model->getJoinedOrganizations(),
    'modelDataArrFromTo' => [
        'from' => 'id',
        'to' => 'id'
    ],
    'modelTargetSearch' => [
        'class' => $organizationModelClass::className(),
        'query' => $model->projectsActivities->projects->getParticipantsOrganizations()
            ->andFilterWhere([
                'not in',
                'id',
                $model->getProjectsTasksJoinedOrganizationsMms()->select('organization_id')
            ]),//$query,
    ],

    'targetUrlController' => 'projects-tasks',
    'moduleClassName' => \open20\amos\projectmanagement\Module::className(),
    'postName' => 'Project Task',
    'postKey' => 'projects-tasks',
    'targetColumnsToView' => [
        'name' => [
            'attribute' => 'name',
            'label' => \open20\amos\projectmanagement\Module::t('amosproject_management', 'Name'),
            'headerOptions' => [
                'id' => \open20\amos\projectmanagement\Module::t('amosproject_management', 'Name'),
            ],
            'contentOptions' => [
                'headers' => \open20\amos\projectmanagement\Module::t('amosproject_management', 'Name'),
            ]
        ],
        'addressField:raw',
//        'numero_civico',
//        'cap'
    ],
]);
?>
