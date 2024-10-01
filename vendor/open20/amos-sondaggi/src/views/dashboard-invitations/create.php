<?php

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiDomande $model
 */

$this->title = AmosSondaggi::t('amossondaggi', '#insert_invitation_list');
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['/' . $this->context->module->id . '/sondaggi/index']];
$this->params['forceBreadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['/sondaggi/sondaggi/manage']];
$this->params['forceBreadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Liste inviti'), 'url' => ['/sondaggi/dashboard-invitations', 'idSondaggio' => $model->sondaggi_id]];
$this->params['forceBreadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Inserisci lista inviti')];

if (isset($url)) {
    $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', '#invitation_lists'), 'url' => $url];
}
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-invitations">
    <?=
    $this->render('_form', [
        'model' => $model,
        'url' => (isset($url)) ? $url : NULL,
        'scope' => $scope,
    ])
    ?>
</div>
