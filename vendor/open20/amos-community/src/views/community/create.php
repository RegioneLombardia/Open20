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

$this->title = AmosCommunity::t('amoscommunity', 'New Community');
if(!is_null($model->parent_id)){
    $this->title = AmosCommunity::t('amoscommunity', '#new_subcommunity');
}
$this->params['breadcrumbs'][] = ['label' => AmosCommunity::t('amoscommunity', 'Community'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud community-create">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
