<?php

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiRisposteSessioni $model
 */

$this->title = AmosSondaggi::t('amossondaggi', 'Create {modelClass}', [
    'modelClass' => 'Sondaggi Risposte Sessioni',
]);
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi Risposte Sessioni'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-risposte-sessioni-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
