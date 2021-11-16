<?php

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiRisposte $model
 */

$this->title = AmosSondaggi::t('amossondaggi', 'Aggiorna {modelClass}', [
    'modelClass' => 'Sondaggi Risposte',
]);
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi Rispostes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosSondaggi::t('amossondaggi', 'Aggiorna');
?>
<div class="sondaggi-risposte-update">
<?= $this->render(
    '_form', 
    ['model' => $model,]) 
?>
</div>
