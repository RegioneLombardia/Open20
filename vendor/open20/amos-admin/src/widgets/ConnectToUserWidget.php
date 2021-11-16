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
use open20\amos\admin\models\UserContact;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\redactor\widgets\Redactor;

/**
 * Class ConnectToUserWidget
 * @package open20\amos\admin\widgets
 */
class ConnectToUserWidget extends Widget
{
    const MODAL_CONFIRM_BTN_OPTIONS = ['class' => 'btn btn-navigation-primary m-r-5'];
    const MODAL_CANCEL_BTN_OPTIONS = [
        'class' => 'btn btn-secondary m-r-5',
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
     * widget initialization
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
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        //Register javascript to send private message to connected users
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
        if ($model instanceof UserContact) {
            $isUserContactModel = true;
        } else {
            $isUserContactModel = false;
        }

        $loggedUserId = Yii::$app->user->id;
        $title = '';
        $titleLink = '';
        $buttonUrl = null;
        $dataTarget = '';
        $dataToggle = '';
        $invited = false;

        $userProfile = User::findOne($loggedUserId)->getProfile();
        if ($isUserContactModel) {
            $userContact = $model;
        } else {
            $userContact = UserContact::findOne(['contact_id' => $model->user_id, 'user_id' => $loggedUserId]);
            $userContactInvited = UserContact::findOne(['user_id' => $model->user_id, 'contact_id' => $loggedUserId]);
        }

        if (!$userProfile->validato_almeno_una_volta) {
            //The logged user profile has never been validated, it is not possible to send connection request: user is invited to complete the profile and ask for validation
            $this->btnOptions['class'] = 'btn btn-action-primary' . ($this->isGridView ? ' font08' : '');
            $title = AmosAdmin::t('amosadmin', 'Connect') . AmosIcons::show('link');
            $titleLink = AmosAdmin::t('amosadmin', 'Connect');
            $dataToggle = 'modal';
            $dataTarget = '#notValidatedUserPopup-' . $model->id;
            if (!$this->onlyButton) {
                Modal::begin([
                    'id' => 'notValidatedUserPopup-' . $model->id,
                    'header' => AmosAdmin::t('amosadmin', "Contact request")
                ]);
                echo Html::tag('div', AmosAdmin::t('amosadmin', "You will be able to connect with") . " <strong>"
                    . $model->getNomeCognome() . "</strong> " . AmosAdmin::t('amosadmin',
                        "once your profile will have been validated. Take some minutes to complete your profile, in order to fully use all the functionality that the platform offers."));
                echo Html::tag('div',
                    Html::a(AmosAdmin::t('amosadmin', 'Not now'), null, $this->modalButtonCancelOptions)
                    . Html::a(AmosAdmin::t('amosadmin', 'Complete the profile'),
                        ['/'.AmosAdmin::getModuleName().'/first-access-wizard/introduction', 'id' => $userProfile->id],
                        $this->modalButtonConfirmationOptions),
                    ['class' => 'pull-right m-15-0']
                );
                Modal::end();
            }
        } else {
            if (empty($userContact) && empty($userContactInvited)) {
                //The logged user invites another user to join his contact network
                $title = AmosAdmin::t('amosadmin', 'Connect') . AmosIcons::show('link');
                $titleLink = AmosAdmin::t('amosadmin', 'Connect');
                $dataToggle = 'modal';
                $dataTarget = '#invitationPopup-' . $model->id;
                if (!$this->onlyButton) {
                    Modal::begin([
                        'id' => 'invitationPopup-' . $model->id,
                        'header' => AmosAdmin::t('amosadmin', "Contact request")
                    ]);
                    echo Html::tag('div', AmosAdmin::t('amosadmin', "Do you wish to invite") . " <strong>"
                        . $model->getNomeCognome() . "</strong> " . AmosAdmin::t('amosadmin',
                            "to join your contact network?"));
                    echo Html::tag('div',
                        Html::a(AmosAdmin::t('amosadmin', 'Cancel'), null, $this->modalButtonCancelOptions)
                        . Html::a(AmosAdmin::t('amosadmin', 'Invite contact'),
                            ['/'.AmosAdmin::getModuleName().'/user-contact/connect', 'contactId' => $model->user_id],
                            $this->modalButtonConfirmationOptions),
                        ['class' => 'm-15-0']
                    );
                    Modal::end();
                }
            } else {
                if (is_null($userContact)) {
                    $userContact = $userContactInvited;
                    $invited = true;
                } else {
                    $invited = ($userContact->contact_id == $loggedUserId);
                }
                if ($userContact->status == UserContact::STATUS_INVITED) {
                    if (!$invited) {
                        //logged user invited another user to join is contact list, but the request is still pending. It is possible to send a reminder
                        $title = AmosAdmin::t('amosadmin', 'Pending invitation') . AmosIcons::show('account-box-mail');
                        $titleLink = AmosAdmin::t('amosadmin', 'Pending invitation');
                        $dataToggle = 'modal';
                        $idModal = ($isUserContactModel ? $userContact->contact_id : $model->id);
                        $dataTarget = '#sendReminderPopup-' . $idModal;
                        if (!$this->onlyButton) {
                            Modal::begin([
                                'id' => 'sendReminderPopup-' . $idModal,
                                'header' => AmosAdmin::t('amosadmin', "Send reminder of connection request")
                            ]);
                            if (!empty($userContact->last_reminder_at)) {
                                $invitationDate = Yii::$app->getFormatter()->asDatetime($userContact->last_reminder_at);
                            } else {
                                $invitationDate = Yii::$app->getFormatter()->asDatetime($userContact->created_at);
                            }
                            if ($isUserContactModel) {
                                $invitedName = User::findOne($userContact->contact_id)->getProfile()->getNomeCognome();
                            } else {
                                $invitedName = $model->getNomeCognome();
                            }
                            echo Html::tag('div', AmosAdmin::t('amosadmin', "Your connection request to") . " <strong>"
                                . $invitedName . "</strong> " . AmosAdmin::t('amosadmin',
                                    "has been sent") . " " . $invitationDate);
                            echo Html::tag('div',
                                AmosAdmin::t('amosadmin', "Click on 'Send reminder' to send a mail to") . " "
                                . $invitedName . " " . AmosAdmin::t('amosadmin',
                                    "reminding to answer to your request") . ". ");
                            echo Html::tag('div',
                                Html::a(AmosAdmin::t('amosadmin', 'Cancel'), null, $this->modalButtonCancelOptions)
                                . Html::a(AmosAdmin::t('amosadmin', 'Send reminder'),
                                    ['/'.AmosAdmin::getModuleName().'/user-contact/send-reminder', 'id' => $userContact->id],
                                    $this->modalButtonConfirmationOptions),
                                ['class' => 'pull-right m-15-0']
                            );
                            Modal::end();
                        }
                    } else {
                        //logged user has been invited to connect by the contact, user can accept or refuse the request
                        $title = AmosAdmin::t('amosadmin', 'Answer to invitation') . AmosIcons::show('account-box-mail');
                        $titleLink = AmosAdmin::t('amosadmin', 'Answer to invitation');
                        $dataToggle = 'modal';
                        $idModal = ($isUserContactModel ? $userContact->user_id : $model->id);
                        $dataTarget = '#answerInvitationPopup-' . $idModal;
                        if (!$this->onlyButton) {
                            Modal::begin([
                                'id' => 'answerInvitationPopup-' . $idModal,
                                'header' => AmosAdmin::t('amosadmin', "Accept or refuse connection request")
                            ]);
                            $btnRejectOpts = array_merge($this->modalButtonCancelOptions);
                            unset($btnRejectOpts['data-dismiss']);
                            $invitedBy = User::findOne($userContact->created_by)->getProfile()->getNomeCognome();
                            echo Html::tag('div', AmosAdmin::t('amosadmin',
                                    "Do you wish to join the contact network of") . " <strong>" . $invitedBy . " </strong>?");
                            echo Html::tag('div',
                                Html::a(AmosAdmin::t('amosadmin', 'Reject invitation'),
                                    [
                                        '/'.AmosAdmin::getModuleName().'/user-contact/connect',
                                        'contactId' => $loggedUserId,
                                        'userId' => $userContact->user_id,
                                        'accept' => false
                                    ],
                                    $btnRejectOpts
                                )
                                . Html::a(AmosAdmin::t('amosadmin', 'Accept invitation'),
                                    [
                                        '/'.AmosAdmin::getModuleName().'/user-contact/connect',
                                        'contactId' => $loggedUserId,
                                        'userId' => $userContact->user_id,
                                        'accept' => true
                                    ],
                                    $this->modalButtonConfirmationOptions),
                                ['class' => 'pull-right m-15-0']
                            );
                            Modal::end();
                        }
                    }
                } elseif ($userContact->status == UserContact::STATUS_ACCEPTED) {
                    //user is an active contact, it is possible to send a private message
                    $chatModule = Yii::$app->getModule('chat');
                    if (isset($chatModule)) {
                        $recipientId = $invited ? $userContact->user_id : $userContact->contact_id;
                        $recipientName = User::findOne($recipientId)->getProfile()->getNomeCognome();
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
                                        'buttons' => ['image'],
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
                                ) . '</div>';//Html::endForm();
                            Modal::end();
                        }
                    }
                }
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
