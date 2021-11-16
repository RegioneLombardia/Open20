<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */

use open20\amos\notificationmanager\AmosNotify;

/**
 * @var integer $contents_number
 */

?>

<div style="box-sizing:border-box;color:#000000;">
    <div style="padding:5px 10px;background-color: #F2F2F2;text-align:center;">
        <h1 style="color:#297A38;font-size:1.2em;margin:0;">
            <?= AmosNotify::t('amosnotify', '#Platform_update') ?>
        </h1>
        <p style="font-size:1em;margin:0;margin-top:5px;">
            <?php
            if ($contents_number == 1):
                ?>
                <?= AmosNotify::t('amosnotify', '#There_is_content_interest', [$contents_number]) ?>
                <?php
            else:
                ?>
                <?= AmosNotify::t('amosnotify', '#There_is_content_interest_plural', [$contents_number]) ?>
            <?php
            endif;
            ?>
        </p>
    </div>
</div>