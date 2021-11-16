<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modues/operators/views 
 */
/**
* @var yii\web\View $this
* @var backend\modules\operators\models\AgidPersonType $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Agid Person Type',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/person']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Tipologia Persona'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agid-person-type-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
