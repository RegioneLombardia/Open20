<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\metadata
 * @category   CategoryName
 */

use amos\sitemanagement\controllers\MetadataController;
use amos\sitemanagement\Module;
use open20\amos\core\forms\CloseButtonWidget;
use open20\amos\core\forms\ContextMenuWidget;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\Metadata $model
 */

$this->title = Module::t('amossitemanagement', 'Page Contents');
$this->params['breadcrumbs'][] = $this->title;

/** @var MetadataController $appController */
$appController = Yii::$app->controller;

?>
<div class="<?= Yii::$app->controller->id ?>-view col-xs-12">
    <div class="row">
        <div class="col-xs-12">
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => '/sitemanagement/metadata/update?id=' . $model->id,
                'actionDelete' => '/sitemanagement/metadata/delete?id=' . $model->id
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'metadataType.type',
                    'key_value',
                    'content',
                ]
            ]) ?>
        </div>
    </div>
    <?= CloseButtonWidget::widget(['urlClose' => $appController->getViewCloseUrl()]) ?>
</div>
