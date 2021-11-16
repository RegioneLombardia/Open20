<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-newsletter/src/views 
 */
/**
* @var yii\web\View $this
* @var amos\newsletter\models\Newsletter $model
*/

$this->title = Yii::t('amosnewsletter', 'Update') .' '. $model->name;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/newsletter']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Newsletter'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="newsletter-update">

    <?= $this->render('_form', [
        'fields' => $fields,
        'lists' => $lists,
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
