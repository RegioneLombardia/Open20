<?php

use luya\helpers\Url;
use yii\widgets\LinkPager;

/* @var $query string The lookup query encoded */
/* @var $this \luya\web\View */
/* @var $provider \yii\data\ActiveDataProvider */
?>

<div class="page-search-results container-padding">
    <h2>Cerca</h2>
    <div class="uk-container">
        <div class="search-form col-xs-12 col-sm-10">
            <form class="searchpage__searched-form" action="<?= Url::toRoute(['/crawler/default/index']); ?>"
                  method="get">
                <input id="search" name="query" type="search" value="<?= $query ?>">
                <input type="submit" value="Cerca"/>
            </form>
        </div>
        <?php if ($provider->totalCount != 0) : ?>
            <div class="search-results-label col-xs-12 col-sm-2">
                <p><?= $provider->totalCount; ?> Risultati</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="uk-container results">
    <div class="row">
        <div class="col-xs-12">
            <?php
			foreach ($provider->models as $item): /* @var $item \luya\crawler\models\Index */
			?>
                <div class="col-xs-12 item-results">
                    <h3><?= $item->getHighlightedTitle($query) ?></h3>
                    <p><?= $item->preview($query); ?></p>
                    <a href="<?= $item->url; ?>" class="read-more"><span class="am am-chevron-right am-2"></span></a>
                </div>
            <?php endforeach; ?>

            <?= LinkPager::widget(['pagination' => $provider->pagination]); ?>
        </div>
    </div>

</div>
