<?php

use open20\amos\core\helpers\Html;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\IstatComuni $model
 */

$this->title = 'Crea';
$this->params['breadcrumbs'][] = ['label' => 'Comuni', 'url' => ['/comuni/dashboard/index']];
$this->params['breadcrumbs'][] = ['label' => 'Elenco comuni', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="istat-comuni-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
