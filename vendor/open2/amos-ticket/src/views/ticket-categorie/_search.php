<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket-categorie
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use kartik\select2\Select2;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\utility\TicketUtility;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open2\amos\ticket\models\search\TicketCategorieSearch $model
 * @var yii\widgets\ActiveForm $form
 */

$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;

/** @var AmosTicket $module */
$module = \Yii::$app->getModule('ticket');
?>

<div class="ticket-categorie-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2><?= AmosTicket::t('amosticket', 'Cerca per') ?>:</h2></div>
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>
    <?= Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel); ?>
    <?= Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView')); ?>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'titolo') ?>
    </div>
    <?php
    if (!$module->oneLevelCategories) {
        ?>
        <div class="col-sm-6 col-lg-4">
            <?php
            $data = ArrayHelper::map(TicketUtility::getTicketCategories()->orderBy('titolo')->all(), 'id', 'nomeCompleto');
            echo $form->field($model, 'categoria_padre_id')->widget(Select2::className(), [
                    'data' => $data,
                    'options' => ['placeholder' => AmosTicket::t('amosticket', 'Cerca per categoria ...')],
                    'pluginOptions' => [
                        'tags' => true,
                        'allowClear' => true,
                    ],
                ]
            );
            ?>
        </div>
        <?php
    }
    ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?php /*   <?= Html::resetButton(AmosTicket::t('amosticket', 'Annulla'), ['class' => 'btn btn-secondary']) ?> */ ?>
            <?= Html::a(AmosTicket::t('amosticket', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')], ['class' => 'btn btn-secondary'])
            ?>
            <?= Html::submitButton(AmosTicket::t('amosticket', 'Cerca'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">Ricerca avanzata<br>
        < ?=AmosIcons::show('caret-down-circle');?>
    </p></a-->
    <?php ActiveForm::end(); ?>
</div>
