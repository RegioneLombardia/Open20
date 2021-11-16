<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */
use luya\helpers\Url;
?>
<!-- BREADCRUMB -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li><a href="<?= Yii::$app->menu->getHome()->link ?>" title="home page"><span class="sr-only"><?= Yii::$app->menu->getHome()->title?> </span><span class="glyphicon glyphicon-home"></span></a></li>
        <?php foreach (Yii::$app->menu->current->teardown as $item): /* @var $item \luya\cms\menu\Item */ ?>
            <li class="breadcrumb-item">
                <a href="<?= $item->link; ?>"><?= $item->title; ?></a>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
<!-- END: BREADCRUMB -->