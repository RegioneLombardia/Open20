<?php
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\builder\Form;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('cruds', 'Aggiorna Codici Catastali');
$this->params['breadcrumbs'][] = ['label'=>'comuni', 'url'=> '/comuni'];
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

<legend>Dati che andrebbero aggiornati:</legend>

<div class="row">
  <div class="col-xs-4"><strong>Comune Nome</strong></div>
  <div class="col-xs-4"><strong>Vecchio Cod.Catastale</strong></div>
  <div class="col-xs-4"><strong>Nuovo Cod.Catastale</strong></div>
</div>

<?php
if( empty($dati)): ?>
  <div class="row"><div class="col-xs-4">Nessun comune da aggiornare</div></div>
<?php
else:

  foreach ( $dati as $k => $array_data ): ?>
    <div class="row">
      <div class="col-xs-4"><?= $array_data['comuneArray']['nome']; ?></div>
      <div class="col-xs-4"><?= $array_data['comuneArray']['codice_catastale']; ?></div>
      <div class="col-xs-4"><?= $array_data['new_codice_catastale']; ?></div>
    </div>

  <?php endforeach; ?>
<?php endif; ?>

<div class="floatclear"></div>
<?php echo Html::input('hidden', 'confirm', true); ?>

<div class="m-t-30">
<?php
  if (!empty($dati)) :
      echo Html::submitButton('Genera', ['name'=> 'submit', 'value'=> true, 'class' => 'btn btn-primary'] );
  endif;
?>
<?php ActiveForm::end(); ?>

</div>