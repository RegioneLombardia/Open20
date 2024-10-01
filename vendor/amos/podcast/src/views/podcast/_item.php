<?php

/**
 * @var $model \amos\podcast\models\PodcastEpisode
 */

use yii\helpers\Html;
use amos\podcast\Module;
/* use amos\podcast\assets\ModulePodcastAsset;
ModulePodcastAsset::register($this); */
use open20\amos\core\forms\ContextMenuWidget;
?>
<?php
$url = '/img/img_default.jpg';
if ($model->mainImage) {
  $url = $model->mainImage->getWebUrl();
}
?>


<div class="m-t-20 m-b-20 row detail-podcast-header p-b-4">
  <div class="col-sm-4 col-md-3">
    <?php
    $url = '/img/img_default.jpg';
    if (!empty($model->mainImage)) {
      $url = $model->mainImage->getWebUrl();
    } ?>
    <?= Html::img($url, [
      'class' => 'img-responsive m-b-20'
    ]); ?>
  </div>
  <div class="col-sm-8 col-md-9">
    <div class="podcast-title">
      <?= ContextMenuWidget::widget([
        'model' => $model,
        'actionModify' => "/podcast/podcast/update?id=" . $model->id,
        'actionDelete' => "/podcast/podcast/delete?id=" . $model->id,
        'labelDeleteConfirm' => Module::t('amospodcast', 'Sei sicuro di voler cancellare questa notizia?'),

      ]) ?>
      <span class="label label-cat" style="background-color: <?= $model->podcastCategory->color ?>"><?= $model->podcastCategory->name ?></span>
      <?php if (!empty($model->published_at)) { ?>
        <span><small><?= Module::t('amospodcast', 'Pubblicato il') ?>: <?= \Yii::$app->formatter->asDate($model->published_at, 'long') ?></small></span>
      <?php } ?>
    </div>
    <a href="/podcast/podcast/view?id=<?= $model->id ?>" title="Vai alla puntata <?= $model->title ?>" class="link-list-title">
      <h4 class="title-three-line m-t-10"><?= $model->title ?></h4>
    </a>
    <p class="description title-two-line"><?= $model->description ?></p>
    <a href="/podcast/podcast/view?id=<?= $model->id ?>" title="Vai all'elenco delle puntate" class="readmore mb-0">Elenco puntate</a>
  </div>
</div>