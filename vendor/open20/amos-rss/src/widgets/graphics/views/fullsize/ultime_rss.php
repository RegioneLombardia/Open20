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
use amos\rss\Module;
use open20\amos\core\forms\Tabs;
use open20\amos\core\icons\AmosIcons;
use open20\amos\layout\assets\OwlCarouselAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

ModuleRssAsset::register($this);
OwlCarouselAsset::register($this);

/**
 * @var View $this
 * @var string $toRefreshSectionId
 * @var array $feeds
 * @var array $feedsfilter
 */
$modelsEnabled = amos\rss\Module::getInstance()->modelsEnabled;

$js = <<< JS
    $(".rss-js").click(function(event) {
        event.preventDefault();
        var rss = this.id;
        $.pjax.defaults.scrollTo = false;
        $.pjax({
            container:'#toRefreshSectionId',
            method: 'post',
            data: {'platformid':(this.id)},
            timeout: 5000
        }).done(function() {
            // console.log('ciao');
            $('.rss-js').removeClass('active');
            $('#'+rss).addClass('active');
         }); //Reload GridView
         return false;
    });
JS;


?>
<div class="box-widget-header">
    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('file-text-o', [], AmosIcons::DASH) ?>
            <span class="pluginName"> <?= Module::t('amosrss', '#from_other_platforms_label') ?></span>
        </h2>
    </div>
</div>

<div class="box-widget latest-rss">
    <section>
        <h2 class="sr-only"><?= Module::t('amosrss', '#from_other_platforms_label') ?></h2>
        <?php
        Pjax::begin(['id' => 'toRefreshSectionId', 'enablePushState' => false, 'scrollTo' => false]);
        $this->registerJs($js);
        ?>
        <div class="list-items ">

            <div class="wrap-item-rss col-xs-12">
                <?php foreach ($feeds as $count => $feed): ?>
                    <?php
                    $platformId = \Yii::$app->request->post('platformid') ?: \Yii::$app->session->get(\amos\rss\widgets\graphics\WidgetGraphicsRssUltimeNews::IDPLATFORM);
                    $image = $feed->getImage();
                    ?>
                    <div class="access col-xs-12">
                        <!--                        < ?= AmosIcons::show('file-text-o', [], AmosIcons::DASH)?>-->
                        <?= Html::a(
                            AmosIcons::show('file-text-o', [], AmosIcons::DASH) . $feed->getTitle(),
                            '',
                            [
                                'id' => $count,
                                'title' => Module::t('amosrss', '#load_platform_contents'),
                                'class' => 'rss-js',
                                'target' => '_blank'
                            ]
                        ); ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php
            foreach ($modelsEnabled as $el):
                $this->beginBlock($el);
                foreach ($feedsfilter as $feed):
                    foreach ($feed as $element):
                        $categories = $element->getCategories();
                        if ($categories[0]['term'] != $el) {
                            continue;
                        }
                        ?>
                        <div class="single-item" role="option">
                            <p class="date"><?= Module::t('amosrss', '#publish_date') . ' ' . Yii::$app->getFormatter()->asDate($element->getDateCreated()); ?></p>
                            <?php
                            $title = '';
                            if (strlen($element->getTitle()) > 55) {
                                $stringCut = substr($element->getTitle(), 0, 55);
                                if (strrpos($stringCut, ' ')) {
                                    $title = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
                                } else {
                                    $title = $stringCut . '... ';
                                }
                            } else {
                                $title = $element->getTitle();
                            }
                            ?>
                            <?= Html::a(
                                $title,
                                ['/rss/default/view', 'url' => $element->getLink()],
                                ['class' => 'title', 'data-pjax' => '0']
                            ); ?>
                            <p class="text">
                                <?php
                                if (strlen($element->getContent()) > 200) {
                                    $stringCut = substr(strip_tags($element->getContent()), 0, 150);
                                    echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                } else {
                                    echo strip_tags($element->getContent());
                                }
                                ?>
                            </p>
                        </div>
                    <?php
                    endforeach;
                endforeach;
                $this->endBlock();
                $model = Yii::createObject($el);
                $itemsTab[] = [
                    'label' => $model->getGrammar()->getModelLabel(),
                    'content' => $this->blocks[$el],
                ];

            endforeach;
            ?>
            <div class="wrap-content-rss col-xs-12">
                <?= Tabs::widget([
                    'encodeLabels' => false,
                    'items' => $itemsTab
                ]); ?>
            </div>
        </div>

        <?php Pjax::end(); ?>
    </section>
</div>
