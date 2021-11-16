<?php

use open20\amos\news\AmosNews;
use luya\helpers\Url;
use yii\helpers\Html;
use app\assets\ResourcesAsset;
use app\modules\uikit\assets\FrontAsset;
use open20\amos\attachments\components\AttachmentsList;

//$resourceAsset = ResourcesAsset::register($this);
FrontAsset::register($this);
$urlEncodato = urlencode($urlPagina);

$allegati = AttachmentsList::widget(['model' => $model, 'attribute' => 'attachments',]);

?>

<?php //$model->getSchema(); 
?>

<!--<a href="< ?= $route = Url::toRoute(['/backendobjects']); ?>">Indietro</a>-->
<?php
$url = $resourceAsset->baseUrl . '/img/img_default.jpg';
$newsImage = $model->getNewsImage();
if (!is_null($newsImage)) {
    $url = $newsImage->getWebUrl('square_large', false, true);
}
if (isset($model->newsCategorie) && !empty($model->newsCategorie)) {

    $classCat = 'cat_community';
}
?>
<style>
    .it-header-slim-right-zone {
        display: none !important;
    }
</style>
<div class="wrap-modules detail-news-landing">
    <div class="hero-landing-news it-hero-wrapper it-dark  it-overlay align-items-end">
        <!-- - img-->
        <div class="img-responsive-wrapper">
            <div class="img-responsive">
                <div class="img-wrapper">
                    <?=
                        Html::img($url, [
                            'title' => AmosNews::t('amosnews', 'Immagine della notizia'),
                            'class' => ''
                        ]);
                    ?>
                </div>
            </div>
        </div>
        <!-- - texts-->
        <div class="container">
            <div class="row">
                <div class="col-12 pb-5">
                    <div class="it-hero-text-wrapper bg-dark pb-5">
                        <?php if (isset($model->data_pubblicazione) && !empty($model->data_pubblicazione)) : ?>

                            <span class="text-uppercase"><?= Yii::$app->getFormatter()->asDate($model->data_pubblicazione, 'long') ?></span>
                        <?php endif; ?>
                        <h1 class="no_toc mb-2"><?= $model->getTitle(); ?></h1>

                        <?php if (isset($model->sottotitolo) && !empty($model->sottotitolo)) : ?>
                            <p class="d-block  text-sans-serif"><span class="lead"><?= $model->sottotitolo ?></span></p>
                        <?php endif; ?>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="nop uk-section-default uk-section py-5">
        <div class="container">
            <div class="row">
                <div class="col content-before-sidebar">
                    <?= Html::a('<span class="mdi mdi-arrow-left"></span> INDIETRO', \Yii::$app->request->referrer, [
                        'class' => ''
                    ]) ?>
                    <div class="capolettera uk-section-default uk-visible@xl uk-section mt-4">
                        <?php if (isset($model->descrizione) && !empty($model->descrizione)) : ?>
                            <div class="mb-5"><?= $model->descrizione ?></div>
                        <?php endif; ?>

                    </div>
                    <div class="social-share-box border-top py-4">
                        <h6 class="mb-3 text-uppercase">Condividi</h6>
                        <div class="container-social-share d-flex">
                            <div class="share-wrap-button">
                                <a class="social-network fb" href="http://www.facebook.com/sharer.php?u=<?= $urlEncodato ?>" target="_blank" title="Condividi su Facebook" aria-label="Condividi su Facebook">
                                    <span class="mdi mdi-facebook md-36 m-1" title="Condividi su Facebook"></span>
                                </a>
                            </div>
                            <div class="share-wrap-button">
                                <a class="social-network tw" href="http://twitter.com/share?url=<?= $urlEncodato ?>" target="_blank" title="Condividi su Twitter" aria-label="Condividi su Twitter">
                                    <span class="mdi mdi-twitter md-36 m-1" title="Condividi su Twitter"></span>
                                </a>
                            </div>
                            <div class="share-wrap-button">
                                <a class="social-network lk" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $urlEncodato ?>" target="_blank" title="Condividi su Linkedin" aria-label="Condividi su Linkedin">
                                    <span class="mdi mdi-linkedin md-36 m-1" title="Condividi su Linkedin"></span>
                                </a>
                            </div>
                            <div class="share-wrap-button">
                                <a class="social-network email" href="mailto:?subject=<?= urlencode($model->title) ?>&body=<?= $urlEncodato ?>" target="_blank" title="Condividi via mail" aria-label="Condividi via mail">
                                    <span class="mdi mdi-email md-36 m-1" title="Condividi via email"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php

                if (count($model->hasMultipleFiles('attachments')->all()) > 0) :

                ?>
                    <div class="offset-md-1 col-md-3 ">
                        <div class="uk-container attachments-detail allegati-container col-md-12 col-xs-12">
                            <h5 class="mb-4">ALLEGATI</h5>
                            <div class="uk-list">

                                <?php
                                $Attachments = [];

                                $Attachments = $model->hasMultipleFiles('attachments')->all();

                                /** @var File $documento */
                                foreach ($Attachments as $documento) :
                                ?>
                                    <div class="mb-3">
                                    <a href="<?= $documento->getWebUrl(); ?>" class="el-link" title="<?= $documento['name'].'.'. $documento['type']?>"><span class="news-allegato-name"><?= $documento['name'].'.'. $documento['type']?></span></a>
                                    <span class="news-allegato-kb small text-muted">(<?= round($documento['size'] / 1024, 2) ?>kb)</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <!--						<h5 class="mb-4">ALLEGATI</h5>-->
                        <!--                        < ? = AttachmentsList::widget([-->
                        <!--                            'model' => $model,-->
                        <!--                            'attribute' => 'attachments',-->
                        <!--                        ]) ? >-->
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>