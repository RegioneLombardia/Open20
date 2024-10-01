<?php

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiDomandeTipologie $model
 */

$this->title = AmosSondaggi::t('amossondaggi', 'Inserisci tipologia');
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Tipologie domande'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-domande-tipologie-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
