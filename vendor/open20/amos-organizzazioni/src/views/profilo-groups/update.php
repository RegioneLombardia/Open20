<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-groups
 * @category   CategoryName
 */

use open20\amos\organizzazioni\Module;

/**
 * @var yii\web\View $this
 * @var \open20\amos\organizzazioni\models\ProfiloGroups $model
 */

$this->title = Module::t('amosorganizzazioni', 'Update group');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profilo-groups-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
