<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\core\helpers\Html;

/** @var \open20\amos\community\utilities\EmailUtil $util */

if(!empty($profile)) {
    $this->params['profile'] = $profile;
}
?>
<div>
    <div style="box-sizing:border-box;">
        <div style="padding:5px 10px;background-color: #F2F2F2;">
            <h1 style="color:#297A38;text-align:center;font-size:1.5em;margin:0;"><?= AmosCommunity::t('amoscommunity', '#registation_notification_mail_title') ?></h1>
        </div>
        <div
            style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff; margin-top: 20px;">
            <h2 style="font-size:2em;line-height: 1;"><?= $util->userName . " " . AmosCommunity::t('amoscommunity', '#registation_notofication_mail_text_1') . $util->contextLabel ?></h2>

            <div style="display: flex; padding: 10px;">
                <?php if ($util->isCommunityContext): ?>
                    <div
                        style="width: 50px; height: 50px; -webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;float: left;">
                        <?= \open20\amos\community\widgets\CommunityCardWidget::widget([
                            'model' => $util->community,
                            'onlyLogo' => true,
                            'absoluteUrl' => true,
                            'inEmail' => true
                        ]) ?>
                    </div>
                <?php endif; ?>
                <?php
                $divOptions = $util->isCommunityContext ? ['style' => 'margin: 0 0 0 20px;'] : [];
                echo Html::tag('div', '<p style="font-weight: 900">' . $util->community->name . '</p>
                <p>' . $util->community->getDescription(true) . '</p>', $divOptions)
                ?>
            </div>
            <div style="width:100%;margin-top:30px">
                <p>
                    <?= AmosCommunity::t('amoscommunity', 'To view the detail and participants list ') ?>
                    <?= Html::a(AmosCommunity::t('amoscommunity', '#mail_network_community_2'), $util->url, ['style' => 'color: green;']) ?>
                </p>
            </div>
            <?php if ($util->isCommunityContext): ?>
                <p>
                    <?= AmosCommunity::t('amoscommunity', '#mail_to_manager_footer') ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>