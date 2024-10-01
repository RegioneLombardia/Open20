<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket-faq
 * @category   CategoryName
 */

use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\TextEditorWidget;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\utility\TicketUtility;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var open2\amos\ticket\models\TicketFaq $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="ticket-faq-form col-xs-12 nop">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'ticket_categoria_id')->widget(Select::className(), [
        'auto_fill' => false,
        'options' => [
            'placeholder' => AmosTicket::t('amosticket', '#ticket_category_field_placeholder'),
            'id' => 'ticket_categoria_id-id',
            'disabled' => false,
            'value' => $model->ticket_categoria_id
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'data' =>
            ArrayHelper::map(TicketUtility::getTicketCategories(null, false)
                ->orderBy('titolo')->all(), 'id', 'nomeCompleto'),
    ]); ?>
	<div class="row">
		<div class="col-lg-12 col-sm-12">
            <?=
            $form->field($model, 'domanda')->widget(
                TextEditorWidget::className(),
                [
                    'options' => ['placeholder' => AmosTicket::t('amosticket', 'Inserisci...')],
                    'clientOptions' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        /*'plugins' => $rtePlugins,
                        'toolbar' => $rteToolbar,*/
                        'buttonsHide' => [
                            'file'
                        ],
                    ],
                ]
            )
            ?>

		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-sm-12">
            <?=
            $form->field($model, 'risposta')->widget(
                TextEditorWidget::className(),
                [
                    'options' => ['placeholder' => AmosTicket::t('amosticket', 'Inserisci...')],
                    'clientOptions' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        /* 'plugins' => $rtePlugins,
                         'toolbar' => $rteToolbar,*/
                        'buttonsHide' => [
                            'file'
                        ],
                    ],
                ]
            )
            ?>

		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-sm-12">
            <?php
            $moduleSeo = \Yii::$app->getModule('seo');
            if (isset($moduleSeo)) :
                ?>
                <?=
                AccordionWidget::widget([
                    'items' => [
                        [
                            'header' => AmosTicket::t('amosticket', '#settings_seo_title'),
                            'content' => \open20\amos\seo\widgets\SeoWidget::widget([
                                'contentModel' => $model,
                            ]),
                        ]
                    ],
                    'headerOptions' => ['tag' => 'h2'],
                    'options' => Yii::$app->user->can('ADMIN') ? [] : ['style' => 'display:none;'],
                    'clientOptions' => [
                        'collapsible' => true,
                        'active' => 'false',
                        'icons' => [
                            'header' => 'ui-icon-amos am am-plus-square',
                            'activeHeader' => 'ui-icon-amos am am-minus-square',
                        ]
                    ],
                ]);
                ?>
            <?php endif; ?>
		</div>
	</div>
	<div class="clearfix"></div>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
