<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\views\agid-service
 * @category   CategoryName
 */

use open20\agid\service\Module;

/**
 * @var yii\web\View $this
 * @var open20\agid\service\models\AgidService $model
 * @var open20\amos\cwh\AmosCwh $moduleCwh
 * @var array $scope
 */

$this->title = Module::t('amosservice', '#update');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="agid-service-update">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
        'moduleCwh' => $moduleCwh,
        'scope' => $scope
    ]) ?>
</div>
