<?php

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiRisposteSessioni $model
 */

$this->title = AmosSondaggi::t('amossondaggi', 'Aggiorna {modelClass}', [
    'modelClass' => 'Sondaggi Risposte Sessioni',
]);
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi Risposte Sessioni'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosSondaggi::t('amossondaggi', 'Aggiorna');
?>
<div class="sondaggi-risposte-sessioni-update">
<?= $this->render(
    '_form',
    ['model' => $model,])
?>
</div>
