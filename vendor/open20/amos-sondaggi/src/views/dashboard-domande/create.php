<?php

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiDomande $model
 */

$this->title = AmosSondaggi::t('amossondaggi', 'Inserisci domanda');
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['/' . $this->context->module->id . '/sondaggi/manage']];

if (isset($url)) {
    if (strstr(yii\helpers\Url::previous(), "sondaggi/sondaggi-domande-pagine")) {
        $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Pagine del sondaggio'), 'url' => $url];
    } else if (strstr(yii\helpers\Url::previous(), "sondaggi/sondaggi-domande/")) {
        $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Domande del sondaggio'), 'url' => $url];
    } else {
        $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => $url];
    }
}
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-domande-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'url' => (isset($url)) ? $url : NULL,
    ])
    ?>
</div>
