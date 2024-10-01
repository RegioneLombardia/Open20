<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-groups
 * @category   CategoryName
 */

use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\organizzazioni\controllers\ProfiloGroupsController;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\Module;

/**
 * @var yii\web\View $this
 * @var \open20\amos\organizzazioni\models\ProfiloGroups $model
 */

$this->title = Module::t('amosorganizzazioni', '#associa_m2m_groups_page_title');
$this->params['breadcrumbs'][] = $this->title;

/** @var Module $organizzazioniModule */
$organizzazioniModule = Module::instance();

/** @var ProfiloGroupsController $appController */
$appController = Yii::$app->controller;

/** @var Profilo $profiloModel */
$profiloModel = $organizzazioniModule->createModel('Profilo');
$modelData = $model->getGroupProfilos();

?>

<?= M2MWidget::widget([
    'model' => $model,
    'modelId' => $model->id,
    'modelData' => $modelData,
    'modelDataArrFromTo' => [
        'from' => 'id',
        'to' => 'id'
    ],
    'modelTargetSearch' => [
        'class' => $organizzazioniModule->model('Profilo'),
        'query' => $appController->getAssociaM2mQuery($model),
    ],
    'gridId' => 'group-organizations-grid',
    'viewSearch' => true,
    'targetUrlController' => 'profilo-groups',
    'moduleClassName' => Module::className(),
    'postName' => 'Profilo',
    'postKey' => 'profilo',
    'targetColumnsToView' => [
        'name',
        'partita_iva',
        'codice_fiscale',
        'typology' => [
            'attribute' => 'tipologiaDiOrganizzazione.name',
            'label' => $profiloModel->getAttributeLabel('tipologia_di_organizzazione')
        ]
    ],
]);
