<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\discussioni\AmosDiscussioni;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\DiscussioniRisposte $model
 * @var yii\widgets\ActiveForm $form
 */

if (isset($_GET['DiscussioniRisposte'])) {
    
    if (isset($_GET['DiscussioniRisposte']['discussioni_topic_id'])) {
        $model->discussioni_topic_id = $_GET['DiscussioniRisposte']['discussioni_topic_id'];
    }
}

$this->title = AmosDiscussioni::t('amosdiscussioni', 'Rispondi a {discussioneNome}', [
    'discussioneNome' => $model->getDiscussioniTopic()->one()->titolo,
]);
$this->params['breadcrumbs'][] = ['label' => $model->getDiscussioniTopic()->one()->titolo, 'url' => ['/discussioni/discussioni-topic/partecipa', 'id' => $model->getDiscussioniTopic()->one()->id
]];
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="discussioni-risposte-form col-xs-12">
    
    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]); ?>
    <?php $this->beginBlock('dettagli'); ?>

    <div class="col-xs-12">
        
        <?= $form->field($model, 'testo')->textarea(['rows' => 6]) ?>

    </div>

    <div class="col-xs-12 nop" style="display: none">
        
        <?php
        if ($model->discussioni_topic_id) {
            echo $form->field($model, 'discussioni_topic_id')->hiddenInput();
        } else {
            echo $form->field($model, 'discussioni_topic_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(open20\amos\discussioni\models\DiscussioniTopic::find()->all(), 'id', 'titolo'),
                ['prompt' => AmosDiscussioni::t('amosdiscussioni', 'Seleziona')]
            );
        }
        
        ?>
    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock('dettagli'); ?>
    
    <?php $itemsTab[] = [
        'label' => AmosDiscussioni::t('amosdiscussioni', 'Dettagli') . ' ',
        'content' => $this->blocks['dettagli'],
    ];
    ?>
    
    <?= Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]
    );
    ?>
    
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
