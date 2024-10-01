<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo\email
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
            <h1 style="color:#297A38;text-align:center;font-size:1.5em;margin:0;"><?= Module::t('amosorganizzazioni', '#rejected_invitation_mail_title') ?></h1>
        </div>
        <div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff; margin-top: 20px;">
            <h2 style="font-size:2em;line-height: 1;"><?= $util->userName . " " . Module::t('amosorganizzazioni', '#rejected_invitation_mail_text_1') . $util->contextLabel ?></h2>
            <div style="display: flex; padding: 10px;">
                <?= ProfiloCardWidget::widget([
                    'model' => $util->model,
                    'onlyLogo' => true,
                    'absoluteUrl' => true,
                    'inEmail' => true
                ]) ?>
                <?php
                echo Html::tag('div', '<p style="font-weight: 900">' . $util->model->name . '</p>
                <p>' . $util->model->getDescription(true) . '</p>', ['style' => 'margin: 0 0 0 20px;'])
                ?>
            </div>
        </div>
    </div>
</div>
