<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\core\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var \open20\amos\community\widgets\graphics\WidgetGraphicsCommunityReports $widget
 * @var array $downloadConfs
 */

$index = 0;

?>

<div class="box-widget">
    <div class="box-widget-toolbar row nom">
        <h2 class="box-widget-title col-xs-10 nop"><?= AmosCommunity::t('amoscommunity', 'Reports') ?></h2>
    </div>
    <section>
        <div role="listbox">
            <div class="list-items">
                <div class="widget-listbox-option row list-items" role="option">
                    <?php foreach ($downloadConfs as $downloadConf): ?>
                        <?php
                        if (isset($downloadConf['hideThis']) && $downloadConf['hideThis']) {
                            continue;
                        }
                        ?>
                        <?php if ($index != 0): ?>
                            <hr>
                        <?php endif; ?>
                        <?php $index++; ?>
                        <article class="col-xs-12 nop">
                            <div class="container-text col-xs-12 nop">
                                <p class="box-widget-subtitle pull-left"><?= $downloadConf['text']; ?></p>
                                <span class="pull-right">
                                    <?= Html::a(AmosCommunity::t('amoscommunity', 'Download'), $downloadConf['url'], [
                                        'class' => 'btn btn-navigation-primary',
                                        'target' => '_popup',
                                        'title' => AmosCommunity::t('amoscommunity', 'Download')
                                    ]); ?>
                                </span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</div>
