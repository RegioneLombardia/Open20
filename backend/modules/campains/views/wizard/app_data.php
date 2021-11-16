<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/compains/views 
 */
/**
* @var yii\web\View $this
* @var backend\modules\campains\models\PreferenceCampain $model
*/

use backend\modules\campains\utility\CampainsUtility;
use yii\bootstrap4\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;


$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

$this->title = Yii::t('amoscore', 'STEP 2', [
    'modelClass' => 'Preference Campain',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/campains']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Campain'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Campagna');
?>


<div class="heading border-bottom mb-4 pb-2">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="mb-0 pr-2 text-muted text-uppercase">Nuova campagna: App (dati generali)</h3>
        </div>
        <div>
            <div class="step">5</div>
        </div>
    </div>
</div>

<div class="preference-campain-step-2">

    <?php
        $form = ActiveForm::begin([
            'options' => ['class' => 'needs-validation']
        ]);
    ?>  
        
        <!-- START Form Content -->
        <?= 
            $this->render('_parts/_form_app_detail', [
                'form' => $form,
                'model' => $model,
                // 'template' => $template,
                'fid' => NULL,
                'dataField' => NULL,
                'dataEntity' => NULL,
            ]) 
        ?>
        <!-- END Form Content -->


        <!-- Wizard Button -->
        <div class="d-flex justify-content-between mt-5">
            <?php
                $returnToUrl = ['/campains/wizard/create-campain?id='.$model->preference_campain_id];
                if (CampainsUtility::campainHaveSmsChannel($model->preferenceCampain)) {
                    $returnToUrl = ['/campains/wizard/sms-data?id='.$model->preference_campain_id];
                } elseif(CampainsUtility::existNewsletterChannel($model->preferenceCampain)) {
                    $returnToUrl = ['/campains/wizard/newsletter-content?id='.$model->preference_campain_id];
                }
            ?>

                <?= Html::a('Precedente', $returnToUrl, ['class'=>'btn btn-primary']) ?>
            
                <?= Html::submitButton('Prosegui', ['class' => 'btn btn-primary'])?>
                
        </div>

    <?php ActiveForm::end(); ?>

</div>
