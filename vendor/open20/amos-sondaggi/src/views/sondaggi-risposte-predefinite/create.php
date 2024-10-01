<?php

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiRispostePredefinite $model
 */
$this->title = AmosSondaggi::t('amossondaggi', 'Inserisci risposta predefinita');
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['/' . $this->context->module->id . '/sondaggi/index']];
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-risposte-predefinite-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'url' => (isset($url)) ? $url : NULL,
    ])
    ?>
</div>
