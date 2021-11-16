<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\widgets
 * @category   CategoryName
 */

namespace open20\amos\moodle\widgets;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\user\User;
use open20\amos\moodle\AmosMoodle;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class InviteUserToEventWidget
 * @package open20\amos\events\widgets
 */
class SubscribeUserToFADCourseWidget extends Widget
{
    const MODAL_CONFIRM_BTN_OPTIONS = ['class' => 'btn btn-navigation-primary user-to-event-widget'];
    const MODAL_CANCEL_BTN_OPTIONS = [
        'class' => 'btn btn-secondary user-to-event-widget',
        'data-dismiss' => 'modal'
    ];
    const BTN_CLASS_DFL = 'btn btn-navigation-primary user-to-event-widget';

    /**
     * @var int $userId
     */
    public $userProfile = null;

    /**
     * 
     */
    public $organization = null;

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

        if (is_null($this->userProfile)) {
            throw new \Exception(AmosMoodle::t('amosmoodle', '#missing_user_profile_model'));
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
        if (!Yii::$app->user->can(self::className())) {
            return '';
        }

        /** @var UserProfile $model */
        //$model = $this->userProfile;
        $userToEnrol = User::findOne(['id' => $this->userProfile->user_id]);
        $amosUser = new \open20\amos\core\user\AmosUser(['identityClass' => User::className()]);
        $amosUser->setIdentity($userToEnrol);

        if (!($amosUser->can(AmosMoodle::MOODLE_STUDENT))) {
            return '';
        }
        
        $userRoles = Yii::$app->authManager->getRolesByUser($this->userProfile->user_id);
        $title = '';
        $titleLink = '';
        $buttonUrl = null;

        if ($this->userProfile->validato_almeno_una_volta) {
            $title = AmosMoodle::t('amosmoodle', '#subscribe_user_to_fad_course');
            $titleLink = AmosMoodle::t('amosmoodle', '#subscribe_user_to_fad_course');
            $buttonUrl = [
                '/moodle/course/index',
                'uid' => $this->userProfile->user_id,
                'org' => $this->organization->profilo->id
            ];
        }

        if (empty($title) || $this->onlyModals) {
            return '';
        }
        
        $this->btnOptions = ArrayHelper::merge($this->btnOptions, [
            'title' => $titleLink
        ]);
        
        $btn = Html::a($title, $buttonUrl, $this->btnOptions);
        if (!empty($this->divClassBtnContainer)) {
            $btn = Html::tag('div', $btn, ['class' => $this->divClassBtnContainer]);
        }
        
        return $btn;
    }
}
