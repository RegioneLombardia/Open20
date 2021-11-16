<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use open20\amos\events\AmosEvents;
use open20\amos\core\icons\AmosIcons;
use backend\modules\campains\models\PreferenceCampain;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use backend\modules\campains\Module;

$this->title                   = Yii::t('campains', 'Nuova Campagna: destinatari e canali');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

$js = <<<JS
    $('#switch-settings').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
            $('#settings-div').show();
        }
        else {
           $('#settings-div').hide();
        }
    });
JS;
$this->registerJs($js);
//pr($model->getErrors());
?>

<div class="heading border-bottom mb-4 pb-2">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="mb-0 pr-2 text-muted"><?= (empty(trim($this->title)) ?: $this->title) ?></h3>
        </div>
        <div>
            <div class="step">1</div>
        </div>
    </div>
</div>

<?php
    $form = ActiveForm::begin(['class' => 'needs-validation', 'id' => 'form-search']);
?>

<?= $form->errorSummary($model) ?>


<?= $this->render('_parts/_base_info',
        [
            'model' => $model,
            'form' => $form,
            'tag' => $tag,
            'profili' => $profili,
            'wizardAsset' => $wizardAsset,
            'spriteAsset' => $spriteAsset,
            'modelSearch' => $modelSearch,
        ])
?>

    <!-- 
    <div class="buttons row">
        < ?php
            echo WizardPrevAndContinueButtonWidget::widget([
                'model' => $model,
                'viewPreviousBtn' => false,
                'cancelUrl' => '',
            ])
        ?> 
    </div> -->

    <div class="row mt-5">
        <div class="col-md-6 text-left">
            <?= Html::a('Precedente', ['/campains/preference-campain/index'], ['class'=>'btn btn-primary']) ?>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-group">
                <?=Html::submitButton('Prosegui', ['class' => 'btn btn-primary'])?>
            </div>
        </div>
    </div>




<?php ActiveForm::end(); ?>


