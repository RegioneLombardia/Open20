<?php

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiDomandePagine $model
 */

$this->title = AmosSondaggi::t('amossondaggi', '#add_poll_page');
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['/' . $this->context->module->id . '/sondaggi/index']];
if (isset($url)) {
    if (strstr(yii\helpers\Url::previous(), "sondaggi/sondaggi-domande-pagine/")) {
        $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', '#poll_pages'), 'url' => $url];
    }
} else {
    $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', '#polls_pages'), 'url' => ['/' . $this->context->module->id . '/sondaggi-domande-pagine/index']];
}
$this->params['breadcrumbs'][] = AmosSondaggi::t('amossondaggi', '#add_poll_page');
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-domande-pagine-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'url' => (isset($url)) ? $url : NULL
    ])
    ?>
</div>
