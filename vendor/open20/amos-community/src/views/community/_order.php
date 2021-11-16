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
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\community\models\search\CommunitySearch $model
 * @var yii\widgets\ActiveForm $form
 */

?>
<div class="community-order element-to-toggle" data-toggle-element="form-order">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
    ]);
    echo Html::hiddenInput("enableOrder", "1");
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    ?>

    <div class="col-xs-12">
        <h2><?= AmosCommunity::tHtml('amoscommunity', 'Order by') ?></h2>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderAttribute')->dropDownList($model->getOrderAttributesLabels()) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderType')->dropDownList(
            [
                SORT_ASC => AmosCommunity::t('amoscommunity', 'Ascendent'),
                SORT_DESC => AmosCommunity::t('amoscommunity', 'Descendent'),
            ]
        )
        ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosCommunity::tHtml('amoscommunity', 'Cancel'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-secondary', 'title' => AmosCommunity::t('amoscommunity', 'Cancel order')]) ?>
            <?= Html::submitButton(AmosCommunity::tHtml('amoscommunity', 'Order'), ['class' => 'btn btn-navigation-primary', 'title' => AmosCommunity::t('amoscommunity', 'Start order')]) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>
</div>