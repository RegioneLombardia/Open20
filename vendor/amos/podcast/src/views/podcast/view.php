<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/amos/podcast/src/views
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use amos\podcast\Module;
use open20\amos\core\forms\ContextMenuWidget;
use amos\podcast\assets\ModulePodcastAsset;

ModulePodcastAsset::register($this);

/**
 * @var yii\web\View $this
 * @var amos\podcast\models\Podcast $model
 */

$this->title = ($model->title);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/podcast']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Podcast'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podcast-view">

    <!-- <div class="col-md-12 podcast-title">
        <h2 class="h2"><strong>< ?= $model->title ?></strong> </h2>
        <span class="label" style="background-color: < ?= $model->podcastCategory->color ?>"><?= $model->podcastCategory->name ?></span>
        < ?php if (!empty($model->published_at)) { ?>
            <span><small>< ?= Module::t('amospodcast','Pubblicato il')?>: < ?= \Yii::$app->formatter->asDate($model->published_at, 'long') ?></small></span>
        < ?php } ?>
    </div> -->

    <div class="m-t-20 m-b-20 row d-flex flex-wrap detail-podcast-header p-b-4">
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
                <span class="label label-cat" style="background-color: <?= $model->podcastCategory->color ?>"><?= $model->podcastCategory->name ?></span>
                <?php if (!empty($model->published_at)) { ?>
                    <span><small><?= Module::t('amospodcast', 'Pubblicato il') ?>: <?= \Yii::$app->formatter->asDate($model->published_at, 'long') ?></small></span>
                <?php } ?>
                <div class="m-l-10">
                    <?= ContextMenuWidget::widget([
                        'model' => $model,
                        'actionModify' => "/podcast/podcast/update?id=" . $model->id,
                        'actionDelete' => "/podcast/podcast/delete?id=" . $model->id,
                        'labelDeleteConfirm' => Module::t('amospodcast', 'Sei sicuro di voler cancellare questa notizia?'),

                    ]) ?>
                </div>

            </div>

            <p class="description"><?= $model->description ?></p>
        </div>
    </div>
    <div>
        <h3 class="h4 uppercase m-b-0"><strong><?= Module::t('amospodcast', "Le puntate") ?></strong></h3>
        <?php
        echo \yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item_ep',
            'summary' => false
        ])
        ?>
    </div>
</div>

<!-- COMMENTATO TASTO CHIUDI 
    <div id="form-actions" class="bk-btnFormContainer pull-right">
    < ?= Html::a(Yii::t('amoscore', 'Chiudi'), ['/podcast/podcast/index'], ['class' => 'btn btn-secondary']); ?></div> -->