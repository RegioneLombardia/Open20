<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\DiscussioniRisposte $model
 */

use open20\amos\discussioni\AmosDiscussioni;

$this->title = AmosDiscussioni::t('amosdiscussioni', 'Update {modelClass}: ', ['modelClass' => 'Discussioni Risposta',]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => AmosDiscussioni::t('amosdiscussioni', 'Discussioni Risposta'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosDiscussioni::t('amosdiscussioni', 'Update');
?>
<div class=" discussioni-risposte-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
