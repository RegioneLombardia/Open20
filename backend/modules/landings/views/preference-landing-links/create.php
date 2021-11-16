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
 * @var backend\modules\landings\models\PreferenceLandingLink $model
 */

use backend\modules\landings\Module;

//$this->title = Module::t('landings', 'Crea l.oink', [
//    'modelClass' => 'Preference Landing Link',
//]);
////$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/landings']];
////$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Landing Link'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;


$this->title = Module::t('landings', 'Crea link');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/landings']];
$this->params['breadcrumbs'][] = ['label' => Module::t('landings','Preference Landing Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preference-landing-link-create">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
