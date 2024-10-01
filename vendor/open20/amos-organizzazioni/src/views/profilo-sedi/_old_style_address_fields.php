<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\comuni\widgets\helpers\AmosComuniWidget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\organizzazioni\models\ProfiloSedi $modelSedi
 * @var bool $isView
 */

?>

<div class="col-xs-10">
    <?= $form->field($modelSedi, 'address_text')->textInput(['maxlength' => true]) ?>
</div>
<div class="col-xs-2">
    <?= $form->field($modelSedi, 'cap_text')->textInput(['maxlength' => true]) ?>
</div>
<?= AmosComuniWidget::widget([
    'form' => $form,
    'model' => $modelSedi,
    // TODO COUNTRIES DISABLED decommentare la sezione della nazione se si devono abilitare le nazioni nell'indirizzo
//    'nazioneConfig' => [
//        'attribute' => 'country_id',
//        'class' => 'col-lg-4 col-sm-4'
//    ],
    'provinciaConfig' => [
        'attribute' => 'province_id',
        'class' => 'col-lg-4 col-sm-4'
    ],
    'comuneConfig' => [
        'attribute' => 'city_id',
        'class' => 'col-lg-4 col-sm-4'
    ]
]); ?>
