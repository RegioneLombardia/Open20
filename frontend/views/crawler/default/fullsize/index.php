<?php

use luya\helpers\Url;
use yii\widgets\LinkPager;
use open20\amos\core\module\BaseAmosModule;
use open20\design\assets\BootstrapItaliaDesignAsset;
use app\modules\cms\helpers\CmsHelper;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

/* @var $query string The lookup query encoded */
/* @var $this \luya\web\View */
/* @var $provider \yii\data\ActiveDataProvider */
?>

<?php
$prevPaginatorIcon = '<svg class="icon icon-sm"><use xlink:href="' . $currentAsset->baseUrl . '/sprite/material-sprite.svg#chevron-left"></use>';
$nextPaginatorIcon = '<svg class="icon icon-sm"><use xlink:href="' . $currentAsset->baseUrl . '/sprite/material-sprite.svg#chevron-right"></use>';
$startPaginatorIcon = '<svg class="icon icon-sm"><use xlink:href="' . $currentAsset->baseUrl . '/sprite/material-sprite.svg#chevron-double-left"></use>';
$endPaginatorIcon = '<svg class="icon icon-sm"><use xlink:href="' . $currentAsset->baseUrl . '/sprite/material-sprite.svg#chevron-double-right"></use>';
?>

<div class="container results-index">

    <div class="page-search-results">
        <h2 class="mt-15"><?= BaseAmosModule::t('amosapp', 'Cerca') ?></h2>
        <div class="row">
            <div class="search-form col-12">
                <form class="searchpage__searched-form row" action="<?= Url::toRoute(['/crawler']); ?>" method="get">
                    <input class="col-10" id="search" name="query" type="search" value="<?= $query ?>">
                    <input class="col-2 btn btn-xs btn-primary" type="submit" value="Cerca" />
                </form>
            </div>
            <?php if ($provider->totalCount != 0) : ?>
                <div class="search-results-label col-12">
                    <p><?= BaseAmosModule::t('amosapp', 'Sono emersi {numResults} risultati che corrispondono alla tua ricerca', ['numResults' => $provider->totalCount]) ?></p>
                </div>
            <?php else : ?>
                <?php if (isset($provider->query)) : ?>
                    <div class="search-results-label col-12">
                        <p><?= BaseAmosModule::t('amosapp', 'Non sono emersi risultati che corrispondono alla tua ricerca') ?></p>
                    </div>
                <?php else : ?>
                    <div class="col-12">
                        <p>
                            <?= BaseAmosModule::t('amosapp', 'Per effettuare una ricerca inserisci nella riga in alto parole o frasi quali tag, argomenti, titoli, community,... e premi INVIO sulla tastiera oppure fai clic sul bottone "Cerca".') ?>
                        </p>
                        <div class="vh-50">
                            <?php
                            $url = '/img/cerca.png';

                            $contentImage = CmsHelper::img($url, [
                                'class' => 'el-image',
                            ]);
                            ?>
                            <?= $contentImage; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="results">
        <div class="row">

            <div class="col-12">

                <div class="link-list-wrapper multiline">
                    <ul class="link-list">

                        <?php
                        foreach ($provider->models as $item) : /* @var $item \luya\crawler\models\Index */
                        ?>
                            <li>
                                <a class="list-item right-icon p-0" href="<?= $item->url ?>" title="<?= BaseAmosModule::t('amosapp', 'Vedi dettaglio su {titleResult}', ['titleResult' => $item->title]) ?>">
                                    <span class="h5 text-black mb-0"><?= $item->getHighlightedTitle($query) ?></span>
                                    <svg class="icon icon-primary icon-right">
                                        <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-arrow-right"></use>
                                    </svg>
                                    <span class="sr-only"><?= BaseAmosModule::t('amosapp', 'Vedi dettaglio') ?></span>
                                    <p><?= $item->preview($query) ?></p>
                                </a>
                            </li>
                            <li><span class="divider"></span></li>
                        <?php endforeach; ?>

                    </ul>
                </div>

            </div>

            <div class="col-12 mt-4">
                <div class="d-flex justify-content-center">
                    <?= LinkPager::widget([
                        'pagination' => $provider->pagination,
                        'options' => [
                            'class' => 'pagination pagination-design'
                        ],
                        'pageCssClass' => 'page-item',
                        'activePageCssClass' => 'border border-primary rounded',
                        'nextPageCssClass' => 'page-item border border-white rounded',
                        'prevPageCssClass' => 'page-item border border-white rounded',
                        'firstPageCssClass' => 'page-item border border-white rounded',
                        'lastPageCssClass' => 'page-item border border-white rounded',
                        'prevPageLabel' => $prevPaginatorIcon,
                        'nextPageLabel' => $nextPaginatorIcon,
                        'firstPageLabel' => $startPaginatorIcon,
                        'lastPageLabel' => $endPaginatorIcon,
                        'linkOptions' => [
                            'class' => 'page-link'
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>