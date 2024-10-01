<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\widgets
 * @category   CategoryName
 */

namespace open20\amos\events\widgets;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\JsUtility;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\utility\EventsUtility;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\Expression;
use yii\db\Query;
use yii\web\View;
use yii\widgets\PjaxAsset;

/**
 * Class InvitedToEventWidget
 * @package open20\amos\events\widgets
 */
class InvitedToEventWidget extends Widget
{
    /**
     * @var AmosAdmin $adminModule
     */
    public $adminModule = null;

    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @var Event $model
     */
    public $model = null;

    /**
     * @var string $gridId
     */
    public $gridId = 'invited-list-grid';

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->eventsModule = AmosEvents::instance();
        $this->adminModule = AmosAdmin::instance();

        if (!$this->model) {
            throw new InvalidConfigException($this->throwErrorMessage('model'));
        }

        if (!$this->model->id) {
            throw new InvalidConfigException($this->throwErrorMessage('model id'));
        }
    }

    protected function throwErrorMessage($field)
    {
        return AmosEvents::t('amosevents', 'Wrong widget configuration: missing field {field}', [
            'field' => $field
        ]);
    }

    protected function getListQuery($searchPostName)
    {
        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');
        $eventInvitationTable = $eventInvitationModel::tableName();

        /** @var User $userModel */
        $userModel = $this->adminModule->createModel('User');
        $userTable = $userModel::tableName();

        /** @var UserProfile $userProfileModel */
        $userProfileModel = $this->adminModule->createModel('UserProfile');
        $userProfileTable = $userProfileModel::tableName();

        $query = new Query();
        $query->select([
            $eventInvitationTable . '.id AS invitation_id',
            $eventInvitationTable . '.event_id',
            new Expression('IF(' . $eventInvitationTable . '.user_id IS NULL, ' . $userProfileTable . '.user_id, ' . $eventInvitationTable . '.user_id) AS invitation_user_id'),
            new Expression('IF(' . $eventInvitationTable . '.user_id IS NULL, ' . $eventInvitationTable . '.name, ' . $userProfileTable . '.nome) AS user_name'),
            new Expression('IF(' . $eventInvitationTable . '.user_id IS NULL, ' . $eventInvitationTable . '.surname, ' . $userProfileTable . '.cognome) AS user_surname'),
            new Expression('IF(' . $eventInvitationTable . '.user_id IS NULL, ' . $eventInvitationTable . '.email, ' . $userTable . '.email) AS user_email'),
            new Expression('IF(' . $eventInvitationTable . '.user_id IS NULL, ' . $eventInvitationTable . '.fiscal_code, ' . $userProfileTable . '.codice_fiscale) AS user_cf'),
            $eventInvitationTable . '.state AS invitation_state',
            $eventInvitationTable . '.type AS invitation_type',
            $eventInvitationTable . '.invitation_sent_on',
            $eventInvitationTable . '.invitation_response_on',
        ]);

        $query->from($eventInvitationTable);
        $query->leftJoin($userProfileTable, \Yii::$app->db->quoteColumnName($userProfileTable . '.user_id') . ' = ' . \Yii::$app->db->quoteColumnName($eventInvitationTable . '.user_id'));
        $query->leftJoin($userTable, \Yii::$app->db->quoteColumnName($userTable . '.id') . ' = ' . \Yii::$app->db->quoteColumnName($userProfileTable . '.user_id'));

        $query->andWhere([$eventInvitationTable . '.deleted_at' => null]);
        $query->andWhere([$userProfileTable . '.deleted_at' => null]);
        $query->andWhere([$userTable . '.deleted_at' => null]);
        $query->andWhere(['event_id' => $this->model->id]);

        $query->orderBy([$eventInvitationTable . '.id' => SORT_DESC]);

        $searchName = \Yii::$app->request->post($searchPostName);
        if (!is_null($searchName) && !empty($searchName)) {
            $query->andWhere(['or',
                ['like', $eventInvitationTable . '.name', $searchName],
                ['like', $eventInvitationTable . '.surname', $searchName],
                ['like', $eventInvitationTable . '.email', $searchName],
                ['like', $eventInvitationTable . '.fiscal_code', $searchName],
                ['like', $userProfileTable . '.nome', $searchName],
                ['like', $userProfileTable . '.cognome', $searchName],
                ['like', $userTable . '.email', $searchName],
                ['like', $userProfileTable . '.codice_fiscale', $searchName]
            ]);
        }

        return $query;
    }

    /**
     * @return string
     */
    public static function getSearchPostName()
    {
        return 'searchInvitedName';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();

        /** @var UserProfile $userProfileModel */
        $userProfileModel = $adminModule->createModel('UserProfile');

        /** @var User $userModel */
        $userModel = $this->adminModule->createModel('User');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $url = \Yii::$app->urlManager->createUrl([
            '/events/event/event-invited-list',
            'eventId' => $this->model->id
        ]);

        $js = JsUtility::getSearchM2mFirstGridJs($this->gridId, $url, static::getSearchPostName());
        PjaxAsset::register($this->getView());
        $this->getView()->registerJs($js, View::POS_LOAD);

        $query = $this->getListQuery(static::getSearchPostName());
        $model = $this->model;

        $widget = M2MWidget::widget([
            'model' => $model,
            'modelId' => $model->id,
            'modelData' => $query,
            'overrideModelDataArr' => true,
            'forceListRender' => true,
            'gridId' => $this->gridId,
            'firstGridSearch' => true,
            'pageParam' => 'page-invited-to-event',
            'targetUrlController' => 'event',
            'moduleClassName' => AmosEvents::className(),
            'postName' => 'Event',
            'postKey' => 'event',
            'itemsMittente' => [
                'invitation_id' => [
                    'label' => AmosEvents::t('amosevents', '#invitation_list_invitation_id_label'),
                    'attribute' => 'invitation_id',
                ],
                'user_surname' => [
                    'label' => $userProfileModel->getAttributeLabel('cognome'),
                    'value' => function ($data) {
                        return (!$data['user_surname'] ? '-' : $data['user_surname']);
                    }
                ],
                'user_name' => [
                    'label' => $userProfileModel->getAttributeLabel('nome'),
                    'value' => function ($data) {
                        return (!$data['user_name'] ? '-' : $data['user_name']);
                    }
                ],
                'user_email' => [
                    'label' => $userModel->getAttributeLabel('email'),
                    'value' => function ($data) {
                        return (!$data['user_email'] ? '-' : $data['user_email']);
                    }
                ],
                'user_cf' => [
                    'label' => $userProfileModel->getAttributeLabel('codice_fiscale'),
                    'value' => function ($data) {
                        return (!$data['user_cf'] ? '-' : $data['user_cf']);
                    }
                ],
                'invitation_type' => [
                    'label' => AmosEvents::t('amosevents', '#invitation_list_invite_type_label'),
                    'value' => function ($data) {
                        if ($data['invitation_type'] == EventInvitation::INVITATION_TYPE_REGISTERED) {
                            return AmosEvents::t('amosevents', '#invitation_list_user_registered');
                        } elseif ($data['invitation_type'] == EventInvitation::INVITATION_TYPE_IMPORTED) {
                            return AmosEvents::t('amosevents', '#invitation_list_imported');
                        } elseif ($data['invitation_type'] == EventInvitation::INVITATION_TYPE_REGISTERED_BY_PUBLIC_FORM) {
                            return AmosEvents::t('amosevents', '#invitation_list_registered_by_public_form');
                        }
                        return '-';
                    }
                ],
                'invitation_state' => [
                    'label' => AmosEvents::t('amosevents', '#invitation_list_invite_state_label'),
                    'value' => function ($data) {
                        if ($data['invitation_state'] == EventInvitation::INVITATION_STATE_INVITED) {
                            return AmosEvents::t('amosevents', '#invitation_list_invite_pending');
                        } elseif ($data['invitation_state'] == EventInvitation::INVITATION_STATE_ACCEPTED) {
                            return AmosEvents::t('amosevents', '#invitation_list_invite_accepted');
                        } elseif ($data['invitation_state'] == EventInvitation::INVITATION_STATE_REJECTED) {
                            return AmosEvents::t('amosevents', '#invitation_list_invite_rejected');
                        }
                        return '-';
                    }
                ],
                'invitation_sent_on' => [
                    'label' => $eventInvitationModel->getAttributeLabel('invitation_sent_on'),
                    'value' => function ($data) {
                        return (!$data['invitation_sent_on'] ? '-' : \Yii::$app->formatter->asDatetime($data['invitation_sent_on'], 'humanalwaysdatetime'));
                    }
                ],
                'invitation_response_on' => [
                    'label' => $eventInvitationModel->getAttributeLabel('invitation_response_on'),
                    'value' => function ($data) {
                        return (!$data['invitation_response_on'] ? '-' : \Yii::$app->formatter->asDatetime($data['invitation_response_on'], 'humanalwaysdatetime'));
                    }
                ],
            ],
            'exportMittenteConfig' => [
                'exportEnabled' => true,
            ],
        ]);

        return "<div id='" . $this->gridId . "' data-pjax-container='" . $this->gridId . "-pjax' data-pjax-timeout=\"10000\">"
            . "<h3>" . AmosEvents::tHtml('amosevents', '#invited_list') . "</h3>"
            . $widget . "</div>";
    }
}
