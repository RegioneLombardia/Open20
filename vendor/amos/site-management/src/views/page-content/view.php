<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\page-content
 * @category   CategoryName
 */

use amos\sitemanagement\controllers\PageContentController;
use amos\sitemanagement\Module;
use open20\amos\core\forms\CloseButtonWidget;
use open20\amos\core\forms\ContextMenuWidget;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\PageContent $model
 */

$this->title = Module::t('amossitemanagement', 'Page Contents');
$this->params['breadcrumbs'][] = $this->title;

/** @var PageContentController $appController */
$appController = Yii::$app->controller;

?>
<div class="<?= Yii::$app->controller->id ?>-view col-xs-12">
    <div class="row">
        <div class="col-xs-12">
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => '/sitemanagement/page-content/update?id=' . $model->id,
                'actionDelete' => '/sitemanagement/page-content/delete?id=' . $model->id
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'title',
                    'tag',
                    'content:html',
                ]
            ]) ?>
        </div>
    </div>
    <?= CloseButtonWidget::widget(['urlClose' => $appController->getViewCloseUrl()]) ?>
</div>
