<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community\email
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;

/**
 * @var yii\web\View $this
 * @var \open20\amos\community\utilities\EmailUtil $util
 */

?>

<div>
    <div style="box-sizing:border-box;">
        <div style="padding:5px 10px;background-color: #F2F2F2;">
            <h1 style="color:#297A38;text-align:center;font-size:1.5em;margin:0;"><?= AmosCommunity::t('amoscommunity', '#deleted_community_mail_subject') ?></h1>
        </div>
        <div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff; margin-top: 20px;">
            <?= AmosCommunity::t('amoscommunity', '#deleted_community_mail_text', ['communityTitle' => $util->community->name]) ?>
        </div>
    </div>
</div>
