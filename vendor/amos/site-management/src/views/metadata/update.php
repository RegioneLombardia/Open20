<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\metadata
 * @category   CategoryName
 */

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\Metadata $model
 */

$this->title = Module::t('amoscore', 'Update');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="<?= Yii::$app->controller->id ?>-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
