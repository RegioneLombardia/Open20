<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-sedi\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\widgets\ProfiloCardWidget;

/**
 * @var \yii\web\View $this
 * @var \open20\amos\organizzazioni\utility\EmailUtility $util
 */

if (!empty($profile)) {
    $this->params['profile'] = $profile;
}
?>

<div>
    <div style="box-sizing:border-box;">
        <div style="padding:5px 10px;background-color: #F2F2F2;">
            <h1 style="color:#297A38;text-align:center;font-size:1.5em;margin:0;"><?= Module::t('amosorganizzazioni', '#accepted_invitation_mail_title') ?></h1>
        </div>
        <div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff; margin-top: 20px;">
            <h2 style="font-size:2em;line-height: 1;"><?= $util->userName . " " . Module::t('amosorganizzazioni', '#accepted_invitation_mail_text_1') . $util->contextLabel ?></h2>
            <div style="display: flex; padding: 10px;">
                <div style="width: 50px; height: 50px; -webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;float: left;">
                    <?= ProfiloCardWidget::widget([
                        'model' => $util->model->profilo,
                        'onlyLogo' => true,
                        'absoluteUrl' => true,
                        'inEmail' => true
                    ]) ?>
                </div>
                <?php
                echo Html::tag('div', '<p style="font-weight: 900">' . $util->model->name . '</p>
                <p>' . $util->model->getDescription(true) . '</p>', ['style' => 'margin: 0 0 0 20px;'])
                ?>
            </div>
            <?php if (!empty($util->role)): ?>
                <div style="width:100%;margin-top:30px">
                    <p>
                        <?= $util->userName . " " . Module::t('amosorganizzazioni', "has role:") ?>
                        <span style="font-weight: 900"><?= $util->role . "." ?></span>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
