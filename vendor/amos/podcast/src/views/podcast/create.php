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
* @var amos\podcast\models\Podcast $model
*/

use amos\podcast\assets\ModulePodcastAsset;

ModulePodcastAsset::register($this);

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Podcast',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/podcast']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Podcast'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podcast-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
