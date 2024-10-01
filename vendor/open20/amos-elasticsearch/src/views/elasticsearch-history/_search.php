<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\news\AmosNews;
use open20\amos\news\models\News;
use open20\amos\admin\AmosAdmin;
use kartik\select2\Select2;
use open20\amos\core\forms\editors\Select;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\search\NewsSearch $model
 * @var yii\widgets\ActiveForm $form
 */


/** @var AmosNews $newsModule */

// enable open search section
$enableAutoOpenSearchPanel = isset(\Yii::$app->params['enableAutoOpenSearchPanel'])
    ? \Yii::$app->params['enableAutoOpenSearchPanel']
    : false;
?>

<div class="news-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><p class="h3"><?= AmosNews::t('amosnews', 'Cerca per') ?>:</p></div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'id' => 'news_form_' . $model->id,
            'class' => 'form',
            'enctype' => 'multipart/form-data',
        ]
    ]);

    echo Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));

    ?>

    <div class="col-sm-6 col-lg-6">
        <?= $form->field($model, 'search_text') ?>
    </div>
    <div class="col-sm-6 col-lg-6">
        <?= $form->field($model, 'dateFrom')->widget(DateControl::className(),[
                'type' => DateControl::FORMAT_DATE
        ])->label(\open20\elasticsearch\Module::t('amoselasticsearch', "Data ricerca dal"))?>
    </div>









    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosNews::t('amosnews', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-outline-primary']) ?>
            <?= Html::submitButton(AmosNews::tHtml('amosnews', 'Cerca'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">Ricerca avanzata<br>
        < ?=AmosIcons::show('caret-down-circle');?>
    </p></a-->

    <?php ActiveForm::end(); ?>

</div>
