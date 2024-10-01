<?php

namespace open20\amos\comments\widgets;

use open20\amos\comments\AmosComments;
use open20\amos\comments\models\CommentNotificationUsers;
use open20\amos\comments\utility\CommentsUtility;
use Exception;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class CommentsBellNotificationWidget
 * @package open20\amos\comments\widgets
 */
class CommentsBellNotificationWidget extends Widget
{
    /**
     * @var string $modelClassname
     */
    public $modelClassname;

    /**
     * @var int $modelId
     */
    public $modelId;

    /**
     * Title of the button when the notification is enabled.
     * @var string $titleNotificationEnabled
     */
    public $titleNotificationEnabled = '';

    /**
     * Title of the button when the notification is disabled.
     * @var string $titleNotificationDisabled
     */
    public $titleNotificationDisabled = '';

    /**
     * Class of the button.
     * @var string $buttonClass
     */
    public $buttonClass = '';


    /**
     * @return void
     * @throws Exception
     */
    public function init()
    {
        if (empty($this->modelClassname)) {
            throw new Exception(AmosComments::t('amoscomments', 'Parameter modelClassname is missing in CommentsBellNotificationWidget.'));
        }
        if (empty($this->modelId)) {
            throw new Exception(AmosComments::t('amoscomments', 'Parameter modelId is missing in CommentsBellNotificationWidget.'));
        }

        parent::init();

        if (empty($this->buttonClass)) {
            $this->buttonClass = 'btn btn-outline-tertiary';
        }
        if (empty($this->titleNotificationEnabled)) {
            $this->titleNotificationEnabled = CommentsUtility::getTitleNotificationBell()['enabled'];
        }
        if (empty($this->titleNotificationDisabled)) {
            $this->titleNotificationDisabled = CommentsUtility::getTitleNotificationBell()['disabled'];
        }
    }

    /**
     * @return string
     */
    public function run(): string
    {
        /** @var AmosComments $moduleComments */
        $moduleComments = AmosComments::instance();

        $showNotificationBell = !Yii::$app->user->isGuest
            && in_array($this->modelClassname, $moduleComments->bellNotificationEnabledClasses)
            && $moduleComments->showBellIconInContentView;

        if ($showNotificationBell) {
            // Register JS
            $this->registerJsCommentsBellNotification();

            // Notification user value
            $commentNotificationUsers = CommentNotificationUsers::findOne([
                'context_model_class_name' => $this->modelClassname,
                'context_model_id' => $this->modelId,
                'user_id' => \Yii::$app->user->id
            ]);
            if ($commentNotificationUsers && $commentNotificationUsers->enable) {
                $commentsNotificationValue = true;
            } else {
                $commentsNotificationValue = false;
            }

            // Button
            $buttonOptions['class'] = $this->buttonClass;
            $buttonOptions['id'] = 'comments-notification-btn';
            $buttonOptions['data-toggle'] = 'tooltip';
            if ($commentsNotificationValue) {
                $buttonOptions['class'] .= ' comments-notification-active';
                $buttonOptions['title'] = $this->titleNotificationEnabled;
                $buttonIcon = '<span class="mdi mdi-bell-ring"></span>';
            } else {
                $buttonOptions['class'] .= ' comments-notification-inactive';
                $buttonOptions['title'] = $this->titleNotificationDisabled;
                $buttonIcon = '<span class="mdi mdi-bell-outline"></span>';
            }
            $button = Html::button($buttonIcon, $buttonOptions);

            return '<div class="bell-notification-container">' . $button . '</div>';
        }

        return '';
    }

    /**
     * Register the js for the bell icon.
     * @return void
     */
    public function registerJsCommentsBellNotification()
    {
        $modelClassname = addslashes($this->modelClassname);

        $js = <<<JS
        var container = $('.bell-notification-container');
        var modelClassname = '{$modelClassname}';
        var modelId = '{$this->modelId}';
        container.on('click', '.mdi-bell-ring, .mdi-bell-outline', function () {
            var bellIcon = $(this);
            var value = bellIcon.hasClass('mdi-bell-ring') ? 0 : 1;
            $.ajax({
                url: '/comments/comment-notification-users/update-ajax',
                type: 'POST',
                data: {
                    modelClassname: modelClassname,
                    modelId: modelId,
                    value: value
                },
                beforeSend: function () {
                    bellIcon.parent().prop('disabled', true);
                },
                success: function () {
                    bellIcon.toggleClass('mdi-bell-ring mdi-bell-outline');
                    bellIcon.parent().prop('disabled', false);
                    var tooltipContent = (value == 1) ? '{$this->titleNotificationEnabled}' : '{$this->titleNotificationDisabled}';
                    bellIcon.parent().attr('data-original-title', tooltipContent).tooltip('show');
                }
            });
        });
JS;

        $this->view->registerJs($js);
    }

}
