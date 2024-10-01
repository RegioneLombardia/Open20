<?php

/**
 * Aria S.p.A.
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
        echo
        '<svg class="svg-send-notify" style="width:22px;height:22px;margin-right: 10px;margin-bottom: -2px;" viewBox="0 0 24 24">
            <path fill="currentColor" d="M9,5A4,4 0 0,1 13,9A4,4 0 0,1 9,13A4,4 0 0,1 5,9A4,4 0 0,1 9,5M9,15C11.67,15 17,16.34 17,19V21H1V19C1,16.34 6.33,15 9,15M16.76,5.36C18.78,7.56 18.78,10.61 16.76,12.63L15.08,10.94C15.92,9.76 15.92,8.23 15.08,7.05L16.76,5.36M20.07,2C24,6.05 23.97,12.11 20.07,16L18.44,14.37C21.21,11.19 21.21,6.65 18.44,3.63L20.07,2Z" />
        </svg>';
        $sendNotifyCheckBox = Html::checkbox(
            $checkboxName,
            true,
            [
                'id' => 'sendMailNotification',
                'label' => ' ' . AmosComments::t('amoscomments', 'Notifica ai partecipanti'),
            ]
        );
    } else {
        $sendNotifyCheckBox = Html::hiddenInput($checkboxName, 1);
    }
}
echo $sendNotifyCheckBox;
