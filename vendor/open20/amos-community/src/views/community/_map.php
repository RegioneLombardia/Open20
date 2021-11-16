<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;

/*
 * Personalize the view at will
 * $model is the model related to db table
 * $buttons The buttons of standard template {view}{update}{delete}
 */
?>

<div class="listview-container">
    <div class="bk-listViewElement">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2><?= $model ?></h2>
            <p>####### personalizzare l&#39;html a piacimento #######</p>
        </div>
        <div class="bk-elementActions">
            <a href="community/view?id=<?= $model->id ?>">
                <button class="btn btn-success"><?= AmosCommunity::t('amoscommunity', 'View') ?></button>
            </a>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>