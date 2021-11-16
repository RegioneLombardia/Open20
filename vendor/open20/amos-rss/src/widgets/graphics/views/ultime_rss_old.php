<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\widgets\graphics\views
 * @category   CategoryName
 */


use amos\rss\Module;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var string $toRefreshSectionId
 */

?>

<div class="" style="">
    <div class="box-widget">
        <div class="box-widget-toolbar row nom">
            <h1 class="box-widget-title col-xs-10 nop"><?= Module::t('amosrss', '#from_other_platforms_label') ?></h1>

        </div>
        <section>
            <h2 class="sr-only"><?= Module::t('amosrss', '#from_other_platforms_label') ?></h2>
            <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
            <div role="listbox">

                <div class="list-items">
                    <?php
                    foreach ($feeds as $feed):
                        foreach ($feed as $element):
                            ?>
                            <div class="widget-listbox-option row" role="option">
                                <article class="col-xs-12 nop">

                                    <div class="container-text row">
                                        <div class="col-xs-12 nopl">

                                            <p><?= Yii::$app->getFormatter()->asDate($element->getDateCreated()); ?></p>
                                            <h2 class="box-widget-subtitle">
                                                <?php
                                                if (strlen($feed->getTitle()) > 55) {
                                                    $stringCut = substr($feed->getTitle(), 0, 55);
                                                    echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                                } else {
                                                    echo $feed->getTitle();
                                                }
                                                ?>
                                            </h2>
                                        </div>
                                        <h4 class="box-widget-title">
                                            <?php
                                            if (strlen($element->getTitle()) > 55) {
                                                $stringCut = substr($element->getTitle(), 0, 55);
                                                echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                            } else {
                                                echo $element->getTitle();
                                            }
                                            ?>
                                        </h4>
                                        <div class="col-xs-12 nopl">
                                            <p class="box-widget-text">
                                                <?php
                                                if (strlen($element->getContent()) > 200) {
                                                    $stringCut = substr($element->getContent(), 0, 200);
                                                    echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                                } else {
                                                    echo $element->getContent();
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 m-t-5 nop">
                                    <span class="pull-right">
                                        <?= Html::a(Module::t('amosrss', 'LEGGI'), ['/rss/default/view', 'url' => $element->getLink()], ['class' => 'btn btn-navigation-primary']); ?>
                                    </span>
                                    </div>
                                </article>
                            </div>
                        <?php
                        endforeach;
                    endforeach;
                    ?>
                </div>
            </div>
            <?php Pjax::end(); ?>
        </section>
    </div>
</div>