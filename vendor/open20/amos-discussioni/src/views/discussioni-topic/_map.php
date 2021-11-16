<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\discussioni\AmosDiscussioni;

?>
<div class="listview-container">

    <div class="bk-listViewElement">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-2">
            <img class="img-responsive" src="<?= $model->creatoreDiscussione->getAvatarUrl('small') ?>" alt="<?= $model ?>"/>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-10">
            <h2><?= $model ?></h2>

            <h3><?= $model->titolo ?></h3>
        </div>

        <div class="bk-elementActions">
            <a href="/discussioni/discussioni-topic/partecipa?id=<?= $model->id ?>" title="partecipa">
                <button class="btn btn-success">
                    <?= AmosDiscussioni::t('amosdiscussioni', 'Visualizza') ?>
                </button>
            </a>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
 