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

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Newsletter',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/newsletter']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Newsletter'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newsletter-create">
    <?= $this->render('_form', [
        'fields' => $fields,
        'lists' => $lists,
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
