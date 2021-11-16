<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;

/**
 * @var yii\web\View $this
 * @var open20\amos\community\models\Community $model
 */

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => AmosCommunity::t('amoscommunity', 'Community'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => strip_tags($model->name), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosCommunity::t('amoscommunity', 'Update');
?>
<div class="community-update">

    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
        'visibleOnEdit' => $visibleOnEdit,
        'tabActive' => $tabActive
    ]) ?>

</div>
