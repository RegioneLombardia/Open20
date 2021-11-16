<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\widgets\graphics\views
 * @category   CategoryName
 */
use amos\rss\assets\ModuleRssAsset;
use open20\amos\core\icons\AmosIcons;
use amos\rss\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Inflector;

ModuleRssAsset::register($this);
$moduleRss = \Yii::$app->getModule(Module::getModuleName());

/**
 * @var View $this
 * @var string $toRefreshSectionId
 */
$limit = Module::getInstance()->limitRss;
if (sizeof($feedsfilter) == 1) {
    $limit = $limit * 2;
}
?>

<div class="box-widget-header">
    <?php if (isset($moduleRss) && !$moduleRss->hideWidgetGraphicsActions) { ?>
        <?=
        WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => Module::className(),
            'actionRoute' => Url::to($link[0], true), /* DA METTERE URL CORRETTO */
            'toRefreshSectionId' => $toRefreshSectionId
        ]);
        ?>
    <?php } ?>

    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('feed', [], AmosIcons::DASH) ?>
            <span class="pluginName"> <?= Module::t('amosrss', '#ultime_news') ?> </span>
        </h2>
    </div>
</div>

<div class="box-widget news-category-rss">
    <section>
        <h2 class="sr-only"><?= (empty($title) ? Module::t('amosrss', '#ultime_news') : $title) ?></h2>
        <div class="list-items">
            <div class="rss-content col-xs-12">
                <?php
                foreach ($feedsfilter as $cat => $link):
                    ?>
                    <div class="rss-infos <?= (sizeof($feedsfilter) == 1) ? '' : 'rss-two-infos' ?> col-xs-12 nop">
                        <div class="rss-head col-xs-12">
                            <h2><?= str_replace('-', ' ', strtoupper($cat)) ?></h2>
                            <?php
                            if (isset($link) && !empty($link)):
                                if (strpos($link[0], '/feed/')):
                                    $urlnew  = explode('feed/', $link[0]);
                                    array_pop($urlnew);
                                    $link[0] = implode('/', $urlnew);
                                endif;
                                ?>
                                <?=
                                Html::a(
                                    Module::t('amosrss', '#readmore').AmosIcons::show('arrow-right'),
                                    Url::to($link[0], true),
                                    ['target' => '_blank', 'title' => Module::t('amosrss', '#readmore')]
                                );
                                ?>
                                <?php
                            endif;
                            ?>
                        </div>
                        <div class="rss-body col-xs-12">
                            <?php
                            foreach ($feeds as $feed):
                                foreach ($feed as $count => $element):
                                    if ($count >= $limit) {
                                        break;
                                    }
                                    $categories = $element->getCategories();
                                    if (is_null($category)) {
                                        if ($categories[0]['label'] != $cat) {
                                            continue;
                                        }
                                    }
                                    ?>

                                    <div class="single-item col-xs-12" role="option">
                                        <?php
                                        $title = '';
                                        if (strlen($element->getTitle()) > 55) {
                                            $stringCut = substr($element->getTitle(), 0, 55);
                                            if (strrpos($stringCut, ' ')) {
                                                $title = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
                                            } else {
                                                $title = $stringCut.'... ';
                                            }
                                        } else {
                                            $title = $element->getTitle();
                                        }
                                        ?>

                                        <a class="image" href= "<?= Url::to($element->getLink(), true) ?>" title="<?= $title ?>" >
                                            <?php
                                            $media = $element->getEnclosure();
                                            if (is_array($media) && isset($media['uri'])) {
                                                ?>
                                                <img class="img-responsive" src="<?= $media['uri'] ?>" alt="immagine della notizia">
                                            <?php } else if (!empty($src = \amos\rss\utility\FeedUtility::getImageSrc($element))) { ?>
                                                <img class="img-responsive" src="<?= $src ?>" alt="immagine della notizia">
                                                <?php
                                            } else if (!empty($src = \amos\rss\utility\FeedUtility::getOgProperty($element,
                                                        'image'))) {
                                                ?>                                               
                                                <img class="img-responsive" src="<?= $src ?>" alt="immagine della notizia">
                                            <?php } else { ?>
                                                <img class="img-responsive" src="/img/img_default.jpg" alt="immagine della notizia">
                                            <?php } ?>
                                        </a>
                                        <p class="date">
                                            <?=
                                            Yii::$app->getFormatter()->asDate($element->getDateCreated(), 'long');
                                            ?>
                                        </p>
                                        <?=
                                        Html::a(
                                            $title.' '.AmosIcons::show('open-in-new', [], AmosIcons::AM),
                                            Url::to($element->getLink(), true),
                                            ['target' => '_blank', 'title' => $title, 'class' => 'title']
                                        );
                                        ?>
                                        <p class="text">
                                            <?php
                                            if (strlen($element->getContent()) > 200) {
                                                $stringCut = substr(strip_tags($element->getContent()), 0, 150);
                                                echo substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
                                            } else {
                                                echo strip_tags($element->getContent());
                                            }
                                            ?>
                                        </p>
                                    </div>

                                    <?php
                                endforeach;
                            endforeach;
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>