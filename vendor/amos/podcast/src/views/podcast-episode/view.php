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
use amos\podcast\assets\ModulePodcastAsset;

ModulePodcastAsset::register($this);

/**
 * @var yii\web\View $this
 * @var amos\podcast\models\PodcastEpisode $model
 */

use open20\amos\core\forms\ContextMenuWidget;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/podcast']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Podcast Episode'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// ------------------------------
$this->params['forceBreadcrumbs'][] = [
    'label' => \amos\podcast\Module::t('amospodcast', "Podcast"),
    'url' => '/podcast/podcast/index',
    'route' => '/podcast/podcast/index',
];
$this->params['forceBreadcrumbs'][] = [
    'label' => \amos\podcast\Module::t('amospodcast', "Episodi podcast"),
    'url' => '/podcast/podcast/view?id=' . $model->podcast,
    'route' => '/podcast/podcast/view?id=' . $model->podcast,
];
$this->params['forceBreadcrumbs'][] = [
    'label' => $this->title,
];


$model->registerMetatagSeo();

?>
<section class="podcast-header">
    <div class="podcast-header-container my-3">
        <div class="podcast-detail-header">
            <div class="podcast-head-container">
                <!-- <h3 class="h1 podcast-episode-title m-b-10">< ?= $model->title ?></h3> -->
                <?= Html::a(\open20\amos\core\icons\AmosIcons::show('arrow-left') . Module::t('amospodcast', "Torna al podcast") . ': ' . $podcast->title, ['/podcast/podcast/view', 'id' => $model->podcast_id], [
                    'class' => 'link-all align-items-center',
                    'title' => Module::t('amospodcast', "Vai al podcast {nomepodcast}", [
                        'nomepodcast' => $podcast->title
                    ])
                ]) ?>




            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3 podcast-image-container">
                    <img class="img-responsive" alt="immagine podcast" src="<?= !empty($model->mainImage) ? $model->mainImage->getWebUrl() : '/img/img_default.jpg' ?>">
                </div>
                <div class="col-xs-12 col-sm-8 col-md-9 d-flex flex-column podcast-text-container">
                    <div>
                        <div class="podcast-title m-t-0">
                            <?php if (!empty($model->published_at)) { ?>
                                <span class="text-muted"><?= Module::t('amospodcast', 'Pubblicato il') ?>
                                        : <?= \Yii::$app->formatter->asDate($model->published_at, 'long') ?></span>
                            <?php } ?>


                            <?= ContextMenuWidget::widget([
                                'model' => $model,
                                'actionModify' => "/podcast/podcast-episode/update?id=" . $model->id,
                                'actionDelete' => "/podcast/podcast-episode/delete?id=" . $model->id,
                                'labelDeleteConfirm' => Module::t('amospodcast', 'Sei sicuro di voler cancellare questa notizia?'),

                            ]) ?>

                        </div>

                    </div>
                    <p class="description m-t-10"><?= $model->abstract ?></p>
                    <div class="tag-container">
                        <?php $tags = $model->getTags() ?>
                        <?php if (count($tags) > 0) { ?>
                            <p class=""><?= Module::t('amospodcast', "Tag") ?>: </p>
                            <?php foreach ($tags as $tag) { ?>
                                <p class="tag"><?= $tag->nome ?></p>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="podcast-puntata">
    <div class="row">
        <div class="puntata-container col-md-7">
            <h3 class="h4"><?= Module::t('amospodcast', "Ascolta la puntata") ?>
                <?= Module::t('amospodcast', "(DURATA: {durata}')", [
                    'durata' => $model->duration
                ]) ?>
            </h3>
            <?php echo \amos\podcast\widgets\PlayerPodcast::widget([
                'model' => $model,
                'pluginOptions' => ['data-width' => '100%', 'data-height' => '400px']
            ]); ?>
        </div>
        <div class="puntata-social-container col-md-4 col-md-offset-1">
            <div class="share">
                <!-- <h4 class="h3">< ?= Module::t('amospodcast', "Condividi") ?></h4> -->
                <?php
                $url = !empty(\Yii::$app->params['platform']['backendUrl']) ? \Yii::$app->params['platform']['backendUrl'] : "";
                echo \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                    'mode' => \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_NORMAL,
                    'configuratorId' => 'socialShare',
                    'model' => $model,
                    'url' =>  \Yii::$app->urlManager->createAbsoluteUrl('/podcast/podcast-episode/public?id=' . $model->id),
                    'title' => $model->title,
                    'description' => $model->abstract,
                    'imageUrl' => !empty($model->mainInage) ? $model->mainInage->getWebUrl('square_small') : '',
                ]);
                ?>
                <!-- <ul class="share-list">
                    <li>
                        <a href="#" class="social-icon" title="Condividi su Facebook" aria-label="Condividi su Facebook">
                            <span class="am am-facebook am-2"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-icon" title="Condividi su Twitter" aria-label="Condividi su Twitter">
                            <span class="am am-twitter am-2"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-icon" title="Condividi su Linkedin" aria-label="Condividi su Linkedin">
                            <span class="am am-linkedin am-2"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-icon" title="Condividi via mail" aria-label="Condividi via mail">
                            <span class="am am-email am-2"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-icon" title="Condividi ad un altro utente" aria-label="Condividi ad un altro utente">
                            <span class="am am-account am-2"></span>
                        </a>
                    </li>
                </ul> -->
                <div class="widget-body-content like-container">
                    <?= \open20\amos\core\forms\editors\likeWidget\LikeWidget::widget([
                        'model' => $model,
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="puntata-info-container col-xs-12">
            <span>
                <?= $model->description ?>
            </span>
        </div>
    </div>
</section>
<section class="podcast-other-episodes">
    <h3 class="h4"><?= Module::t('amospodcast', "Other episodes") ?></h3>
    <?php
    $podcast = $model->podcast;
    $query = $podcast->getOtherEpisodes($model->id);
    $dataProvider = new \yii\data\ActiveDataProvider([
        'query' => $query
    ]);
    echo \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '../podcast/_item_ep',
        'summary' => false

    ]);
    ?>
</section>
<!-- <div id="form-actions" class="bk-btnFormContainer pull-right">
    < ?= Html::a(Yii::t('amoscore', 'Chiudi'), ['/podcast/podcast/view', 'id' => $model->podcast_id], ['class' => 'btn btn-secondary']); ?>
</div> -->