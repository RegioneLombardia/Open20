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
use yii\helpers\VarDumper;
use yii\web\View;

$idCittadini = 'target_cittadini-id';


$this->registerJs(
    <<<JS

    $("button[id^='scelta-target']").click(function(event) {
        var dataTarget = $(this).attr("data-target-str");      
        var accordionButton= $(this).find("input[id^='target_"+dataTarget+"']");
        if(accordionButton.attr("checked")=="checked"){
            $("#contact-input-"+dataTarget+"-id").val(null);
            accordionButton.toggleClass("not-checked");
            accordionButton.removeAttr("checked");
            $("label[for^='contact-input-"+dataTarget+"-id']").toggleClass("active");
        }else{
            $("#contact-input-"+dataTarget+"-id").val('{$email}');
            accordionButton.attr("checked", "checked");
            accordionButton.toggleClass("not-checked");
            $("label[for^='contact-input-"+dataTarget+"-id']").toggleClass("active");
        } 
        event.preventDefault();  
    });

JS
,
    View::POS_READY
);

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
                <h1>Scelta contatti</h1>
                <p class="tertiary-color">Scegli la tipologia di utente in cui ti rivedi e imposta i canali di contatto</p>
            </div>
            <div class="col-md-3">
                <ul class="wizard-steps text-center">
                    <li class="active-step">
                        <div>1</div>
                    </li>
                    <li class="active-step ">
                        <div>2</div>
                    </li>
                    <li class="active-step current-step">
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


<div class="container py-5 mb-5">
    <?php
    if (!empty($errorMessage)) :
    ?>
        <div class="alert alert-danger" role="alert">
            <?= $errorMessage ?>
        </div>
    <?php
    endif;
    ?>

    <div class="pb-4">

        <div class="form-check form-check-group">
            <input type="checkbox" id="target_cittadini-id" name="StepAppContacts[target_cittadini]" <?= $model->target_cittadini ? 'checked' : ''; ?>>
            <label class="custom-control-label lead color-primary mb-0 border-0 py-0 h-auto" for="target_cittadini-id">
                <span class="h5 font-weight-normal primary-color">Sono interessato a informazioni per <strong>Cittadini</strong></span>
            </label>
        </div>
    </div>
    <div class="pb-4">
        <div class="form-check form-check-group">
            <input type="checkbox" id="target_impresa-id" name="StepAppContacts[target_impresa]" <?= $model->target_impresa ? 'checked' : ''; ?>>
            <label class="custom-control-label lead color-primary mb-0 border-0 py-0 h-auto" for="target_impresa-id">
                <span class="h5 font-weight-normal primary-color">Sono interessato a informazioni per <strong>Imprese</strong></span>
            </label>
        </div>
    </div>
    <div class="pb-4">
        <div class="form-check form-check-group">
            <input type="checkbox" id="target_enti_operatori-id" name="StepAppContacts[target_enti_operatori]" <?= $model->target_enti_operatori ? 'checked' : ''; ?>>
            <label class="custom-control-label lead color-primary mb-0 border-0 py-0 h-auto" for="target_enti_operatori-id">
                <span class="h5 font-weight-normal primary-color">Sono interessato a informazioni per <strong>Enti/operatori</strong></span>
            </label>
        </div>

    </div>



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
            echo Html::a(Yii::t('preferenceuser', 'Indietro'), ['preferences'], [
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


<?php ActiveForm::end(); ?>