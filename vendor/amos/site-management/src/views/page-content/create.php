<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\page-content
 * @category   CategoryName
 */

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\PageContent $model
 */

$this->title = Module::t('amoscore', 'Create');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="<?= Yii::$app->controller->id ?>-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
