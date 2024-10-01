<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/amos/podcast/src/views 
 */
/**
* @var yii\web\View $this
* @var amos\podcast\models\PodcastCategory $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Podcast Category',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/podcast']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Podcast Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podcast-category-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
