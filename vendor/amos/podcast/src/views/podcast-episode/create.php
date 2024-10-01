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
 * @var amos\podcast\models\PodcastEpisode $model
 */

$this->title = Yii::t('amoscore', 'Create episode for').' '.$podcast->title;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/podcast']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Podcast Episode'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//----------------------
$this->params['forceBreadcrumbs'][] = [
    'label' => \amos\podcast\Module::t('amospodcast', "Podcast"),
    'url' => '/podcast/podcast/index',
    'route' => '/podcast/podcast/index',
];
$this->params['forceBreadcrumbs'][] = [
    'label' => \amos\podcast\Module::t('amospodcast', "Episodi podcast"),
    'url' => '/podcast/podcast/update?id='. $model->podcast,
    'route' => '/podcast/podcast/update?id='. $model->podcast,
];
$this->params['forceBreadcrumbs'][] = [
    'label' => $this->title,
];
?>
<div class="podcast-episode-create">
    <?= $this->render('_form', [
        'podcast' => $podcast,
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
