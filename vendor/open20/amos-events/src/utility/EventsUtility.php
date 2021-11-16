<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\utility
 * @category   CategoryName
 */

namespace open20\amos\events\utility;

use dosamigos\qrcode\lib\Enum;
use dosamigos\qrcode\QrCode;
use open20\amos\attachments\models\File;
use open20\amos\community\AmosCommunity;
use open20\amos\community\exceptions\CommunityException;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityContextInterface;
use open20\amos\community\models\CommunityType;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\events\AmosEvents;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventAccreditationList;
use open20\amos\events\models\EventGroupReferent;
use open20\amos\events\models\EventGroupReferentMm;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventInvitationSent;
use open20\amos\events\models\EventParticipantCompanion;
use open20\amos\events\models\EventType;
use open20\amos\invitations\models\Invitation;
use open20\amos\tag\models\Tag;
use kartik\mpdf\Pdf;
use Yii;
use yii\base\Exception;
use yii\helpers\Url;
use yii\log\Logger;

/**
 * Class EventsUtility
 * @package open20\amos\events\utility
 */
class EventsUtility
{
    /**
     * This method translate the array values.
     * @param array $arrayValues
     * @return array
     */
    public static function translateArrayValues($arrayValues)
    {
        $translatedArrayValues = [];
        foreach ($arrayValues as $key => $title) {
            $translatedArrayValues[$key] = AmosEvents::t('amosevents', $title);
        }
        return $translatedArrayValues;
    }

    /**
     * @param $model
     * @param string $managerStatus
     * @param null $typeCommunity
     * @return bool
     */
    public static function createCommunity($model, $managerStatus = '', $typeCommunity = null)
    {
        /** @var AmosCommunity $communityModule */
        $communityModule = Yii::$app->getModule('community');
        $title = ($model->title ? $model->title : '');
        $description = ($model->description ? $model->description : '');
        $eventType = $model->eventType;
        $type = CommunityType::COMMUNITY_TYPE_CLOSED; // DEFAULT TYPE

        if ($typeCommunity) {
            $type = $typeCommunity;
        } else {
            if (!is_null($eventType) && $eventType->event_type == EventType::TYPE_OPEN) {
                $type = CommunityType::COMMUNITY_TYPE_OPEN;
            } else if (!is_null($eventType) && $eventType->event_type == EventType::TYPE_UPON_INVITATION) {
                $type = CommunityType::COMMUNITY_TYPE_CLOSED;
            }
        }

        $context = AmosEvents::instance()->model('Event');
        $managerRole = $model->getManagerRole();
        try {
            $model->community_id = $communityModule->createCommunity(
                $title,
                $type,
                $context,
                $managerRole,
                $description,
                $model,
                $managerStatus
            );
            $ok = $model->save(false);
            if (!is_null($model->community_id)) {
                $ok = EventsUtility::duplicateEventTagForCommunity($model);
            }
        } catch (CommunityException $exception) {
            \Yii::getLogger()->log($exception->getMessage(), Logger::LEVEL_ERROR);
            $ok = false;
        }
        return $ok;
    }

    /**
     * Update a community.
     * @param Event $model
     * @return bool
     */
    public static function updateCommunity($model)
    {
        $model->community->name = $model->title;
        $model->community->description = $model->description;
        $ok = $model->community->save(false);
        return $ok;
    }

    /**
     * @param Event $model
     * @return bool
     */
    public static function duplicateEventTagForCommunity($model)
    {
        $moduleTag = Yii::$app->getModule('tag');
        /** @var AmosEvents $eventsModule */
        $eventsModule = AmosEvents::instance();
        $ok = true;
        if (isset($moduleTag) && in_array(
                $eventsModule->model('Event'),
                $moduleTag->modelsEnabled
            ) && $moduleTag->behaviors) {
            $eventTags = \open20\amos\tag\models\EntitysTagsMm::findAll([
                'classname' => $eventsModule->model('Event'),
                'record_id' => $model->id
            ]);
            foreach ($eventTags as $eventTag) {
                $entityTag = new \open20\amos\tag\models\EntitysTagsMm();
                $entityTag->classname = Community::className();
                $entityTag->record_id = $model->community_id;
                $entityTag->tag_id = $eventTag->tag_id;
                $entityTag->root_id = $eventTag->root_id;
                $ok = $entityTag->save(false);
                if (!$ok) {
                    break;
                }
            }
        }
        return $ok;
    }

    /**
     * @param Event $model
     * @return bool
     */
    public static function duplicateEventLogoForCommunity($model)
    {
        $ok = true;
        $eventLogo = File::findOne([
            'model' => AmosEvents::instance()->model('Event'), 'attribute' => 'eventLogo',
            'itemId' => $model->id
        ]);
        if (!is_null($eventLogo)) {
            $communityLogo = File::findOne([
                'model' => Community::className(), 'attribute' => 'communityLogo',
                'itemId' => $model->community_id
            ]);
            if (!is_null($communityLogo)) {
                if ($eventLogo->hash != $communityLogo->hash) {
                    $communityLogo->delete();
                    $ok = EventsUtility::newCommunityLogo(
                        $model->community_id,
                        $eventLogo
                    );
                }
            } else {
                $ok = EventsUtility::newCommunityLogo(
                    $model->community_id,
                    $eventLogo
                );
            }
        } else {
            $communityLogo = File::findOne([
                'model' => Community::className(), 'attribute' => 'communityLogo',
                'itemId' => $model->community_id
            ]);
            if (!is_null($communityLogo)) {
                $communityLogo->delete();
            }
        }
        return $ok;
    }

    /**
     * @param int $communityId
     * @param File $eventLogo
     * @return bool
     */
    private static function newCommunityLogo($communityId, $eventLogo)
    {
        $communityLogo = new File();
        $eventLogoAttributes = $eventLogo->attributes;
        $toSkipFields = ['id', 'model', 'attribute', 'itemId'];
        foreach ($eventLogoAttributes as $fieldName => $fieldValue) {
            if (!in_array($fieldName, $toSkipFields)) {
                $communityLogo->{$fieldName} = $fieldValue;
            }
        }
        $communityLogo->model = Community::className();
        $communityLogo->attribute = 'communityLogo';
        $communityLogo->itemId = $communityId;
        return $communityLogo->save();
    }

    public static function deleteCommunityLogo($model)
    {
        $communityLogo = File::findOne([
            'model' => Community::className(), 'attribute' => 'communityLogo',
            'itemId' => $model->community_id
        ]);
        if (!is_null($communityLogo)) {
            $communityLogo->delete();
        }
    }

    /**
     * Check if there is at least one confirmed event manager only if there is a community. If not it return true.
     * @param Event $event
     * @return bool
     */
    public static function checkOneConfirmedManagerPresence($event)
    {
        if (!$event->community_id) {
            return true;
        }
        $confirmedEventManagers = self::findEventManagers(
            $event,
            CommunityUserMm::STATUS_ACTIVE
        );
        return (count($confirmedEventManagers) > 0);
    }

    /**
     * Check if there is at least one confirmed event manager only if there is a community. If not it return true.
     * @param Event $event
     * @param string $status
     * @return array[CommunityUserMm]
     */
    public static function findEventManagers($event, $status = '')
    {
        if (!$event->community_id) {
            return [];
        }

        $where = [
            'community_id' => $event->community_id,
            'role' => $event->getManagerRole()
        ];

        if ($status) {
            $where['status'] = $status;
        }

        $eventManagers = CommunityUserMm::find()->andWhere($where)->all();

        return $eventManagers;
    }

    /**
     * @param null|integer $userId
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getUserCalendarService($userId = null)
    {
        $socialAuth = \Yii::$app->getModule('socialauth');
        if (!is_null($socialAuth)) {
            if (is_null($userId)) {
                $userId = \Yii::$app->user->id;
            }
            $socialAuthUser = \open20\amos\socialauth\models\SocialAuthUsers::findOne([
                'user_id' => $userId, 'provider' => 'google'
            ]);
            if (!is_null($socialAuthUser)) {
                $service = $socialAuthUser->getServices()->andWhere(['service' => 'calendar'])->one();
                return $service;
            }
        }
        return null;
    }

    /**
     * @param null|\open20\amos\socialauth\models\SocialAuthServices $service
     * @return \Google_Service_Calendar|null
     */
    public static function getGoogleServiceCalendar($service = null)
    {
        $socialAuth = \Yii::$app->getModule('socialauth');
        if (!is_null($socialAuth)) {
            $client = $socialAuth->getClient('google', $service);
            if (!is_null($client)) {
                return new \Google_Service_Calendar($client);
            }
        }
        return null;
    }

    /**
     * @param \Google_Service_Calendar $serviceGoogle
     * @param string $calendarId
     * @param \Google_Service_Calendar_Event $eventCalendar
     * @return bool - operation result
     */
    public static function insertOrUpdateGoogleEvent(
        $serviceGoogle,
        $calendarId,
        $eventCalendar
    )
    {

        $eventId = $eventCalendar->getId();
        try {
            $eventCalendarExists = $serviceGoogle->events->get(
                $calendarId,
                $eventId
            );
            $isUpdate = true;
        } catch (\Google_Service_Exception $ex) {
            $isUpdate = false;
        }
        try {
            if (!$isUpdate) {
                $serviceGoogle->events->insert($calendarId, $eventCalendar);
            } else {
                $serviceGoogle->events->update(
                    $calendarId,
                    $eventCalendar->getId(),
                    $eventCalendar
                );
            }
        } catch (\Google_Service_Exception $e) {
            Yii::getLogger()->log(
                'Google calendar insert or update event ' . $eventId . ': ' . $e->getMessage(),
                Logger::LEVEL_WARNING
            );
            return false;
        }
        return true;
    }

    /**
     * @param \Google_Service_Calendar $serviceGoogle
     * @param string $calendarId
     * @param string $eventId
     * @return bool - operation result
     */
    public static function deleteGoogleEvent(
        $serviceGoogle,
        $calendarId,
        $eventId
    )
    {

        try {
            $eventCalendar = $serviceGoogle->events->get($calendarId, $eventId);
        } catch (\Google_Service_Exception $ex) {
            return true;
        }
        try {
            $serviceGoogle->events->delete($calendarId, $eventId);
        } catch (\Google_Service_Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param Event $event
     * @param int $eventId
     * @return int|string
     */
    public static function cmpSeatsAvailable(Event $event, $eventId = 0)
    {
        $count = 0;
        try {
            if (is_null($event)) {
                if ($eventId > 0) {
                    /** @var AmosEvents $eventsModule */
                    $eventsModule = AmosEvents::instance();
                    /** @var Event $eventModel */
                    $eventModel = $eventsModule->createModel('Event');
                    $event = $eventModel::findOne($eventId);
                } else {
                    throw new Exception('No event present');
                }
            }
            $community = $event->getCommunityModel();
            $query = $community->getCommunityManagers();
            $members = $query->count();
            $count = $event->seats_available - $members;
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }

        return $count;
    }

    public static function checkManager($event)
    {
        $communityUtil = new CommunityUtil();
        $isOperator = EventsUtility::isUserEventOperator(\Yii::$app->user->id, $event);

        return $communityUtil->isManagerLoggedUser($event) || $isOperator;
    }

    /**
     * @param CommunityContextInterface $model
     * @return bool
     */
    public static function hasPrivilegesLoggedUser($model)
    {
        $isOperator = EventsUtility::isUserEventOperator(\Yii::$app->user->id, $model);

        $foundRow = CommunityUserMm::findOne([
            'community_id' => $model->getCommunityModel()->id,
            'user_id' => \Yii::$app->getUser()->getId(),
            'role' => $model->getPriviledgedRoles()
        ]);
        return (!is_null($foundRow) || $isOperator);
    }

    /**
     * @param Event|null $event
     * @param Invitation|null $invitation
     * @param string $type
     * @param array $companion
     * @param null $url
     * @param string $qrcodeFormat
     * @param int $size
     * @return string
     */
    public static function createQrCode(
        $event = null,
        $invitation = null,
        $type = '',
        $companion = null,
        $url = null,
        $qrcodeFormat = 'png',
        $size = 350
    )
    {
        if ($type == 'participant') {
            if ($event && $invitation) {
                $url = Url::base(true) . Url::toRoute([
                        'register-participant', 'eid' => $event->id,
                        'pid' => (empty($invitation->user_id) ? '' : $invitation->user_id), 'iid' => $invitation->id
                    ]);
            }
        } elseif ($type == 'companion') {
            if ($event && $invitation) {
                $url = Url::base(true) . Url::toRoute([
                        'register-companion', 'eid' => $event->id,
                        'pid' => $invitation->user_id, 'iid' => $invitation->id,
                        'cid' => $companion['id']
                    ]);
            }
        }

        if (!empty($url)) {
            /* if ($qrcodeFormat == 'svg') {
              return QrCode::svg($url, "qrcode", false, Enum::QR_ECLEVEL_M, $size);
              } else */
            if ($qrcodeFormat == 'png') {
                ob_start();
                QrCode::png($url, false, Enum::QR_ECLEVEL_M, $size);
                $imageString = base64_encode(ob_get_contents());
                ob_end_clean();
                return "<img width=\"{$size}\" src=\"data:image/png;base64,{$imageString}\" />";
            }
        }
        return '';
    }

    /**
     * @param $eid
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function createDownloadTicket($eid)
    {
        /** @var AmosEvents $eventModule */
        $eventModule = AmosEvents::instance();
        if (!is_null($eventModule)) {
            $temp_dir = $eventModule->getTempPath();
            /** @var Event $eventModel */
            $eventModel = $eventModule->createModel('Event');
            /** @var Event $event */
            $event = $eventModel::findOne(['id' => $eid]);
            $seatModel = null;
            if ($event) {
                $filenameTicket = $eid . '_' . \Yii::$app->user->id . '_Ticket.pdf';
                if ($event->has_tickets) {
                    /** @var EventInvitation $eventInvitationModel */
                    $eventInvitationModel = $eventModule->createModel('EventInvitation');
                    /** @var EventParticipantCompanion $eventParticipantCompanionModel */
                    $eventParticipantCompanionModel = $eventModule->createModel('EventParticipantCompanion');
                    /** @var EventInvitation $invitation */
                    $invitation = $eventInvitationModel::findOne(['event_id' => $eid, 'user_id' => \Yii::$app->user->id]);
                    if ($invitation) {
                        $companions = $eventParticipantCompanionModel::find()
                            ->andWhere(['event_invitation_id' => $invitation->id])
                            ->all();

                        // get assignd seat
                        $seat = null;
                        if ($event->isSeatManagement()) {
                            $assignedSeat = $invitation->assignedSeat;

                            if ($assignedSeat) {
                                $seat = $assignedSeat->getStringCoordinateSeat();
                                $filenameTicket = $assignedSeat->getTicketName();
                                $seatModel = $assignedSeat;
                            }
                        }

                        $content = \Yii::$app->controller->renderPartial(
                            !empty($event->ticket_layout_view) ? $event->ticket_layout_view
                                : 'pdf-tickets/general-layout',
                            [
                                'eventData' => $event,
                                'participantData' => [
                                    'nome' => $invitation->name,
                                    'cognome' => $invitation->surname,
                                    'azienda' => $invitation->company,
                                    'codice_fiscale' => $event->abilita_codice_fiscale_in_form
                                        ? $invitation->fiscal_code : "",
                                    'email' => $invitation->email,
                                    'note' => $invitation->notes,
                                    'accreditation_list_id' => $invitation->accreditation_list_id,
                                    'accreditationModel' => $invitation->getAccreditationList()->one(),
                                    'companion_of' => null,
                                    'seat' => $seat,

                                ],
                                'seatModel' => $seatModel,
                                'qrcode' => $event->has_qr_code ? EventsUtility::createQrCode(
                                    $event,
                                    $invitation,
                                    'participant',
                                    null,
                                    null,
                                    'png'
                                ) : '',
                            ]
                        );

                        foreach ($companions as $companion) {
                            $seat = null;
                            $seatModel = null;
                            // GET ASSIGNED SEAT
                            if ($event->isSeatManagement()) {
                                $assignedSeat = $companion->assignedSeat;
                                if ($assignedSeat) {
                                    $seat = $assignedSeat->getStringCoordinateSeat();
                                    $seatModel = $assignedSeat;
                                }
                            }
                            $content .= "<pagebreak />";

                            /** @var EventAccreditationList $eventAccreditationListModel */
                            $eventAccreditationListModel = $eventModule->createModel('EventAccreditationList');

                            $content .= \Yii::$app->controller->renderPartial(
                                !empty($event->ticket_layout_view)
                                    ? $event->ticket_layout_view : 'pdf-tickets/general-layout',
                                [
                                    'eventData' => $event,
                                    'participantData' => [
                                        'nome' => $companion->nome,
                                        'cognome' => $companion->cognome,
                                        'azienda' => $companion->azienda,
                                        'codice_fiscale' => $event->abilita_codice_fiscale_in_form
                                            ? $companion->codice_fiscale : "",
                                        'email' => $companion->email,
                                        'note' => $companion->note,
                                        'accreditation_list_id' => $companion->event_accreditation_list_id,
                                        'accreditationModel' => $eventAccreditationListModel::findOne([
                                            'id' => $companion->event_accreditation_list_id
                                        ]),
                                        'companion_of' => $invitation,
                                        'seat' => $seat,

                                    ],
                                    'seatModel' => $seatModel,
                                    'qrcode' => $event->has_qr_code ? EventsUtility::createQrCode(
                                        $event,
                                        $invitation,
                                        'companion',
                                        $companion,
                                        null,
                                        'png'
                                    ) : "",
                                ]
                            );
                        }
                        $filenameTicket = str_replace(" ", "_", $filenameTicket);
                        $pdf = new Pdf([
                            'filename' => $filenameTicket,
                            // set to use core fonts only
                            'mode' => Pdf::MODE_CORE,
                            // A4 paper format
                            'format' => Pdf::FORMAT_A4,
                            // portrait orientation
                            'orientation' => Pdf::ORIENT_PORTRAIT,
                            // stream to browser inline
                            'destination' => Pdf::DEST_BROWSER,
                            // your html content input
                            'content' => $content,
                            'methods' => [
                                //'SetHeader'=>[$event->title],
                                //'SetFooter'=>['{PAGENO}'],
                            ]
                        ]);

                        $pdf->marginBottom = 5;
                        $pdf->marginTop = 5;


                        $pdf_file = $temp_dir . DIRECTORY_SEPARATOR . $filenameTicket . '.pdf';
                        $savepath = $temp_dir . DIRECTORY_SEPARATOR . $filenameTicket . '.jpg';
                        $pdf->output(
                            $pdf->content,
                            $temp_dir . DIRECTORY_SEPARATOR . $filenameTicket . '.pdf',
                            'F'
                        );

                        exec("convert '" . $pdf_file . "' '" . $savepath . "'");
                        $invitation->ticket_downloaded_at = date("Y-m-d H:i:s");
                        $invitation->ticket_downloaded_by = (!empty(\Yii::$app->user)
                            && !empty(\Yii::$app->user->id)) ? \Yii::$app->user->id
                            : $invitation->user_id;
                        $invitation->save(false);
                        return "ticket_download/" . $filenameTicket . '.jpg';
                    } else {
                        return '';
                    }

                    return '';
                } else {
                    return '';
                }
            }
        }
        return '';
    }

    /**
     * This method checks if the user can view the "Enter in community" button in view.
     * @param Event $model
     * @param AmosEvents|null $eventsModule
     * @return bool
     */
    public static function showCommunityButtonInView(Event $model, $eventsModule = null)
    {
        if (is_null($eventsModule)) {
            $eventsModule = AmosEvents::instance();
        }
        return ($eventsModule->enableCommunitySections ||
            (!$eventsModule->enableCommunitySections &&
                EventsUtility::checkManager($model)));
    }

    /**
     * @param $event_id
     * @param $user_id
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function isEventParticipant($event_id, $user_id)
    {
        $event = Event::findOne($event_id);
        if ($event) {
            $count = CommunityUserMm::find()
                ->andWhere(['community_id' => $event->community_id])
                ->andWhere(['user_id' => $user_id])
                ->andWhere(['status' => CommunityUserMm::STATUS_ACTIVE])->count();
        }
        return $count;
    }

    /**
     * @param $event_calendars_id
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function isLoggedUserPartner($event_calendars_id)
    {
        $count = \open20\amos\events\models\EventCalendars::find()
            ->andWhere(['partner_user_id' => \Yii::$app->user->id])
            ->andWhere(['id' => $event_calendars_id])->count();
        return $count > 0;
    }


    /**
     * @return array|\open20\amos\tag\models\search\TagQuery|mixed
     */
    public static function getPreferenceCenterTags()
    {
        $tags = [];
        $root = Tag::find()
            ->andWhere(['codice' => Event::ROOT_TAG_PREFERENCE_CENTER])->one();

        if ($root) {
            $tags = Tag::find()->andWhere(['root' => $root->id, 'lvl' => 1])
                ->andWhere(['deleted_at' => null])
                ->orderBy('nome ASC')->all();
        }
        return $tags;
    }

    /**
     * @param $model
     * @return array
     */
    public static function getSidebarEvents($model)
    {
        $controllerDashboard = 'event-dashboard';
        $isEventInformative = ($model->eventType->event_type == \open20\amos\events\models\EventType::TYPE_INFORMATIVE);

        $menu = [
            [
                'mainMenu' => [
                    'label' => AmosEvents::t('amosevents', 'Dashboard'),
                    'icon' => '/sprite/material-sprite.svg#ic_dashboard',
                    'activeTargetAction' => 'view',
                    'activeTargetController' => $controllerDashboard,
                    'titleLink' => AmosEvents::t('amosevents', 'Evento'),
                    'url' => '/events/event-dashboard/view?id=' . $model->id
                ],
            ],
            [
                'mainMenu' => [
                    'label' => AmosEvents::t('amosevents', 'Info evento'),
                    'icon' => '/sprite/material-sprite.svg#ic_event',
                    'activeTargetAction' => 'info-event',
                    'activeTargetController' => $controllerDashboard,
                    'titleLink' => AmosEvents::t('amosevents', 'Info evento'),
                    'url' => '/events/event-dashboard/info-event?id=' . $model->id
                ],
            ],
            [
                'mainMenu' => [
                    'label' => AmosEvents::t('amosevents', 'Landing page'),
                    'icon' => '/sprite/material-sprite.svg#ic_web',
                    'activeTargetAction' => 'landing-settings',
                    'activeTargetController' => $controllerDashboard,
                    'titleLink' => AmosEvents::t('amosevents', 'Landing pages'),
                    'url' => '/events/event-dashboard/landing-settings'
                ],
                'dinamicSubMenu' => [
                    'menuLanding1' => [
                        'label' => AmosEvents::t('amosevents', 'Configura'),
                        'url' => '/events/event-dashboard/landing-settings?id=' . $model->id,
                        'activeTargetAction' => 'landing-settings',
                        'activeTargetController' => $controllerDashboard,
                        'titleLink' => AmosEvents::t('amosevents', 'Configura')
                    ],
                    'menuLanding2' => [
                        'label' => AmosEvents::t('amosevents', 'Gestione contenuti'),
                        'url' => '/events/event-dashboard/landing-manage-contents?id=' . $model->id,
                        'activeTargetAction' => 'landing-manage-contents',
                        'activeTargetController' => $controllerDashboard,
                        'titleLink' => AmosEvents::t('amosevents', 'Gestisci contenuti')
                    ],
                ]
            ],
        ];

        if (!$isEventInformative) {
            $menu[] =
                [
                    'mainMenu' => [
                        'label' => AmosEvents::t('amosevents', 'Modifica community'),
                        'icon' => '/sprite/material-sprite.svg#ic_account-supervisor-circle',
                        'activeTargetAction' => 'community',
                        'activeTargetController' => $controllerDashboard,
                        'titleLink' => AmosEvents::t('amosevents', 'Modifica community'),
                        'url' => '/events/event-dashboard/community?id=' . $model->id
                    ],
                ];

        }

        $menu[] = [
            'mainMenu' => [
                'label' => AmosEvents::t('amosevents', 'Template email'),
                'icon' => '/sprite/material-sprite.svg#ic_mail',
                'activeTargetAction' => 'template-emails',
                'activeTargetController' => $controllerDashboard,
                'titleLink' => AmosEvents::t('amosevents', 'Template email'),
                'url' => '/events/event-dashboard/template-emails?id=' . $model->id
            ],
        ];
        $menu[] = [
            'mainMenu' => [
                'label' => AmosEvents::t('amosevents', 'Gestione Invii'),
                'icon' => '/sprite/material-sprite.svg#ic_contact_mail',
                'activeTargetAction' => 'invite',
                'activeTargetController' => $controllerDashboard,
                'titleLink' => AmosEvents::t('amosevents', 'Inviti'),
                'url' => '/events/event-dashboard/invite?id=' . $model->id
            ],

        ];

        if ($model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED && !$isEventInformative) {
            $menu[] = [
                'mainMenu' => [
                    'label' => AmosEvents::t('amosevents', 'Gestione partecipanti'),
                    'icon' => '/sprite/material-sprite.svg#ic_settings',
                    'activeTargetAction' => 'info-event',
                    'activeTargetController' => $controllerDashboard,
                    'titleLink' => AmosEvents::t('amosevents', 'Gestione partecipanti'),
                    'url' => '/events/event/view?id=' . $model->id
                ],
                'dinamicSubMenu' => [
                    'menuLanding1' => [
                        'label' => AmosEvents::t('amosevents', 'Partecipanti'),
                        'url' => '/events/event/view?id=' . $model->id,
                        'activeTargetAction' => 'view',
                        'activeTargetController' => 'events',
                        'titleLink' => AmosEvents::t('amosevents', 'partecipanti')
                    ],
                    'menuLanding2' => [
                        'label' => AmosEvents::t('amosevents', 'Comunicazioni'),
                        'url' => '/events/event-dashboard/communications?id=' . $model->id,
                        'activeTargetAction' => 'communications',
                        'activeTargetController' => $controllerDashboard,
                        'titleLink' => AmosEvents::t('amosevents', 'Comunicazioni')
                    ],
                ]
            ];
        } else {
            $menu[] = [
                'mainMenu' => [
                    'icon' => '/sprite/material-sprite.svg#ic_settings',
                    'label' => AmosEvents::t('amosevents', 'Comunicazioni'),
                    'url' => '/events/event-dashboard/communications?id=' . $model->id,
                    'activeTargetAction' => 'communications',
                    'activeTargetController' => $controllerDashboard,
                    'titleLink' => AmosEvents::t('amosevents', 'Comunicazioni')
                ]
            ];
        }
        return $menu;
    }

    /**
     * @param $model
     * @return array
     */
    public static function getSidebarEventsUser()
    {
        $menu = [

            [
                'mainMenu' => [
                    'label' => AmosEvents::t('amosevents', 'Eventi a cui sono iscritto'),
                    'icon' => '/sprite/material-sprite.svg#ic_calendar-edit',
                    'activeTargetAction' => 'my-events',
                    'titleLink' => AmosEvents::t('amosevents', 'Eventi a cui sono iscritto'),
                    'url' => '/events/event-dashboard/my-events'
                ],
            ],
            [
                'mainMenu' => [
                    'label' => AmosEvents::t('amosevents', 'Eventi a cui sono stato invitato'),
                    'icon' => '/sprite/material-sprite.svg#ic_event',
                    'activeTargetAction' => 'my-invitations',
                    'titleLink' => AmosEvents::t('amosevents', 'Eventi a cui sono stato invitato'),
                    'url' => '/events/event-dashboard/my-invitations'
                ],
            ],
            [
                'mainMenu' => [
                    'label' => AmosEvents::t('amosevents', 'Eventi di mio interesse'),
                    'icon' => '/sprite/material-sprite.svg#ic_calendar-heart',
                    'activeTargetAction' => 'own-interest',
                    'titleLink' => AmosEvents::t('amosevents', 'Eventi di mio interesse'),
                    'url' => '/events/event-dashboard/own-interest'
                ],
            ],
            [
                'mainMenu' => [
                    'label' => AmosEvents::t('amosevents', 'Tutti gli eventi'),
                    'icon' => '/sprite/material-sprite.svg#ic_event',
                    'activeTargetAction' => 'all',
                    'titleLink' => AmosEvents::t('amosevents', 'Tutti gli eventi'),
                    'url' => '/events/event-dashboard/all'
                ],
            ],


        ];

        return $menu;
    }

    /**
     * @param $nav_id
     */
    public static function getTemplatePreviewHtml($nav_id)
    {
        $utility = new \amos\cmsbridge\utility\CmsUtility();
        $page = new \amos\cmsbridge\models\PostCmsCreatePage();

        $page->nav_id = $nav_id;
        $page->cms_user_id = $utility->loginCms();
        $page->lang_id = 1; // lingua
        $result = $utility->getPreviewUrlCmsPageHtml($page);
        return $result;
        //        pr($result);

    }


    /**
     * @param $id
     */
    public static function setScope($id)
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh)) {
            $moduleCwh->setCwhScopeInSession(
                [
                    'community' => $id,
                ],
                [
                    'mm_name' => 'community_user_mm',
                    'entity_id_field' => 'community_id',
                    'entity_id' => $id
                ]
            );
        }
    }

    public static function doSendInvitations($eid, array $rows, $registerSendDatetime = false, $saveInvitationSent = false)
    {
        $cnt = 0;
        $errs = '';
        $controller = \Yii::$app->controller;

        /** @var Event $eventModel */
        $eventModel = $controller->eventsModule->createModel('Event');
        $event = $eventModel::findOne(['id' => $eid]);

        foreach ($rows as $r => $row) {
            try {
                // Sets sender
                $from = $controller->getFromMail($event);
                EventMailUtility::setLayoutMail($event->email_ticket_layout_custom);
                if ($controller->eventsModule->enableAutoInviteUsers && ($row['type'] == EventInvitation::INVITATION_TYPE_REGISTERED)) {
                    $user = User::findOne($row['user_id']);
                    $profile = $user->userProfile;
                    // Build url signup with user's data
                    $extUrlYes = Url::base(true) . Url::toRoute([
                            '/events/event/event-signup', 'eid' => $event->id, 'pName' => $row['name'],
                            'pSurname' => $row['surname'], 'pEmail' => $row['email'], 'pCode' => $row['code']
                        ]);
                    $regUrlNo = Url::base(true) . Url::toRoute(['reject', 'id' => $event->id]);
                    $row['email'] = $user['email'];
                    $viewInvitation = 'email_invitation_registered';
                    if (!empty($event->email_invitation_custom)) {
                        $viewInvitation = $event->email_invitation_custom;
                    }
                    $text = $controller->renderPartial(
                        $viewInvitation,
                        [
                            'event' => $event,
                            'user' => $user,
                            'profile' => $profile,
                            'urlYes' => $extUrlYes,
                            'urlNo' => $regUrlNo,
                        ]
                    );
                } else {
                    $viewInvitation = 'email_invitation';
                    if (!empty($event->email_invitation_custom)) {
                        $viewInvitation = $event->email_invitation_custom;
                    }
                    $extUrlYes = Url::base(true) . Url::toRoute([
                            '/events/event/event-signup', 'eid' => $event->id, 'pName' => $row['name'],
                            'pSurname' => $row['surname'], 'pEmail' => $row['email']
                        ]);
                    $text = $controller->renderPartial(
                        $viewInvitation,
                        [
                            'event' => $event,
                            'user' => $row,
                            'urlYes' => $extUrlYes
                        ]
                    );
                }
                // Sends e-mail
                $ok = Email::sendMail(
                    $from,
                    [$row['email']],
                    'Invito - ' . html_entity_decode($event->title),
                    $text,
                    [],
                    [],
                    [],
                    0,
                    false
                );
                if ($saveInvitationSent && $ok) {
                    $intivationSent = new EventInvitationSent();
                    $intivationSent->event_internal_list_id = $row['event_internal_list_id'];
                    $intivationSent->user_id = $row['user_id'];
                    $intivationSent->email = $row['email'];
                    $intivationSent->send_at = date('Y-m-d H:i:s');
                    $intivationSent->template = $row['template'];
                    $intivationSent->save();
                }
                if ($registerSendDatetime && $ok) {
                    // Marks invitation as sent
                    /** @var EventInvitation $eventInvitationModel */
                    $eventInvitationModel = $controller->eventsModule->createModel('EventInvitation');
                    $invitation = $eventInvitationModel::findOne($row['id']);
                    $invitation->invitation_sent_on = new \yii\db\Expression('now()');
                    $invitation->save();
                }

                ++$cnt;
            } catch (\Exception $e) {
                $errs .= (strlen($errs) > 0 ? '<br>' : '') . $e->getMessage();
                $errs .= '<br>' . 'Errore in fase di importazione della riga ' . (1 + $r) . ' : codice fiscale già presente ';
            }
        }

        return ['cnt' => $cnt, 'errs' => $errs];
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getCurrentDg($operator = true)
    {
        $query = EventGroupReferent::find()
            ->innerJoinWith('eventGroupReferentMms')
            ->andWhere(['user_id' => \Yii::$app->user->id]);
        if($operator){
            $query->andWhere(['exclude_from_query' => true]);
        }
        $group = $query->one();
        return $group;
    }


    /**
     * @param $user_id
     * @param $model Event|Community
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function isUserEventOperator($user_id, $model)
    {
        if (\Yii::$app->user->can('SUPER_USER_EVENT')) {
            return true;
        }
        $group = self::getCurrentDg();
        $groupReferentMm = null;
        if ($group) {
            if (get_class($model) == Community::className()) {
                $event = Event::find()
                    ->andWhere(['community_id' => $model->id])->one();
            } else {
                $event = $model;
            }
            $groupReferentMm = EventGroupReferentMm::find()
                ->innerJoin('event', 'event.event_group_referent_id = event_group_referent_mm.event_group_referent_id')
                ->andWhere(['event.id' => $event->id])
                ->andWhere(['event_group_referent_mm.event_group_referent_id' => $group->id])
                ->andWhere(['event_group_referent_mm.user_id' => $user_id])->one();
        }
        if (!empty($groupReferentMm)) {
            if (\Yii::$app->authManager->checkAccess($user_id, 'EVENT_DG_OPERATOR')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $model Event
     * @return string
     */
    public static function getUrlLanding($model)
    {
        $landing = $model->eventLanding;
        if ($landing) {
            return \Yii::$app->params['platform']['frontendUrl'] . '/it/' . $landing->url;
        }
        return '';
    }

    /**
     * @param $model Event
     * @return string
     */
    public static function getUrlLandingPreview($model)
    {
        $landing = $model->eventLanding;
        if ($landing) {
            return \Yii::$app->params['platform']['frontendUrl'] . '/api/1/preview-event?itemId=' . $landing->luya_page_id;
        }
        return '';
    }

    /**
     * @param $title
     * @return string
     */
    public static function getClassPreviewLanding($title)
    {
        return 'gradient-' . str_replace('/', '-', strtolower($title));
    }

    /**
     * @param $event
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function canViewCommunityEvent($event)
    {
        if ($event->community_id && $event->show_community) {
            $ismemeber = \open20\amos\community\models\CommunityUserMm::find()
                ->andWhere(['community_id' => $event->community_id, 'user_id' => Yii::$app->user->id])->count();
            return $ismemeber > 0;
        }
        return false;
    }

    /**
     * @param $event
     * @return bool
     */
    public static function isEventCommunityEnabled($event)
    {
        if ($event->community_id && $event->show_community) {
            return true;
        }
        return false;
    }


    /**
     * @return array
     */
    public static function getLabelPreference()
    {

        $tags = EventsUtility::getPreferenceCenterTags();
        $preferenceTags = [];
        $assetBundle = WizardEventAsset::register(\Yii::$app->controller->view);
        $baseUrl = $assetBundle->baseUrl;

        foreach ($tags as $tag) {
//            $tag->codice;
            $src = $baseUrl . '/img/' . $tag->codice . '.png';
            // $preferenceTags [$tag->id] = Html::img($src, ['class' => 'img-tag']). $tag->nome;
            $preferenceTags [$tag->id] = "<div class='img-tag'>" . Html::img($src, ['class' => '']) . "</div>" . $tag->nome;
            $preferenceTags [$tag->id] = "<div class='img-tag'>" . "</div>" . $tag->nome;
        }
        return $preferenceTags;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function currentDgEvents()
    {
        $group = self::getCurrentDg();
        if ($group) {
            return Event::find()->andWhere(['event_group_referent_id' => $group->id])->all();
        }
        if (\Yii::$app->user->can('SUPER_USER_EVENT')) {
            return Event::find()->all();
        }
        return [];
    }


    /**
     * @param $currentDgId
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function eventFathersQuery($currentDgId)
    {
        $query = \open20\amos\events\models\Event::find()
            ->andWhere(['is_father' => true])
            ->andFilterWhere(['event_group_referent_id' => $currentDgId]);
        return $query;
    }

    /**
     * @return string
     */
    public static function getToolbarTextEditor()
    {
        //  "fullscreen | undo redo code | styleselect | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media insertdatetime | removeformat | keyvalues",
        return "fullscreen | undo redo | bold italic underline strikethrough | bullist numlist | link | keyvalues";
    }

    /**
     * @param $courseId
     * @param $user_id
     * @return bool
     */
    public static function checkIfCourseIsCompleted($courseId, $user_id)
    {
        $courseId = 9;
        $completed_statuses = \open20\amos\moodle\models\ServiceCall::ACTIVITY_STATUS_COMPLETED_LIST;
        $serviceCall = new \open20\amos\moodle\models\ServiceCall();
        $topicId = null;

        $moodleUser = \open20\amos\moodle\models\MoodleUser::find()->andWhere(['user_id' => $user_id])->one();
        $whitelist = ['scorm'];
        $results = [];
        if (!empty($moodleUser) && !empty($courseId)) {
            try {
                $contentsList = $serviceCall->getActivitiesCompletionStatus($courseId, $moodleUser->moodle_userid);
                foreach ($contentsList as $content) {

                 //   pr($moodleUser->moodle_email);
//                    pr($content, $moodleUser->moodle_email);
                    if (!isset($results[$content['cmid']])) {
                      //  pr('sss');
                        $results[$content['cmid']] = false;
                    }
                    if (in_array($content['modname'], $whitelist)) {
                        if (in_array($content['state'], $completed_statuses)) {
                            $results[$content['cmid']] = true;
                        }
                    }
                }
                // check if all courses are completed
                if(count($results) == 0){
                    return false;
                }
                foreach ($results as $result) {
                    if ($result == false) {
                        return false;
                    }
                }
                return true;
            }catch (\open20\amos\moodle\exceptions\MoodleException $e){

            }


        }
        return false;
    }

}


