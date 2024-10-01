<?php
/**
 * Aria S.p.A.
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
use open20\amos\events\AmosEvents;
use open20\amos\events\models\AgidEventTypology;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventAccreditationList;
use open20\amos\events\models\EventCalendars;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventParticipantCompanion;
use open20\amos\events\models\EventRoom;
use open20\amos\events\models\EventType;
use open20\amos\invitations\models\Invitation;
use kartik\mpdf\Pdf;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
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
     * Create a community for the event.
     * @param Event $model
     * @param string $managerStatus
     * @return bool
     */
    public static function createCommunity($model, $managerStatus = '')
    {
        /** @var AmosCommunity $communityModule */
        $communityModule = Yii::$app->getModule('community');
        $title = ($model->title ? $model->title : '');
        $description = ($model->description ? $model->description : '');
        $eventType = $model->eventType;
        $type = CommunityType::COMMUNITY_TYPE_CLOSED; // DEFAULT TYPE
        
        if (!is_null($eventType) && $eventType->event_type == EventType::TYPE_OPEN) {
            $type = CommunityType::COMMUNITY_TYPE_OPEN;
        } else if (!is_null($eventType) && $eventType->event_type == EventType::TYPE_UPON_INVITATION) {
            $type = CommunityType::COMMUNITY_TYPE_CLOSED;
        }
        $context = AmosEvents::instance()->model('Event');
        $managerRole = $model->getManagerRole();
        try {
            $model->community_id = $communityModule->createCommunity($title,
                $type, $context, $managerRole, $description, $model,
                $managerStatus);
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
        if (isset($moduleTag) && in_array($eventsModule->model('Event'),
                $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
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
        $eventLogo = File::findOne(['model' => AmosEvents::instance()->model('Event'), 'attribute' => 'eventLogo',
            'item_id' => $model->id]);
        if (!is_null($eventLogo)) {
            $communityLogo = File::findOne(['model' => Community::className(), 'attribute' => 'communityLogo',
                'item_id' => $model->community_id]);
            if (!is_null($communityLogo)) {
                if ($eventLogo->hash != $communityLogo->hash) {
                    $communityLogo->delete();
                    $ok = EventsUtility::newCommunityLogo($model->community_id,
                        $eventLogo);
                }
            } else {
                $ok = EventsUtility::newCommunityLogo($model->community_id,
                    $eventLogo);
            }
        } else {
            $communityLogo = File::findOne(['model' => Community::className(), 'attribute' => 'communityLogo',
                'item_id' => $model->community_id]);
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
        $toSkipFields = ['id', 'model', 'attribute', 'item_id'];
        foreach ($eventLogoAttributes as $fieldName => $fieldValue) {
            if (!in_array($fieldName, $toSkipFields)) {
                $communityLogo->{$fieldName} = $fieldValue;
            }
        }
        $communityLogo->model = Community::className();
        $communityLogo->attribute = 'communityLogo';
        $communityLogo->item_id = $communityId;
        return $communityLogo->save();
    }
    
    public static function deleteCommunityLogo($model)
    {
        $communityLogo = File::findOne(['model' => Community::className(), 'attribute' => 'communityLogo',
            'item_id' => $model->community_id]);
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
        $confirmedEventManagers = self::findEventManagers($event,
            CommunityUserMm::STATUS_ACTIVE);
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
                'user_id' => $userId, 'provider' => 'google']);
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
    public static function insertOrUpdateGoogleEvent($serviceGoogle,
                                                     $calendarId, $eventCalendar)
    {
        
        $eventId = $eventCalendar->getId();
        try {
            $eventCalendarExists = $serviceGoogle->events->get($calendarId,
                $eventId);
            $isUpdate = true;
        } catch (\Google_Service_Exception $ex) {
            $isUpdate = false;
        }
        try {
            if (!$isUpdate) {
                $serviceGoogle->events->insert($calendarId, $eventCalendar);
            } else {
                $serviceGoogle->events->update($calendarId,
                    $eventCalendar->getId(), $eventCalendar);
            }
        } catch (\Google_Service_Exception $e) {
            Yii::getLogger()->log('Google calendar insert or update event ' . $eventId . ': ' . $e->getMessage(),
                Logger::LEVEL_WARNING);
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
    public static function deleteGoogleEvent($serviceGoogle, $calendarId,
                                             $eventId)
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
        return $communityUtil->isManagerLoggedUser($event);
    }
    
    /**
     * @param CommunityContextInterface $model
     * @return bool
     */
    public static function hasPrivilegesLoggedUser($model)
    {
        $foundRow = CommunityUserMm::findOne([
            'community_id' => $model->getCommunityModel()->id,
            'user_id' => \Yii::$app->getUser()->getId(),
            'role' => $model->getPriviledgedRoles()
        ]);
        return (!is_null($foundRow));
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
    public static function createQrCode($event = null, $invitation = null,
                                        $type = '', $companion = null,
                                        $url = null, $qrcodeFormat = 'png',
                                        $size = 350)
    {
        if ($type == 'participant') {
            if ($event && $invitation) {
                $url = Url::base(true) . Url::toRoute(['register-participant', 'eid' => $event->id,
                        'pid' => (empty($invitation->user_id) ? '' : $invitation->user_id), 'iid' => $invitation->id]);
            }
        } elseif ($type == 'companion') {
            if ($event && $invitation) {
                $url = Url::base(true) . Url::toRoute(['register-companion', 'eid' => $event->id,
                        'pid' => $invitation->user_id, 'iid' => $invitation->id,
                        'cid' => $companion['id']]);
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
                        if ($event->seats_management) {
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
                                'qrcode' => $event->has_qr_code ? EventsUtility::createQrCode($event,
                                    $invitation, 'participant', null, null,
                                    'png') : '',
                            ]
                        );
                        
                        foreach ($companions as $companion) {
                            $seat = null;
                            $seatModel = null;
                            // GET ASSIGNED SEAT
                            if ($event->seats_management) {
                                $assignedSeat = $companion->assignedSeat;
                                if ($assignedSeat) {
                                    $seat = $assignedSeat->getStringCoordinateSeat();
                                    $seatModel = $assignedSeat;
                                }
                            }
                            $content .= "<pagebreak />";
                            
                            /** @var EventAccreditationList $eventAccreditationListModel */
                            $eventAccreditationListModel = $eventModule->createModel('EventAccreditationList');
                            
                            $content .= \Yii::$app->controller->renderPartial(!empty($event->ticket_layout_view)
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
                                            'id' => $companion->event_accreditation_list_id]),
                                        'companion_of' => $invitation,
                                        'seat' => $seat,
                                    
                                    ],
                                    'seatModel' => $seatModel,
                                    'qrcode' => $event->has_qr_code ? EventsUtility::createQrCode($event,
                                        $invitation, 'companion', $companion,
                                        null, 'png') : "",
                                ]);
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
                        $pdf->output($pdf->content, $temp_dir . DIRECTORY_SEPARATOR . $filenameTicket . '.pdf',
                            'F');
                        
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
    public static function showCommunityButtonInView( $model, $eventsModule = null)
    {
        if (is_null($eventsModule)) {
            $eventsModule = AmosEvents::instance();
        }
        return (
            $eventsModule->enableCommunitySections ||
            (
                !$eventsModule->enableCommunitySections &&
                EventsUtility::checkManager($model)
            )
        );
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
        /** @var EventCalendars $eventCalendarsModel */
        $eventCalendarsModel = AmosEvents::instance()->createModel('EventCalendars');
        $count = $eventCalendarsModel::find()
            ->andWhere(['partner_user_id' => \Yii::$app->user->id])
            ->andWhere(['id' => $event_calendars_id])->count();
        return $count > 0;
    }
    
    /**
     * This method returns all events rooms.
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    public static function findAllEventRooms()
    {
        /** @var AmosEvents $eventsModule */
        $eventsModule = AmosEvents::instance();
        /** @var EventRoom $eventRoomModel */
        $eventRoomModel = $eventsModule->createModel('EventRoom');
        /** @var ActiveQuery $query */
        $query = $eventRoomModel::find();
        $eventRooms = $query->all();
        return $eventRooms;
    }
    
    /**
     * This method returns all events rooms ready for a form select widget.
     * @param EventRoom[]|null $objects
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEventRoomsReadyForSelect($objects = null)
    {
        $eventRooms = [];
        if (is_null($objects)) {
            /** @var AmosEvents $eventsModule */
            $eventsModule = AmosEvents::instance();
            if (!is_null($eventsModule)) {
                /** @var EventRoom $eventRoomModel */
                $eventRoomModel = $eventsModule->createModel('EventRoom');
                /** @var ActiveQuery $query */
                $query = $eventRoomModel::find();
                $query->select(['room_name']);
                $query->indexBy('id');
                $eventRooms = $query->column();
            }
        } else {
            $eventRooms = ArrayHelper::map($objects, 'id', 'room_name');
        }
        return $eventRooms;
    }
    
    /**
     * This method returns all events rooms seats available ready for the form select widget options.
     * @param EventRoom[]|null $objects
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEventRoomsDataForSelect($objects = null)
    {
        $eventRooms = [];
        $dataToReturn = [];
        if (is_null($objects)) {
            /** @var AmosEvents $eventsModule */
            $eventsModule = AmosEvents::instance();
            if (!is_null($eventsModule)) {
                /** @var EventRoom $eventRoomModel */
                $eventRoomModel = $eventsModule->createModel('EventRoom');
                /** @var ActiveQuery $query */
                $query = $eventRoomModel::find();
                $query->select(['available_seats']);
                $query->indexBy('id');
                $eventRooms = $query->column();
            }
        } else {
            $eventRooms = ArrayHelper::map($objects, 'id', 'available_seats');
        }
        if (!empty($eventRooms)) {
            foreach ($eventRooms as $eventRoomId => $seatsAvailable) {
                $dataToReturn[$eventRoomId] = ['data' => ['available_seats' => $seatsAvailable]];
            }
        }
        return $dataToReturn;
    }
    
    /**
     * This method returns the event room available seats by the event room id.
     * @param int $eventRoomId
     * @param AmosEvents|null $eventsModule
     * @return false|string|null
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEventRoomAvailableSeatsById($eventRoomId, $eventsModule = null)
    {
        if (is_null($eventsModule)) {
            /** @var AmosEvents $eventsModule */
            $eventsModule = AmosEvents::instance();
        }
        /** @var EventRoom $eventRoomModel */
        $eventRoomModel = $eventsModule->createModel('EventRoom');
        /** @var ActiveQuery $query */
        $query = $eventRoomModel::find();
        $query->select(['available_seats']);
        $query->andWhere(['id' => $eventRoomId]);
        $eventRoomAvailableSeats = $query->scalar();
        return $eventRoomAvailableSeats;
    }
    
    /**
     * @param AmosEvents|null $eventsModule
     * @param AgidEventTypology[]|null $objects
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEventAgidTypologiesReadyForSelect($eventsModule = null, $objects = null)
    {
        if (is_null($eventsModule)) {
            /** @var AmosEvents $eventsModule */
            $eventsModule = AmosEvents::instance();
        }
        
        if (is_null($objects)) {
            /** @var AgidEventTypology $agidEventTypologyModel */
            $agidEventTypologyModel = $eventsModule->createModel('AgidEventTypology');
            /** @var ActiveQuery $query */
            $query = $agidEventTypologyModel::find();
            $query->orderBy([
                'level' => SORT_ASC,
                'name' => SORT_ASC
            ]);
            $agidEventTipologies = $query->all();
        } else {
            $agidEventTipologies = $objects;
        }
        
        $readyForSelect = [];
        
        foreach ($agidEventTipologies as $agidEventTipology) {
            /** @var AgidEventTypology $agidEventTipology */
            if (!is_null($agidEventTipology->parent_id)) {
                $readyForSelect[$agidEventTipology->id] = self::makeAgidEventTypologyConcat($agidEventTipology);
            } else {
                $readyForSelect[$agidEventTipology->id] = $agidEventTipology->name;
            }
        }
        
        return $readyForSelect;
    }
    
    /**
     * @param AgidEventTypology $agidEventTipology
     * @return string
     */
    protected static function makeAgidEventTypologyConcat($agidEventTipology)
    {
        $concatParentsNames = $agidEventTipology->name;
        if (is_null($agidEventTipology->parent_id)) {
            return $concatParentsNames;
        }
        return self::makeAgidEventTypologyConcat($agidEventTipology->parent) . ' > ' . $concatParentsNames;
    }
    
    /**
     * This method returns the AGID administrative persons.
     * @return array
     */
    public static function getAgidValidatedPersons($onlyQuery = false)
    {
        /** @var \open20\agid\person\Module|AmosModule $agidPersonModule */
        $agidPersonModule = \Yii::$app->getModule('person');
        if (!is_null($agidPersonModule)) {
            return $agidPersonModule->getAdministrativeValidatedPersons($onlyQuery);
        }
        return null;
    }

    /**
     * This method returns the AGID administrative persons.
     * @return array
     */
    public static function getAgidValidatedPoliticPersons($onlyQuery = false)
    {
        /** @var \open20\agid\person\Module|AmosModule $agidPersonModule */
        $agidPersonModule = \Yii::$app->getModule('person');
        if (!is_null($agidPersonModule)) {
            return $agidPersonModule->getPoliticValidatedPersons($onlyQuery);
        }
        return null;
    }
    /**
     * This method returns the AGID administrative persons ready for select.
     * @return array
     */
    public static function getEventAgidPersonsReadyForSelect()
    {
        $readyForSelect = [];
        $validatedPersons = self::getAgidValidatedPoliticPersons();
        if (!empty($validatedPersons)) {
            foreach ($validatedPersons as $person) {
                /** @var \open20\agid\person\models\AgidPerson $person */
                $readyForSelect[$person->id] = $person->getSurnameName();
            }
        }
        return $readyForSelect;
    }
}
