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
use open20\amos\community\assets\AmosCommunityAsset;
use open20\amos\core\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var \open20\amos\community\widgets\graphics\WidgetGraphicsCommunityReports $widget
 * @var array $downloadConfs
 */

AmosCommunityAsset::register($this);

$index = 0;

?>

<div class="grid-item my-community-report">
    <div class="box-widget">
        <div class="box-widget-toolbar row nom">
            <h2 class="box-widget-title col-xs-10 nop"><?= AmosCommunity::t('amoscommunity', 'Reports') ?></h2>
        </div>
        <section>
            <?php foreach ($downloadConfs as $downloadConf): ?>
                <?php
                if (isset($downloadConf['hideThis']) && $downloadConf['hideThis']) {
                    continue;
                }
                ?>
                <?php $index++; ?>
                <div class="col-xs-12 widget-listbox-option" role="option">
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
                </div>
            <?php endforeach; ?>
        </section>
    </div>
</div>
