<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-sedi
 * @category   CategoryName
 */

use open20\amos\organizzazioni\Module;

/**
 * @var yii\web\View $this
 * @var open20\amos\organizzazioni\models\ProfiloSedi $model
 */

$this->title = Module::t('amosorganizzazioni', 'Update headquarter');
$this->params['breadcrumbs'][] = ['label' => Module::t('amosorganizzazioni', 'Profilo Sedi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="profilo-sedi-update">
    <?= $this->render('_form', [
        'model' => $model
    ]) ?>
</div>
