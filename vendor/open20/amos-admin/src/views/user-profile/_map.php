<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
?>

<div class="listview-container">

    <div class="bk-listViewElement">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-2">
            <img class="img-responsive" src="<?= $model->getAvatarUrl('small') ?>" alt="<?= $model ?>"/>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-10">
            <h2><?= $model ?></h2>

            <h3><?= $model->getIndirizzoCompleto() ?></h3>
        </div>

        <div class="bk-elementActions">
            <a href="/'.AmosAdmin::getModuleName().'/user-profile/view?id=<?= $model->id ?>"><button class="btn btn-success"><?= AmosAdmin::t('amosadmin', 'Visualizza') ?></button></a>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
 