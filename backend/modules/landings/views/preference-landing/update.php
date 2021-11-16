<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/landings/views
 */
/**
 * @var yii\web\View $this
 * @var backend\modules\landings\models\PreferenceLanding $model
 */

$this->title = Yii::t('amoscore', 'Modifica landing');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/landings']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Landing'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="preference-landing-update">

    <?= $this->render('_form', [
        'model' => $model,
        'arrayTemplates' => $arrayTemplates,
        'htmlTemplate' => $htmlTemplate,
        'dataProviderSliderElemImage' => $dataProviderSliderElemImage,
        'dataProviderSliderElemVideo' => $dataProviderSliderElemVideo,
        'dataProviderProtagonists' => $dataProviderProtagonists,
        'dataProviderNews' => $dataProviderNews,
        'dataProviderDocumenti' => $dataProviderDocumenti,
        'dataProviderLinks' => $dataProviderLinks,
        'sondaggiChoices' => $sondaggiChoices,

    ]) ?>

</div>
