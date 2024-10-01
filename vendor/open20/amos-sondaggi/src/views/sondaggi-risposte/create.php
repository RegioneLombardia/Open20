<?php

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiRisposte $model
 */

$this->title = AmosSondaggi::t('amossondaggi', 'Create {modelClass}', [
    'modelClass' => 'Sondaggi Risposte',
]);
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi Rispostes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-risposte-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
