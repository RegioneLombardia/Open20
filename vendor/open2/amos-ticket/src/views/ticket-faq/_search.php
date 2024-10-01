<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket-faq
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
 * @var open2\amos\ticket\models\search\TicketFaqSearch $model
 * @var yii\widgets\ActiveForm $form
 */

$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;

?>

<div class="ticket-faq-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2>Cerca per:</h2></div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>
    <?= Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel); ?>
    <?= Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView')); ?>

    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'domanda') ?></div>
    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'risposta') ?></div>
    <div class="col-sm-6 col-lg-4">
        <?php
        $data = ArrayHelper::map(TicketUtility::getTicketCategories()->orderBy('titolo')->all(), 'id', 'nomeCompleto');
        echo $form->field($model, 'ticket_categoria_id')->widget(Select2::className(), [
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

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosTicket::t('amosticket', 'Annulla'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosTicket::t('amosticket', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <!--a><p class="text-center">Ricerca avanzata<br>
                < ?=AmosIcons::show('caret-down-circle');?>
            </p></a-->
    <?php ActiveForm::end(); ?>
</div>
