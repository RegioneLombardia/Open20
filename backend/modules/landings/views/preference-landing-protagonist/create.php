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
* @var backend\modules\landings\models\PreferenceLandingProtagonist $model
*/

$this->title = Yii::t('amoscore', 'Crea speaker');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/landings']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Landing Protagonist'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preference-landing-protagonist-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
