<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\widgets\views\comments-widget
 * @category   CategoryName
 */

use open20\amos\comments\AmosComments;
use open20\amos\core\helpers\Html;

/**
 * @var \open20\amos\comments\widgets\CommentsWidget $widget
 * @var bool $enableUserSendMailCheckbox
 * @var bool $displayNotifyCheckBox
 * @var string $checkboxName
 * @var string $viewTypePosition "comment" if from comment and "comment_reply" if from comments view for comment reply
 */

$sendNotifyCheckBox = '';
if ($enableUserSendMailCheckbox) {
    if ($displayNotifyCheckBox) {
        $sendNotifyCheckBox = Html::checkbox($checkboxName, true, ['label' => ' ' . AmosComments::t('amoscomments', '#checkbox_send_notify')]);
    } else {
        $sendNotifyCheckBox = Html::hiddenInput($checkboxName, 1);
    }
}
echo $sendNotifyCheckBox;
