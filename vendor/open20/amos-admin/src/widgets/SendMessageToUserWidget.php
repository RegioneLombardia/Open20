<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\widgets
 * @category   CategoryName
 */

namespace open20\amos\admin\widgets;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\user\User;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\redactor\widgets\Redactor;

/**
 * Class SendMessageToUserWidget
 * @package open20\amos\admin\widgets
 */
class SendMessageToUserWidget extends Widget
{
    const MODAL_CONFIRM_BTN_OPTIONS = ['class' => 'btn btn-navigation-primary btn-connect-to-user'];
    const MODAL_CANCEL_BTN_OPTIONS = [
        'class' => 'btn btn-secondary btn-connect-to-user',
        'data-dismiss' => 'modal'
    ];
    const BTN_CLASS_DFL = 'btn btn-navigation-primary btn-connect-to-user';

    /**
     * @var int $userId
     */
    public $model = null;

    /**
     * @var bool|false true if we are in edit mode, false if in view mode or otherwise
     */
    public $modalButtonConfirmationStyle = '';
    public $modalButtonConfirmationOptions = [];
    public $modalButtonCancelStyle = '';
    public $modalButtonCancelOptions = [];
    public $divClassBtnContainer = '';
    public $btnClass = '';
    public $btnStyle = '';
    public $btnOptions = [];
    public $isProfileView = false;
    public $isGridView = false;
    public $onlyModals = false;
    public $onlyButton = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (is_null($this->model)) {
            throw new \Exception(AmosAdmin::t('amosadmin', 'Missing model'));
        }

        if (empty($this->modalButtonConfirmationOptions)) {
            $this->modalButtonConfirmationOptions = self::MODAL_CONFIRM_BTN_OPTIONS;
            if (empty($this->modalButtonConfirmationStyle)) {
                if ($this->isProfileView) {
                    $this->modalButtonConfirmationOptions['class'] = $this->modalButtonConfirmationOptions['class'] . ' modal-btn-confirm-relative';
                }
            } else {
                $this->modalButtonConfirmationOptions = ArrayHelper::merge(self::MODAL_CONFIRM_BTN_OPTIONS, ['style' => $this->modalButtonConfirmationStyle]);
            }
        }

        if (empty($this->modalButtonCancelOptions)) {
            $this->modalButtonCancelOptions = self::MODAL_CANCEL_BTN_OPTIONS;
            if (empty($this->modalButtonCancelStyle)) {
                if ($this->isProfileView) {
                    $this->modalButtonCancelOptions['class'] = $this->modalButtonCancelOptions['class'] . ' modal-btn-cancel-relative';
                }
            } else {
                $this->modalButtonCancelOptions = ArrayHelper::merge(self::MODAL_CANCEL_BTN_OPTIONS, ['style' => $this->modalButtonCancelStyle]);
            }
        }

        if (empty($this->btnOptions)) {
            if (empty($this->btnClass)) {
                if ($this->isProfileView) {
                    $this->btnClass = 'btn btn-secondary';
                } else {
                    $this->btnClass = self::BTN_CLASS_DFL;
                }
            }
            $this->btnOptions = ['class' => $this->btnClass . ($this->isGridView ? ' font08' : '')];
            if (!empty($this->btnStyle)) {
                $this->btnOptions = ArrayHelper::merge($this->btnOptions, ['style' => $this->btnStyle]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        // Check if chat module is present. In other case return empty string now.
        /** @var \open20\amos\chat\AmosChat $chatModule */
        $chatModule = Yii::$app->getModule('chat');
        if (is_null($chatModule)) {
            return '';
        }

        // Register javascript to send private message to connected users
        $js = <<<JS
        $(".send-message-btn").on("click",function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var textId = '#chat-message_' + $(this).data('recipient_id');
            $.ajax({
                url: href,
                type: 'POST',
                data: {
                    text: $(textId).val()
                },
                dataType : 'json',
                success: function(response) {
                    var decoded = response;
                    if(decoded.success == 1) {
                       $(textId).val('');
                       window.location.href = decoded.url;
                    }    
                }
            });
            return false;
        });
JS;
        $this->getView()->registerJs($js);

        /** @var UserProfile $model */
        $model = $this->model;
        $loggedUserId = Yii::$app->user->id;
        $loggedUserProfile = User::findOne($loggedUserId)->userProfile;
        $title = '';
        $titleLink = '';
        $buttonUrl = null;
        $dataTarget = '';
        $dataToggle = '';

        if ($loggedUserProfile->validato_almeno_una_volta) {
            $recipientId = $model->user_id;
            $recipientName = $model->getNomeCognome();
            $title = AmosAdmin::t('amosadmin', 'Send message');
            $titleLink = AmosAdmin::t('amosadmin', 'Send message');
            $dataToggle = 'modal';
            $dataTarget = '#sendMessagePopup-' . $recipientId;
            if (!$this->onlyButton) {
                Modal::begin([
                    'id' => 'sendMessagePopup-' . $recipientId,
                    'header' => AmosAdmin::t('amosadmin', "Send message to") . " " . $recipientName
                ]);
                echo
                    '<div class="col-xs-12 nop">'
                    . '<label class="hidden" for="chat-message">' . AmosAdmin::tHtml('amosadmin',
                        'Message') . '</label>'
                    . Redactor::widget([
                        'name' => 'text',
                        'options' => [
                            'id' => 'chat-message_' . $recipientId,
                            'class' => 'form-control send-message',
                            'placeholder' => AmosAdmin::t('amosadmin', 'Write message...')
                        ],
                        'clientOptions' => [
                            'focus' => true,
                            'buttons' => $chatModule->formRedactorButtons,
                            'lang' => substr(Yii::$app->language, 0, 2)
                        ]
                    ]);

                $btnOptions = array_merge($this->modalButtonConfirmationOptions);
                $btnOptions['class'] .= ' send-message-btn';
                echo Html::tag('div',
                        Html::a(AmosAdmin::t('amosadmin', 'Cancel'), null,
                            $this->modalButtonCancelOptions)
                        . Html::a(AmosAdmin::t('amosadmin', 'Send message'), '/chat/default/send-message?contactId=' . $recipientId,
                            ArrayHelper::merge($btnOptions, ['id' => 'send-message-btn-' . $recipientId, 'data-recipient_id' => $recipientId])),
                        ['class' => 'pull-right m-15-0']
                    ) . '</div>';
                Modal::end();
            }
        }

        if (empty($title) || $this->onlyModals) {
            return '';
        } else {
            $this->btnOptions = ArrayHelper::merge($this->btnOptions, [
                'title' => $titleLink
            ]);
        }
        if (!empty($dataTarget) && !empty($dataToggle)) {
            $this->btnOptions = ArrayHelper::merge($this->btnOptions, [
                'data-target' => $dataTarget,
                'data-toggle' => $dataToggle
            ]);
        }
        $btn = Html::a($title, $buttonUrl, $this->btnOptions);
        if (!empty($this->divClassBtnContainer)) {
            $btn = Html::tag('div', $btn, ['class' => $this->divClassBtnContainer]);
        }
        return $btn;
    }
}
