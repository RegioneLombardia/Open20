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

$this->title = Yii::t('amoscore', 'Crea landing');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/landings']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Landing'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preference-landing-create">
    <?= $this->render('_form', [
        'model' => $model,
        'arrayTemplates' => $arrayTemplates,
        'htmlTemplate' => $htmlTemplate,
        'sondaggiChoices' => $sondaggiChoices,
    ]) ?>

</div>
