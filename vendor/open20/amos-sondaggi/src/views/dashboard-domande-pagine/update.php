<?php

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiDomandePagine $model
 */
$this->title = AmosSondaggi::t('amossondaggi', 'Aggiorna pagina');
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['/' . $this->context->module->id . '/sondaggi/index']];
if (isset($url)) {
    $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Pagine del sondaggio'), 'url' => $url];
} else {
    $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Pagine dei sondaggi'), 'url' => ['index']];
}
//$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosSondaggi::t('amossondaggi', 'Aggiorna');
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-domande-pagine-update">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
</div>
