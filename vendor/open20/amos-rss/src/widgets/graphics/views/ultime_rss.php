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
use kv4nt\owlcarousel\OwlCarouselWidget;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

ModuleRssAsset::register($this);
OwlCarouselAsset::register($this);

/**
 * @var View $this
 * @var string $toRefreshSectionId
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
            $('.rss-title').removeClass('active');
            $('#'+rss).parent().prev().find('.rss-title').addClass('active');
         }); //Reload GridView
         return false;
    });

    $('#rssOwlCarousel').owlCarousel({
        margin: 10,
        nav: true,
        loop: false,
        autoplay: false,
        autoplayTimeout: 3000,
        responsive: {
            320: {
                items: 1,
                stagePadding: 30,
            },
            420: {
                items: 1,
                stagePadding: 30,
            },
            620: {
                items: 1,
                stagePadding: 20,
            },
            768: {
                items: 2,
            },
            992: {
                items: 3,
            },
            1199: {
                items: 5,
            }
        }
    });
JS;


?>

<div class="grid-item grid-item--fullwidth rss-custom-height">
    <div class="box-widget latest-rss">
        <div class="box-widget-toolbar">
            <h1 class="box-widget-title col-xs-10 nop"><?= Module::t('amosrss', '#from_other_platforms_label') ?></h1>
        </div>
        <section class="clearfixplus">
            <h2 class="sr-only"><?= Module::t('amosrss', '#from_other_platforms_label') ?></h2>
            <?php
            Pjax::begin(['id' => 'toRefreshSectionId', 'enablePushState' => false, 'scrollTo' => false]);
            $this->registerJs($js);
            ?>

            <?php
            OwlCarouselWidget::begin([
                'container' => 'div',
                'containerOptions' => [
                    'id' => 'rssOwlCarousel',
                    'class' => 'rss-owl-carousel'
                ],
            ]);
            ?>

            <?php
            foreach ($feeds as $count => $feed):
                ?>
                <?php
                $platformId = \Yii::$app->request->post('platformid') ?: \Yii::$app->session->get(\amos\rss\widgets\graphics\WidgetGraphicsRssUltimeNews::IDPLATFORM);
                $stateTitle = ($platformId == $count || ($platformId == '' && $count == 0)) ? 'active' : '';
                $image = $feed->getImage();
                ?>
                <div class="owl-item-content <?= $stateTitle ?>">
                    <div class="access col-xs-12 nop">
                        <?=
                        Html::a(
                            AmosIcons::show('chevron-left') .
                            AmosIcons::show('home') .
                            Html::tag('span', Module::t('amosrss', '#load_platform_contents')), $image['link'], [
                                'title' => Module::t('amosrss', '#load_platform_contents'),
                                'class' => 'access-link',
                                'target' => '_blank'
                            ]
                        );
                        ?>
                    </div>
                    <div class="rss-infos col-xs-12 nop">
                        <?php
                        if (is_array($image) && isset($image['uri'])) {
                            ?>
                            <div class="rss-image col-xs-12 nop">
                                <img class="img-responsive" src="<?= $image['uri'] ?>">
                            </div>
                        <?php } ?>
                        <div class="rss-title col-xs-12 nop">
                            <?= Html::tag('p', $feed->getTitle()) ?>
                        </div>
                    </div>
                    <div class="rss-footer col-xs-12 nop">
                        <?= ($stateTitle == 'active') ? Html::tag('span', Module::t('amosrss', '#rss')) : Html::a(Module::t('amosrss', '#view_rss'), '', ['id' => $count, 'class' => 'rss-js btn btn-navigation-primary']); ?>
                    </div>
                </div>
            <?php
            endforeach;
            ?>
            <?php OwlCarouselWidget::end(); ?>

            <div class="list-items col-xs-12 nop">
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
                        'label' => $model->getGrammar()->getModelSingularLabel(),
                        'content' => $this->blocks[$el],
                    ];

                endforeach;
                ?>
                <?=
                Tabs::widget(
                    [
                        'encodeLabels' => false,
                        'items' => $itemsTab
                    ]
                );
                ?>
                <div class="clearfix"></div>
            </div>
            <?php Pjax::end(); ?>
        </section>
    </div>
</div>