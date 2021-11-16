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

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\user\User;
use open20\amos\events\AmosEvents;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class InviteUserToEventWidget
 * @package open20\amos\events\widgets
 */
class InviteUserToEventWidget extends Widget
{
    const MODAL_CONFIRM_BTN_OPTIONS = ['class' => 'btn btn-navigation-primary user-to-event-widget'];
    const MODAL_CANCEL_BTN_OPTIONS = [
        'class' => 'btn btn-secondary user-to-event-widget',
        'data-dismiss' => 'modal'
    ];
    const BTN_CLASS_DFL = 'btn btn-navigation-primary user-to-event-widget';

    /**
     * @var UserProfile $model
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
            throw new \Exception(AmosEvents::t('amosevents', 'Missing model'));
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
        // Check if events module is present. In other case return empty string now.
        $externalModule = Yii::$app->getModule('events');
        if (is_null($externalModule) || !Yii::$app->user->can(self::className())) {
            return '';
        }

        /** @var UserProfile $model */
        $model = $this->model;
        $loggedUserId = Yii::$app->user->id;
        $loggedUserProfile = User::findOne($loggedUserId)->userProfile;
        $title = '';
        $titleLink = '';
        $buttonUrl = null;

        if ($loggedUserProfile->validato_almeno_una_volta) {
            $title = AmosEvents::t('amosevents', '#subscribe_user_to_event');
            $titleLink = AmosEvents::t('amosevents', '#subscribe_user_to_event');
            $buttonUrl = ['/events/event/associate-user-to-event-m2m', 'id' => $model->id, 'viewM2MWidgetGenericSearch' => true];
        }

        if (empty($title) || $this->onlyModals) {
            return '';
        } else {
            $this->btnOptions = ArrayHelper::merge($this->btnOptions, [
                'title' => $titleLink
            ]);
        }
        $btn = Html::a($title, $buttonUrl, $this->btnOptions);
        if (!empty($this->divClassBtnContainer)) {
            $btn = Html::tag('div', $btn, ['class' => $this->divClassBtnContainer]);
        }
        return $btn;
    }
}
