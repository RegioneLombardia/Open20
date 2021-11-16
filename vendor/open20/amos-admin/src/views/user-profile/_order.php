<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\search\UserProfileSearch $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="user-profile-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <h2><?= AmosAdmin::t('amosadmin', 'Order by') ?>:</h2>
    </div>
    
    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView')); ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderAttribute')->dropDownList($model->getOrderAttributesLabels()) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderType')->dropDownList([
            SORT_ASC => AmosAdmin::t('amosadmin', 'Ascending'),
            SORT_DESC => AmosAdmin::t('amosadmin', 'Descending')
        ]) ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosAdmin::t('amosadmin', 'Cancel'), [
                Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')
            ], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosAdmin::t('amosadmin', 'Order'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>
</div>
