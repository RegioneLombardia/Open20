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

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Preference Campain',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/campains']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Campain'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preference-campain-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
