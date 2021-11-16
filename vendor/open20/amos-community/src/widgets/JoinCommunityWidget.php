<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\widgets;


use open20\amos\admin\AmosAdmin;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;

class JoinCommunityWidget extends Widget
{
    const MODAL_CONFIRM_BTN_OPTIONS = ['class' => 'btn btn-navigation-primary'];
    const MODAL_CANCEL_BTN_OPTIONS = [
        'class' => 'btn btn-secondary',
        'data-dismiss' => 'modal'
    ];

    const BTN_CLASS_DFL = 'btn btn-navigation-primary btn-join-community';


    /**
     * @var Community $model
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
    public $useIcon = false;

    public $onlyModals = false;
    public $onlyButton = false;

    /**
     * widget initialization
     */
    public function init()
    {
        parent::init();

        if (is_null($this->model)) {
            throw new \Exception(AmosCommunity::t('amoscommunity', 'Missing model'));
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
                    $this->btnClass = 'btn btn-secondary btn-join-community';
                } elseif ($this->useIcon) {
                    $this->btnClass = 'btn btn-tools-secondary';
                } else {
                    $this->btnClass = self::BTN_CLASS_DFL;
                }
            }
            $this->btnOptions = ['class' => $this->btnClass . (($this->isGridView && !$this->useIcon) ? ' font08' : '')];
            if (!empty($this->btnStyle)) {
                $this->btnOptions = ArrayHelper::merge($this->btnOptions, ['style' => $this->btnStyle]);
            }
        }

    }

    /**
     * @return mixed
     */
    public function run()
    {
        $titleLink = '';
        /** @var Community $model */
        $model = $this->model;
        if ($model instanceof CommunityUserMm) {
            $isUserCommunityModel = true;
        } else {
            $isUserCommunityModel = false;
        }

        $buttonUrl = null;
        $dataTarget = '';
        $dataToggle = '';

        $loggedUserId = Yii::$app->getUser()->getId();

        $userProfile = User::findOne($loggedUserId)->getProfile();
        if ($isUserCommunityModel) {
            $userCommunity = $model;
            $model = Community::findOne($userCommunity->community_id);
        } else {
            $userCommunity = CommunityUserMm::findOne(['community_id' => $model->id, 'user_id' => $loggedUserId]);
        }

        if (!$userProfile->validato_almeno_una_volta && is_null($userCommunity) && $model->for_all_user == 0) {
            $icon = 'plus-square';
            //$this->btnOptions['class'] = 'btn btn-action-primary' . (($this->isGridView && !$this->useIcon) ? ' font08' : '');
            $title = AmosCommunity::t('amoscommunity', 'Join') . AmosIcons::show($icon);
            $titleLink = AmosCommunity::t('amoscommunity', 'Join');
            $dataToggle = 'modal';
            $dataTarget = '#notValidatedUserPopup-' . $model->id;
            if (!$this->onlyButton) {
                Modal::begin([
                    'id' => 'notValidatedUserPopup-' . $model->id,
                    'header' => AmosCommunity::t('amoscommunity', "Join")
                ]);
                echo Html::tag('div', Html::tag('p', AmosCommunity::t('amoscommunity', "You will be able to subscribe to community") . " <strong>"
                    . $model->name . "</strong> " . AmosCommunity::t('amoscommunity',
                        "once your profile will have been validated. Take some minutes to complete your profile, in order to fully use all the functionality that the platform offers."))
                );
                echo Html::tag('div',
                    Html::a(AmosAdmin::t('amosadmin', 'Not now'), null, $this->modalButtonCancelOptions)
                    . Html::a(AmosAdmin::t('amosadmin', 'Complete the profile'),
                        ['/admin/first-access-wizard/introduction', 'id' => $userProfile->id],
                        $this->modalButtonConfirmationOptions),
                    ['class' => 'pull-right m-15-0']
                );
                Modal::end();
            }
        } else {
            if (is_null($userCommunity)) {
                $icon = 'plus-square';
                $title = AmosCommunity::t('amoscommunity', 'Join') . AmosIcons::show($icon);
                $titleLink = AmosCommunity::t('amoscommunity', 'Join');
                $dataToggle = 'modal';
                $dataTarget = '#joinPopup-' . $model->id;
                $buttonUrl = '';
                Modal::begin([
                    'id' => 'joinPopup-' . $model->id,
                    'header' => AmosCommunity::t('amoscommunity', "Join")
                ]);
                echo Html::tag('div',
                    Html::tag('p', AmosCommunity::t('amoscommunity', "Do you wish to add community") .
                        " <strong>" . $model->name . "</strong> " . AmosCommunity::t('amoscommunity', "to your network?"))
                );
                echo Html::tag('div',
                    Html::a(AmosCommunity::t('amoscommunity', 'Cancel'), null,
                        $this->modalButtonCancelOptions)
                    . Html::a(AmosCommunity::t('amoscommunity', 'Yes'),
                        ['/community/community/join-community', 'communityId' => $model->id],
                        $this->modalButtonConfirmationOptions),
                    ['class' => 'pull-right m-15-0']
                );
                Modal::end();
            } else {
                if ($userCommunity->status == CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER) {
                    $icon = 'upload';;
                    $this->btnOptions['class'] = (!$this->useIcon) ? ('btn btn-action-primary btn-join-community' . ($this->isGridView ? ' font08' : '')) : $this->btnOptions['class'];
                    $title = AmosCommunity::t('amoscommunity', 'Request sent') . AmosIcons::show($icon);
                    $titleLink = AmosCommunity::t('amoscommunity', 'Request sent');
                    $disabled = true;
                } elseif ($userCommunity->status == CommunityUserMm::STATUS_WAITING_OK_USER) {
                    $icon = 'mail-reply';
                    $title = AmosCommunity::t('amoscommunity', 'Answer to invitation') . AmosIcons::show($icon);
                    $titleLink = AmosCommunity::t('amoscommunity', 'Answer to invitation');
                    $dataToggle = 'modal';
                    $dataTarget = '#answerInvitationPopup-' . $model->id;
                    $buttonUrl = null;
                    $btnRejectOpts = array_merge($this->modalButtonCancelOptions);
                    unset($btnRejectOpts['data-dismiss']);
                    Modal::begin([
                        'id' => 'answerInvitationPopup-' . $model->id,
                        'header' => AmosCommunity::t('amoscommunity', "Answer to invitation")
                    ]);
                    $invitedBy = $userCommunity->createdUserProfile->getNomeCognome();
                    echo Html::tag('div',
                        $invitedBy . " " . AmosCommunity::t('amoscommunity', "invited you to join this community"));
                    echo Html::tag('div',
                        Html::a(AmosCommunity::t('amoscommunity', 'Reject invitation'),
                            ['/community/community/join-community', 'communityId' => $model->id, 'accept' => false],
                            $btnRejectOpts)
                        . Html::a(AmosCommunity::t('amoscommunity', 'Accept invitation'),
                            ['/community/community/join-community', 'communityId' => $model->id, 'accept' => true],
                            $this->modalButtonConfirmationOptions),
                        ['class' => 'pull-right m-15-0']
                    );
                    Modal::end();
                } else {
                    $icon = 'sign-in';
                    $title = AmosCommunity::t('amoscommunity', 'Sign in') . AmosIcons::show($icon);
                    $titleLink = AmosCommunity::t('amoscommunity', 'Sign in');
                    $buttonUrl = '/community/join?id=' . $model->id;
                    //$this->btnOptions['class'] .= ' ' . self::btnJoinSelector() . ' ';
                    $this->btnOptions['data-community_id'] = $model->id;
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
        if (isset($disabled)) {
            $this->btnOptions['class'] = $this->btnOptions['class'] . ' disabled';
        }
        if (!empty($dataTarget) && !empty($dataToggle)) {
            $this->btnOptions = ArrayHelper::merge($this->btnOptions, [
                'data-target' => $dataTarget,
                'data-toggle' => $dataToggle
            ]);
        }
        if ($this->useIcon) {
            $this->btnOptions['class'] = $this->btnOptions['class'];
            $btn = Html::a(AmosIcons::show($icon), $buttonUrl, $this->btnOptions);
        } else {
            $btn = Html::a($title, $buttonUrl, $this->btnOptions);
        }
        if (!empty($this->divClassBtnContainer)) {
            $btn = Html::tag('div', $btn, ['class' => $this->divClassBtnContainer]);
        }
        return $btn;
    }

    public static function btnJoinSelector()
    {
        return 'join-community-btn-selector';
    }
}
