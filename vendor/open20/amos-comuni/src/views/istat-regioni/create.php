<?php

use open20\amos\core\helpers\Html;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\IstatRegioni $model
 */

$this->title = 'Create Istat Regioni';
$this->params['breadcrumbs'][] = ['label' => 'Istat Regioni', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="istat-regioni-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
