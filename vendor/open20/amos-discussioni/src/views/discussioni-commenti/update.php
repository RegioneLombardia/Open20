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
 * @var open20\amos\discussioni\models\DiscussioniCommenti $model
 */

use open20\amos\discussioni\AmosDiscussioni;

$this->title = AmosDiscussioni::t('amosdiscussioni', 'Update {modelClass}: ', [
        'modelClass' => 'Discussioni Commenti',
    ]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => AmosDiscussioni::t('amosdiscussioni', 'Discussioni Commentis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosDiscussioni::t('amosdiscussioni', 'Update');
?>
<div class="discussioni-commenti-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
