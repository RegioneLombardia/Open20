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
* @var backend\modules\operators\models\AgidPersonContentType $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Agid Person Content Type',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/Persona']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Content Type Persona'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agid-person-content-type-create container">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
