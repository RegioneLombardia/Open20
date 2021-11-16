<?php
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\builder\Form;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


$this->title = Yii::t('cruds', 'Aggiorna COMUNI');
$this->params['breadcrumbs'][] = ['label'=>'comuni', 'url'=>'/comuni'];
$this->params['breadcrumbs'][] = $this->title;
?>



<?php $form = ActiveForm::begin(); ?>
<?php $this->beginBlock('principale'); ?>

<?php $this->endBlock('principale'); ?>
<?php $itemsTab[] = [
    'label' => '',
    'content' => $this->blocks['principale'],
];
?>

<?= Tabs::widget(
    [
        'encodeLabels' => false,
        'items' => $itemsTab
    ]
);
?>

<?php echo Html::input('hidden', 'confirm', true); ?>


<legend>Dati che andrebbero inseriti: (totale: <?= count($dati['new']);?>)</legend>

<div class="row">
    <div class="col-xs-3"><strong>Comune Nome</strong></div>
    <div class="col-xs-3"><strong>Codice ISTAT</strong></div>
    <div class="col-xs-3"><strong>Regione</strong></div>
    <div class="col-xs-3"><strong>Provincia</strong></div>
</div>


<?php
if( empty($dati['new'])): ?>
    <div class="row"><div class="col-xs-4">Nessun comune da inserire</div></div>
<?php
else:

    foreach ( $dati['new'] as $k => $array_data ): ?>
        <div class="row">
            <div class="col-xs-3"><?= $array_data['comuneArray'][5]; ?></div>
            <div class="col-xs-3"><?= $array_data['comuneArray'][4]; ?></div>
            <div class="col-xs-3"><?= $array_data['comuneArray'][9]; ?></div>
            <div class="col-xs-3"><?= $array_data['comuneArray'][11]; ?></div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="floatclear"></div>

<legend>Dati che andrebbero aggiornati: (totale: <?= count($dati['update']);?>)</legend>

<div class="row">
    <div class="col-xs-3"><strong>Comune Nome</strong></div>
    <div class="col-xs-3"><strong>Codice ISTAT</strong></div>
    <div class="col-xs-3"><strong>Nuovo Nome</strong></div>
</div>


<?php
if( empty($dati['update'])): ?>
    <div class="row"><div class="col-xs-4">Nessun nome comune da aggiornare</div></div>
    <?php
else:

    foreach ( $dati['update'] as $k => $array_data ): ?>
        <div class="row">
            <div class="col-xs-3"><?= $array_data['comuneArray']['nome']; ?></div>
            <div class="col-xs-3"><?= $array_data['comuneArray']['cod_istat_alfanumerico']; ?></div>
            <div class="col-xs-3"><?= $array_data['nuovo_nome']; ?></div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>





<div class="m-t-30">

    <?php
    if( !empty($dati)):
        echo Html::submitButton('Genera', ['name'=> 'submit', 'value'=> true, 'class' => 'btn btn-primary'] );
    endif; ?>
<?php ActiveForm::end(); ?>

</div>


