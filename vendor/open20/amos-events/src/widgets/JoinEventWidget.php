<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\widgets
 * @category   CategoryName
 */

namespace open20\amos\events\widgets;

use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;

/**
 * Class JoinEventWidget
 * @package open20\amos\events\widgets
 */
class JoinEventWidget extends Widget
{
    const MODAL_CONFIRM_BTN_OPTIONS = ['class' => 'btn btn-navigation-primary'];
    const MODAL_CANCEL_BTN_OPTIONS = [
        'class' => 'btn btn-secondary',
        'data-dismiss' => 'modal'
    ];
    const BTN_CLASS_DFL = 'btn btn-navigation-primary';

    /**
     * @var Event $model
     */
    public $model = null;

    /**
     * @var int $userId
     */
    public $userId = null;

    /**
     * @var User $user
     */
    private $user = null;

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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (is_null($this->model)) {
            throw new \Exception(AmosEvents::t('amosevents', '#join_event_widget_missing_model'));
        }

        if (!$this->userId) {
            throw new \Exception(AmosEvents::t('amosevents', '#join_event_widget_missing_user_id'));
        }

        $this->user = User::findOne($this->userId);
        if (is_null($this->user)) {
            throw new \Exception(AmosEvents::t('amosevents', '#join_event_widget_not_found_user', ['userId' => $this->userId]));
        }

        if (!($this->model instanceof Event)) {
            throw new \Exception(AmosEvents::t('amosevents', '#join_event_widget_model_not_instance_of_event'));
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
                } elseif ($this->useIcon) {
                    $this->btnClass = 'btn btn-tool-secondary';
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
     * @inheritdoc
     */
    public function run()
    {
        $community = $this->model->community;
        if (is_null($community)) {
            return '';
        }

        $buttonUrl = null;
        $dataTarget = '';
        $dataToggle = '';
        $userCommunity = CommunityUserMm::findOne(['community_id' => $community->id, 'user_id' => $this->userId]);

        if (is_null($userCommunity)) {
            $icon = 'plus';
            $title = AmosEvents::t('amosevents', '#join_event_widget_subscribe_event');
            $dataToggle = 'modal';
            $dataTarget = '#joinPopup-' . $this->model->id;
            $buttonUrl = null;
            Modal::begin([
                'id' => 'joinPopup-' . $this->model->id,
                'header' => AmosEvents::t('amosevents', "#join_event_widget_subscribe_event")
            ]);
            echo Html::tag('div',
                AmosEvents::t('amosevents', "#join_event_widget_do_you_wish_subscribe") .
                " <strong>" . $this->user->userProfile->nomeCognome . "</strong> " .
                AmosEvents::t('amosevents', "#join_event_widget_to_event") .
                " <strong>" . $this->model->title . "</strong>"
            );
            echo Html::tag('div',
                Html::a(AmosEvents::t('amosevents', 'Annulla'), null, $this->modalButtonCancelOptions)
                . Html::a(AmosEvents::t('amosevents', 'Yes'),
                    ['/events/event/subscribe-user-to-event', 'eventId' => $this->model->id, 'userId' => $this->userId],
                    $this->modalButtonConfirmationOptions),
                ['class' => 'pull-right m-15-0']
            );
            Modal::end();
        }

        if (empty($title) || $this->onlyModals) {
            return '';
        } else {
            $this->btnOptions = ArrayHelper::merge($this->btnOptions, [
                'title' => $title
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
            $this->btnOptions['class'] = $this->btnOptions['class'] . ' m-r-5';
            $btn = Html::a(AmosIcons::show($icon), $buttonUrl, $this->btnOptions);
        } else {
            $btn = Html::a($title, $buttonUrl, $this->btnOptions);
        }
        if (!empty($this->divClassBtnContainer)) {
            $btn = Html::tag('div', $btn, ['class' => $this->divClassBtnContainer]);
        }

        return $btn;
    }
}
