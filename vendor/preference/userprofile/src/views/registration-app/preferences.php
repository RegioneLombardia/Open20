<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\design\components\bootstrapitalia\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\components\bootstrapitalia\CheckBoxListTopicsIcon;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
?>

<?php
$form = ActiveForm::begin([
    'options' => [
        'id' => 'preferencies-form',
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'enctype' => 'multipart/form-data',
    ],
]);
?>

<div class="lightgrey-bg-c1 py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-9">
          <h1>Scelta preferenze</h1>
          <p class="tertiary-color">Seleziona alcuni argomenti di tuo interesse, visualizzerai l’elenco completo
            una
            volta effettuata la registrazione</p>
        </div>
        <div class="col-md-3">
          
        <ul class="wizard-steps text-center">
            <li class="active-step">
                <div>1</div>
            </li>
            <li class="active-step current-step">
                <div>2</div>
            </li>
            <li class=" ">
                <div>3</div>
            </li>
            <li class="">
                <div>4</div>
            </li>
        </ul>
        </div>
      </div>

    </div>
</div>



<div class="container border-bottom border-light py-5 mb-5 overflow-hidden">
    <?php //echo $form->field($model, 'topics')->checkboxList($model->getCrossTopicsChoiches()); ?>
    <?=$form->field($model, 'topics')->widget(CheckBoxListTopicsIcon::className(), [
        'choices' => $model->getCrossTopics(),
        'classContainer' => 'col-lg-3 col-6',
        'baseIconsUrl' => '/img/icone-tematiche/',   
    ]);?>
</div>

<div class="container d-flex flex-row justify-content-center justify-content-sm-between mb-0 mb-sm-5">
    <div>
        <?php
        echo Html::a(Yii::t('preferenceuser', 'Annulla'), ['/'], [
            'class' => 'btn btn-outline-primary d-none d-sm-block px-5',
            'title' => Yii::t('preferenceuser', 'Torna in home page'),
        ]);
        ?>
    </div>
    <div class="d-flex flex-row ">
        <?php
        echo Html::a(Yii::t('preferenceuser', 'Indietro'), ['personal-data'], [
            'class' => 'btn btn-outline-primary mr-2 px-5',
            'title' => Yii::t('preferenceuser', 'Torna allo step precedente'),
        ]);
        ?>
        <?php
        echo Html::submitButton(
            'Continua',
            ['class' => 'btn btn-primary px-5', 'name' => 'submit-action', 'value' => 'forward']
        );
        ?>
    </div>
</div>

<div class="mobile-wizard-button mb-5 d-flex justify-content-center d-sm-none">
    <?php
    echo Html::a(Yii::t('preferenceuser', 'Annulla registrazione'), ['/'], [
        'class' => 'text-decoration-none text-secondary pt-4 px-5',
        'title' => Yii::t('preferenceuser', 'Torna in home page'),
    ]);
    ?>
</div>

<?php ActiveForm::end();?>