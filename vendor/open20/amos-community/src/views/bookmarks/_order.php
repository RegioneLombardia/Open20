<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-community/src/views
 */

use \open20\amos\community\AmosCommunity;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\community\models\search\BookmarksSearch $model
 * @var yii\widgets\ActiveForm $form
 */
$currentView = (isset($currentView)) ? $currentView : Yii::$app->request->getQueryParam('currentView');
$community_id = Yii::$app->request->getQueryParam('id');
?>

<div class="news-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <p class="h3"><?= amoscommunity::t('amoscommunity', 'Order by') ?>:</p>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => ['index', 'id' => $community_id],
        'method' => 'get',
        'options' => [
            'class' => 'default-form2'
        ]
    ]);
    //    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    echo Html::hiddenInput("currentView", $currentView);
    echo Html::hiddenInput("BookmarksSearch[titolo]", Yii::$app->request->getQueryParam('BookmarksSearch')['titolo']);
    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderAttribute')->dropDownList(['bookmarks.data_pubblicazione' => 'Data di pubblicazione'])
            ->label(amoscommunity::t('amoscommunity', 'Order attribute')) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderType')->dropDownList(
            [
                SORT_ASC => amoscommunity::t('amoscommunity', 'Ascending'),
                SORT_DESC => amoscommunity::t('amoscommunity', 'Descending'),
            ]
        )
        ->label(amoscommunity::t('amoscommunity', 'Order type'));
        ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(amoscommunity::t('amoscommunity', 'Cancel'), ['index', 'id' => $community_id],
                ['class'=>'btn btn-secondary']) ?>
            <?= Html::submitButton(amoscommunity::t('amoscommunity', 'Sort'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>