<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-sedi\email
 * @category   CategoryName
 */

use open20\amos\organizzazioni\Module;

/**
 * @var \yii\web\View $this
 * @var \open20\amos\organizzazioni\utility\EmailUtility $util
 */

?>

<div>
    <div style="box-sizing:border-box;">
        <div style="padding:5px 10px;background-color: #F2F2F2;">
            <h1 style="color:#297A38;text-align:center;font-size:1.5em;margin:0;"><?= Module::t('amosorganizzazioni', '#deleted_headquarter_mail_subject') ?></h1>
        </div>
        <div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff; margin-top: 20px;">
            <?= Module::t('amosorganizzazioni', '#deleted_headquarter_mail_text', ['headquarterName' => $util->model->name, 'organizationName' => $util->model->profilo->name]) ?>
        </div>
    </div>
</div>
