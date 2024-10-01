<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\sondaggi
 * @category   CategoryName
 */

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\Sondaggi $model
 * @var \open20\amos\cwh\AmosCwh $moduleCwh
 * @var array $scope
 */

$this->title = AmosSondaggi::t('amossondaggi', 'Aggiorna sondaggio');
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosSondaggi::t('amossondaggi', 'Aggiorna');
?>
<div class="sondaggi-update">
    <?=
    $this->render('_form', [
        'model' => $model,
        'public' => isset($public) ? $public : NULL,
        'moduleCwh' => $moduleCwh,
        'scope' => $scope
    ])
    ?>
</div>
