<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\controllers
 * @category   CategoryName
 */

namespace open20\amos\events\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventAccreditationList;
use open20\amos\events\models\EventEmailTemplates;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventInvitationsUpload;
use open20\amos\events\models\EventLengthMeasurementUnit;
use open20\amos\events\models\EventParticipantCompanion;
use open20\amos\events\models\EventParticipantCompanionDynamic;
use open20\amos\events\models\EventSeats;
use open20\amos\events\models\EventType;
use open20\amos\events\models\FormAssignSeat;
use open20\amos\events\models\RegisterGroupForm;
use open20\amos\events\rules\EventsCheckInRule;
use open20\amos\events\rules\EventsUpdateRule;
use open20\amos\events\utility\EventsUtility;
use open20\amos\events\utility\ICS;
use open20\amos\events\utility\MultipleModel;
use kartik\mpdf\Pdf;
use moonland\phpexcel\Excel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use open20\amos\events\utility\EventMailUtility;

/**
 * Class EventController
 * This is the class for controller "EventController".
 * @package open20\amos\events\controllers
 */
class EventController extends base\EventController
{
    /**
     * @var string $layout
     */
    public $layout = 'list';
    private $min_seats_event = 1;

    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'calculate-end-date-hour',
                                'created-by',
                                'get-event-by-id',
                                'to-publish',
                                'management',
                                'validate',
                                'reject',
                                'own-interest',
                            ],
                            'roles' => ['EVENTS_ADMINISTRATOR']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'own-interest',
                                'all-events',
                                'download-import-file-example'
                            ],
                            'roles' => ['EVENTS_READER']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'calculate-end-date-hour',
                                'created-by',
                                'get-event-by-id',
                            ],
                            'roles' => ['EVENTS_CREATOR']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'calculate-end-date-hour',
                                'get-event-by-id',
                                'to-publish',
                                'validate',
                                'reject',
                            ],
                            'roles' => ['EVENTS_VALIDATOR', 'PLATFORM_EVENTS_VALIDATOR', 'EventValidateOnDomain']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'calculate-end-date-hour',
                            ],
                            'roles' => ['EVENT_READ']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'management',
                            ],
                            'roles' => ['EVENTS_MANAGER']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'subscribe',
                                'accept',
                            ],
                            'roles' => ['@']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'import-invitations',
                                'send-invitations',
                                'change-participant-accreditation-list',
                                'change-companion-accreditation-list',
                                'send-tickets-not-sent',
                                'send-ticket',
                                'send-tickets-massive',
                                'force-download-ics',
                                'get-qr-code-companion',
                                'get-qr-code-participant',
                                'participant-detail',
                                'download-participants-excel',
                                'remove-companion',
                                'set-session-search-params',
                                'get-session-search-params',
                                'remove-seat',
                                'assign-seat',
                                'import-seats',
                                'view-sector',
                                'delete-sector'
                            ],
                            'roles' => ['EVENTS_ADMINISTRATOR', 'EVENTS_MANAGER', EventsUpdateRule::className()]
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'elimina-m2m',
                                'annulla-m2m',
                                'associate-user-to-event-m2m',
                                'subscribe-user-to-event'
                            ],
                            'roles' => ['ASSOCIATE_USER_TO_EVENT_PERMISSION']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'get-event-by-id',
                                'event-calendar-widget'
                            ],
                            'roles' => ['EVENT_READ']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'subscribe-and-register',
                                'test',
                            ],
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'event-signup',
                                'event-signup-group',
                                'download-tickets',
                                'download-ics',
                                'remove-signup-to-event',
                                'get-rows-ajax',
                                'get-seats-ajax',
                            ],
                            'roles' => ['?', '@']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'register-companion',
                                'register-participant',
                                'remove-companion-attendance',
                                'remove-participant-attendance',
                                'participants'
                            ],
                            'roles' => [EventsCheckInRule::className(), 'EVENTS_ADMINISTRATOR', 'EVENTS_MANAGER']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'participant-detail',
                            ],
                            'roles' => [EventsCheckInRule::className()]
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'show-companions-list-only'
                            ],
                            'roles' => ['EVENTS_CHECK_IN', 'EVENTS_ADMINISTRATOR', 'EVENTS_MANAGER', 'EVENT_READ']
                        ]
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
            ]);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setMmTableName(CommunityUserMm::className());
        $this->setStartObjClassName(User::className());
        $this->setMmStartKey('community_id');
        $this->setTargetObjClassName(AmosAdmin::instance()->createModel('UserProfile')->className());
        $this->setMmTargetKey('user_id');
        $this->setRedirectAction('update');
        $this->setModuleClassName(AmosEvents::className());
        $this->setCustomQuery(true);
        $this->on(M2MEventsEnum::EVENT_BEFORE_CANCEL_ASSOCIATE_M2M, [$this, 'beforeCancelAssociateM2m']);
    }

    /**
     * @param \yii\base\Event $event
     */
    public function beforeCancelAssociateM2m($event)
    {
        $urlPrevious = Yii::$app->session->get(AmosAdmin::beginCreateNewSessionKey());
        if (!$urlPrevious) {
            $urlPrevious = Url::previous();
        }
        $this->setRedirectArray($urlPrevious);
    }

    /**
     * @return mixed
     */
    public function actionAssociateUserToEventM2m()
    {
        Url::remember();
        $this->setUpLayout('main');
        $this->setMmTableName(CommunityUserMm::className());
        $this->setStartObjClassName(User::className());
        $this->setMmStartKey('community_id');
        $this->setTargetObjClassName(AmosAdmin::instance()->createModel('UserProfile')->className());
        $this->setMmTargetKey('user_id');
        $this->setRedirectAction('update');
        $this->setModuleClassName(AmosEvents::className());
        $this->setCustomQuery(true);
        $this->setTargetUrl('associate-user-to-event-m2m');
        $userProfileId = Yii::$app->request->get('id');
        $userId = UserProfile::findOne(['id' => $userProfileId])->user_id;

        return $this->actionAssociaM2m($userId);
    }

    /**
     * @return string
     */
    public function actionCreatedBy()
    {
        $this->setDataProvider($this->modelSearch->searchCreatedBy(Yii::$app->request->getQueryParams()));

        if ($this->eventsModule->actionCreatedByOnlyViewGrid) {
            $this->setAvailableViews([
                'grid' => [
                    'name' => 'grid',
                    'label' => AmosEvents::t('amosevents',
                        '{tableIcon}' . Html::tag('p', AmosEvents::t('amosevents', 'Table')),
                        [
                            'tableIcon' => AmosIcons::show('view-list-alt')
                        ]),
                    'url' => '?currentView=grid'
                ]
            ]);
            $this->setCurrentView($this->getAvailableView('grid'));
        }

        return $this->baseListsAction(AmosEvents::t('amosevents', 'Created by me'));
    }

    /**
     * @return string
     */
    public function actionCalculateEndDateHour()
    {
        $retval = [];
        if (Yii::$app->getRequest()->getIsAjax()) {
            $post = Yii::$app->getRequest()->post();
            $beginDateHour = isset($post['beginDateHour']) ? $post['beginDateHour'] : null;
            $lengthValue = isset($post['lengthValue']) ? $post['lengthValue'] : null;
            $lengthMUId = isset($post['lengthMUId']) ? $post['lengthMUId'] : null;
            if ($beginDateHour && $lengthValue && $lengthMUId) {
                $dbDateTimeFormat = 'Y-m-d H:i:s';
                $dateTime = \DateTime::createFromFormat($dbDateTimeFormat, $beginDateHour);
                /** @var EventLengthMeasurementUnit $eventLengthMeasurementUnitModel */
                $eventLengthMeasurementUnitModel = $this->eventsModule->createModel('EventLengthMeasurementUnit');
                $eventLengthMU = $eventLengthMeasurementUnitModel::findOne($lengthMUId);
                if (!is_null($dateTime) && !is_null($eventLengthMU) && is_numeric($lengthValue)) {
                    $interval = 'P';
                    $timePeriod = ['H', 'M', 'S'];
                    if (in_array($eventLengthMU->date_interval_period, $timePeriod)) {
                        $interval .= 'T';
                    }
                    $interval .= $lengthValue . $eventLengthMU->date_interval_period;
                    $dateTime->add(new \DateInterval($interval));
                    $retValDateTime = $dateTime->format($dbDateTimeFormat);
                    $retval['datetime'] = $retValDateTime;
                    $retval['date'] = Yii::$app->getFormatter()->asDate($retValDateTime);
                    $retval['time'] = Yii::$app->getFormatter()->asTime($retValDateTime);
                }
            }
        }

        return json_encode($retval);
    }

    /**
     * @return bool|string
     */
    public function actionGetEventById()
    {
        /**
         * post('id') is in the form 'cal-event-$id'
         */
        $elements = explode('-', Yii::$app->request->post('id'));
        $id = $elements[count($elements) - 1];
        if (!is_null($id)) {
            /** @var Event $event */
            $event = $this->findModel($id);

            return $this->renderAjax('calendar_event_details', ['model' => $event]);
        }

        return false;
    }

    /**
     * @return string
     */
    public function actionToPublish()
    {
        $this->setDataProvider($this->modelSearch->searchToPublish(Yii::$app->request->getQueryParams()));

        return $this->baseListsAction(AmosEvents::t('amosevents', 'To publish'), true, 'toPublish');
    }

    /**
     * @return string
     */
    public function actionManagement()
    {
        $this->setDataProvider($this->modelSearch->searchManagement(Yii::$app->request->getQueryParams()));

        return $this->baseListsAction(AmosEvents::t('amosevents', 'Events management'), true, 'management');
    }

    /**
     * Lists own interests Event models.
     * @return string
     */
    public function actionOwnInterest()
    {
        $moduleNotify = \Yii::$app->getModule('notify');
        if ($moduleNotify) {
            /** @var \open20\amos\notificationmanager\AmosNotify $moduleNotify */
            $this->modelSearch->setNotifier($moduleNotify);
        }
        $this->setDataProvider($this->modelSearch->searchCalendarView(Yii::$app->request->getQueryParams()));

        return $this->baseListsAction(AmosEvents::t('amosevents', '#page_title_own_interest'));
    }

    /**
     * Lists all Event models.
     * @return string
     */
    public function actionAllEvents()
    {
        $moduleNotify = \Yii::$app->getModule('notify');
        if ($moduleNotify) {
            /** @var \open20\amos\notificationmanager\AmosNotify $moduleNotify */
            $this->modelSearch->setNotifier($moduleNotify);
        }
        $this->setDataProvider($this->modelSearch->searchAllEvents(Yii::$app->request->getQueryParams()));

        return $this->baseListsAction(AmosEvents::t('amosevents', '#all_events'));
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSubscribeAndRegister()
    {
        $this->setUpLayout('main');
        // Some stuff
        $ses = Yii::$app->getSession();
        $fwd = '/';

        $userregister = false;

        // Gets invitation
        $code = Yii::$app->request->get('code');
        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');
        $invitation = $eventInvitationModel::findOne(['code' => $code]);
        if (!$invitation || $invitation->state != EventInvitation::INVITATION_STATE_INVITED) {
            $ses->addFlash('danger', 'Invito non trovato');
            return $this->redirect('/');
        }

        // Gets event
        $event = $invitation->event;
        $eventId = $event->id;
        if (!$event) {
            $ses->addFlash('danger', 'Evento non trovato');
            return $this->redirect('/');
        } else if (!$event->community_id) {
            $ses->addFlash('danger', 'Community non trovata');
            return $this->redirect('/');
        }

        // Checks dates
        if (!is_null($event->eventType) && $event->eventType->event_type != EventType::TYPE_INFORMATIVE) {
            $from = $event->registration_date_begin ? strtotime($event->registration_date_begin) : 0;
            $to = $event->registration_date_end ? strtotime($event->registration_date_end) : PHP_INT_MAX;
            $now = time();
            if ($now < $from || $now > $to) {
                $ses->addFlash('danger', 'Iscrizioni chiuse');
                return $this->redirect('/');
            }
        }

        // Show partners form only for some event typea and only for main users (not partners)
        if ((!is_null($event->eventType) && ($event->eventType->partners)) && !$invitation->partner_of) {
            $partners = [
                $this->eventsModule->createModel('EventInvitationPartner'),
                $this->eventsModule->createModel('EventInvitationPartner'),
                $this->eventsModule->createModel('EventInvitationPartner'),
                $this->eventsModule->createModel('EventInvitationPartner'),
                $this->eventsModule->createModel('EventInvitationPartner')
            ];
            $register = (Model::loadMultiple($partners, Yii::$app->request->post()) && Model::validateMultiple($partners));
        } else {
            $partners = false;
            $register = true;
        }

        // It's all right, register user and join community
        if ($register) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                /** @var User|null $user */
                $user = null;
                $userProfile = null;
                if ($invitation->type == EventInvitation::INVITATION_TYPE_IMPORTED) {
                    $userNew = AmosAdmin::getInstance()->createNewAccount(
                        $invitation->name, $invitation->surname, $invitation->email, 1
                    );
                    if (isset($userNew['user']) && !is_null($userNew['user'])) {
                        $user = $userNew['user'];
                        $userId = $user->id;
                        $userProfile = $user->getProfile();
                    } else {
                        if (isset($userNew['error']) && ($userNew['error'] == UserProfileUtility::UNABLE_TO_CREATE_USER_ERROR)) {
                            $ses->addFlash('info',
                                AmosEvents::t('amosevents', '{username} Procedere con il login',
                                    ['username' => $userNew['messages']['email'][0]]));

                            $user = User::findOne(['email' => $invitation->email]);
                            $userId = $user->id;
                            $userProfile = $user->getProfile();
                            $userregister = true;
                        } else {
                            $transaction->rollBack();
                            $ses->addFlash('danger',
                                AmosEvents::t('amosevents', 'Qualcosa è andato storto nella creazione dell\'utente'));
                            return $this->redirect('/');
                        }
                    }
                } else {
                    // Gets user information
                    $user = User::findOne($invitation->user_id);
                    $userId = $user->id;
                    $userProfile = UserProfile::findOne(['user_id' => $user->id]);
                }
                if (!is_null($userProfile)) {
                    $userProfile->first_access_redirect_url = $event->getFullViewUrl();
                    $userProfile->save(false);
                }
                $community_memeber_status = CommunityUserMm::STATUS_ACTIVE;
                if (!is_null($event->eventType) && $event->eventType->limited_seats) {
                    $number_seats = EventsUtility::cmpSeatsAvailable($event);
                    if ($number_seats < $this->min_seats_event) {
                        $community_memeber_status = CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER;

                        //send email for status waiting
                        $this->doSendMailWaiting($event);
                        $ses->addFlash('danger', 'Il numero massimo di posti disponibili è stato superato');
                        return $this->redirect('/');
                    }
                }
                $communityModule = AmosCommunity::getInstance();
                if (!is_null($communityModule)) {
                    $communityModule->createCommunityUser($event->community_id, $community_memeber_status,
                        Event::EVENT_PARTICIPANT, $userId, $invitation->invitation_sent_on,
                        new \yii\db\Expression('now()'), $invitation->partner_of);
                }

                // Sets invitation as accepted
                $invitation->user_id = $userId;
                $invitation->state = EventInvitation::INVITATION_STATE_ACCEPTED;
                $invitation->invitation_response_on = new \yii\db\Expression('now()');
                $invitation->save();
                // Creates partners if necesssary
                if ($partners) {
                    // Some common stuff
                    $from = $this->getFromMail($event);
                    foreach ($partners as $partner) {
                        if ($partner['email']) {
                            // Creates a new invitation
                            /** @var EventInvitation $partnerInvitation */
                            $partnerInvitation = $this->eventsModule->createModel('EventInvitation');
                            $partnerInvitation->event_id = $eventId;
                            $partnerInvitation->partner_of = $userId;
                            $partnerInvitation->code = EventInvitation::uuid4();
                            $partnerInvitation->type = EventInvitation::INVITATION_TYPE_IMPORTED;
                            $partnerInvitation->state = EventInvitation::INVITATION_STATE_INVITED;
                            $partnerInvitation->email = $partner['email'];
                            $partnerInvitation->fiscal_code = $partner['fiscal_code'];
                            $partnerInvitation->name = $partner['name'];
                            $partnerInvitation->surname = $partner['surname'];
                            $partnerInvitation->invitation_sent_on = new \yii\db\Expression('now()');
                            $partnerInvitation->save();
                            // Sends e-mail
                            $urlYes = Url::base(true) . Url::toRoute(['subscribe-and-register',
                                    'id' => $eventId, 'code' => $partnerInvitation->code]);
                            $urlNo = Url::base(true) . Url::toRoute(['reject', 'id' => $eventId,
                                    'code' => $partnerInvitation->code]);
                            $text = $this->renderPartial('email_partner_invitation',
                                [
                                    'event' => $event,
                                    'user' => $user,
                                    'profile' => $userProfile,
                                    'partner' => $partner,
                                    'urlYes' => $urlYes,
                                    'urlNo' => $urlNo,
                                ]);
                            Email::sendMail($from, [$partner['email']], 'Invito - ' . $event->title, $text, [], [], [], 0,
                                false);
                        }
                    }
                }

                if (!$userregister) {
                    $ses->addFlash('success', AmosEvents::t('amosevents', 'Registrazione avvenuta con successo!'));
                    if ($invitation->type == EventInvitation::INVITATION_TYPE_IMPORTED) {
                        $user->generatePasswordResetToken();
                        $user->save(false);
                        $view_url = ('/admin/security/insert-auth-data?token=' . $user->password_reset_token);
                    } else {
                        $view_url = ('/');
                    }
                } else {
                    $view_url = ('/');
                }
                $transaction->commit();
                return $this->redirect($view_url);
            } catch (\Exception $e) {
                $transaction->rollBack();
                $ses->addFlash('danger', AmosEvents::t('amosevents', 'Qualcosa è andato storto') . ': ' . $e->getMessage());
                return $this->redirect('/');
            } catch (\Throwable $e) {
                $transaction->rollBack();
                $ses->addFlash('danger', AmosEvents::t('amosevents', 'Qualcosa è andato storto') . ': ' . $e->getMessage());
                return $this->redirect('/');
            }
        }

        return $this->render('event_invitation_confirm',
            [
                'event' => $event,
                'invitation' => $invitation,
                'partners' => $partners,
            ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionSubscribe()
    {
        $eventId = Yii::$app->request->get('eventId');
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');
        $event = $eventModel::findOne($eventId);

        /** @var User $user */
        $user = Yii::$app->user->identity;
        $userId = $user->id;
        $communityId = $event->community_id;

        $defaultAction = ['view', 'id' => $eventId];

        if (!$communityId) {
            Yii::$app->getSession()->addFlash('danger',
                AmosEvents::tHtml('amosevents', "It is not possible to subscribe the user. Missing parameter community."));
            return $this->redirect($defaultAction);
        }

        /////////////////////////////////////////////////////
        // User joins Community
        $communityUserMm = new CommunityUserMm();
        $communityUserMm->community_id = $communityId;
        $communityUserMm->user_id = $userId;
        $communityUserMm->status = CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER;
        $communityUserMm->role = Event::EVENT_PARTICIPANT;
        if ($communityUserMm->save()) {
            Yii::$app->getSession()->addFlash('success',
                AmosEvents::t('amosevents', 'Your request has been forwarded to event manager for approval.'));
        } else {
            Yii::$app->getSession()->addFlash('danger',
                AmosEvents::t('amosevents',
                    'An error occurred. Your request has NOT been forwarded to event manager for approval'));
        }

        /////////////////////////////////////////////////////
        // Send email to event manager
        // Default email values
        $from = $this->getFromMail($event);
        $to = $communityUserMm->getCommunityManagerMailList($event->community_id);
        /** @var UserProfile $userProfile */
        $userProfile = $user->getProfile();
        // Populate SUBJECT
        $subject = AmosEvents::t('amosevents', 'User') . ' ' . $userProfile->getNomeCognome() . ' ' . AmosEvents::t('amosevents',
                'asked to join the event') . ' ' . $event->title;

        // Populate TEXT
        $text = $subject;
        $text .= AmosEvents::t('amosevents', 'Type') . ': ' . !is_null($event->eventType) ? $event->eventType->title
            : '-' . '<br>';
        $text .= AmosEvents::t('amosevents', 'Title') . ': ' . $event->title . '<br>';
        $text .= AmosEvents::t('amosevents', 'Summary') . ': ' . $event->summary . '<br>';
        $text .= AmosEvents::t('amosevents', 'Published by') . ': ' . UserProfile::findOne(['user_id' => $event->created_by])->getNomeCognome();
        $createUrlParams = [
            '/events/event/view',
            'id' => $eventId
        ];
        $url = Yii::$app->urlManager->createAbsoluteUrl($createUrlParams) . '#tab-participants';
        $text .= Html::a(AmosEvents::t('amosevents', "Sign into the event to accept or reject the request."),
            $url);

        $files = [];
        $bcc[] = $user->email;
        $params = null;
        $priority = 0;
        $use_queue = false;

        // SEND EMAIL
        Email::sendMail(
            $from, $to, $subject, $text, $files, $bcc, $params, $priority, $use_queue
        );

        return $this->redirect($defaultAction);
    }

    /**
     * This method subscribes an user to an event. It means an user subscribe another one to an event.
     * It's not the logged user that subscribe himself to an event, like normal procedure.
     * @return \yii\web\Response
     */
    public function actionSubscribeUserToEvent()
    {
        /** @var User $loggedUser */
        $loggedUser = Yii::$app->user->identity;
        $eventId = Yii::$app->request->get('eventId');
        $userId = Yii::$app->request->get('userId');
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');
        $event = $eventModel::findOne($eventId);
        $user = User::findOne($userId);
        $userProfile = $user->userProfile;
        $communityId = $event->community_id;

        $defaultAction = [
            '/events/event/associate-user-to-event-m2m',
            'id' => $userProfile->id,
            'viewM2MWidgetGenericSearch' => true
        ];

        if (!$communityId) {
            Yii::$app->getSession()->addFlash('danger',
                AmosEvents::tHtml('amosevents', "It is not possible to subscribe the user. Missing parameter community."));
            return $this->redirect($defaultAction);
        }

        /** @var AmosCommunity $communityModule */
        $communityModule = Yii::$app->getModule('community');
        if (is_null($communityModule)) {
            Yii::$app->getSession()->addFlash('danger', AmosEvents::tHtml('amosevents', '#missing_community_module'));
            return $this->redirect($defaultAction);
        }

        /////////////////////////////////////////////////////
        // User joins Community
        $communityUserMm = new CommunityUserMm();
        $communityUserMm->community_id = $communityId;
        $communityUserMm->user_id = $userId;

        $status = ($this->eventsModule->forceEventSubscription == true)
            ? CommunityUserMm::STATUS_ACTIVE
            : CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER;

        $communityUserMm->status = $status;
        $communityUserMm->role = Event::EVENT_PARTICIPANT;

        $ok = $communityUserMm->save();
        if ($ok) {
            $event->community->setCwhAuthAssignments($communityUserMm);
        } else {
            Yii::$app->getSession()->addFlash('danger',
                AmosEvents::t('amosevents',
                    'An error occurred. Your request has NOT been forwarded to event manager for approval'));
            return $this->redirect($defaultAction);
        }

        /////////////////////////////////////////////////////
        // Send email to event manager
        // Default email values
        $from = $this->getFromMail($event);
        $to = [$user->email];

        // Email subject
        $subjectMessage = ($this->eventsModule->forceEventSubscription == true)
            ? '#user_forced_user_to_event_email_subject'
            : '#user_invite_user_to_event_email_subject';

        $subject = AmosEvents::t('amosevents', $subjectMessage, [
            'nameSurname' => $loggedUser->userProfile->nomeCognome,
            'eventTitle' => $event->title
        ]);

        // Email text
        $text = $subject . '<br><br>';
        $text .= (!is_null($event->eventType) ? AmosEvents::t('amosevents', 'Type') . ': ' . $event->eventType->title . '<br>'
            : '-' . '<br>');
        $text .= AmosEvents::t('amosevents', 'Title') . ': ' . $event->title . '<br>';
        $text .= AmosEvents::t('amosevents', 'Summary') . ': ' . $event->summary . '<br>';
        $text .= $event->getAttributeLabel('begin_date_hour') . ': ' . Yii::$app->getFormatter()->asDatetime($event->begin_date_hour,
                'humanalwaysdatetime') . '<br>';
        $text .= ($event->end_date_hour ? $event->getAttributeLabel('end_date_hour') . ': ' . \Yii::$app->getFormatter()->asDatetime($event->end_date_hour) . '<br>'
            : '-' . '<br>');
        if (
            $event->event_location ||
            ($event->event_address && $event->event_address_house_number) ||
            ($event->event_address_cap && $event->cityLocation) ||
            $event->provinceLocation ||
            $event->countryLocation
        ) {
            $text .= AmosEvents::t('amosevents', 'Location') . ': ' .
                ($event->event_location ? $event->event_location . '<br>' : '') .
                ($event->event_address ? $event->event_address : '') .
                (($event->event_address_house_number && $event->event_address) ? ', ' . $event->event_address_house_number . '<br>'
                    : ($event->event_address_house_number ? $event->event_address_house_number . '<br>' : '')) .
                ($event->event_address_cap ? $event->event_address_cap : '') .
                ($event->cityLocation ? $event->cityLocation->nome . ' ' : '') .
                ($event->provinceLocation ? $event->provinceLocation->sigla : '') .
                ($event->event_address_cap || $event->cityLocation || $event->provinceLocation ? ' <br>' : '') .
                ($event->countryLocation ? $event->countryLocation->nome . ' ' : '') . '<br>';
        }

        $url = Yii::$app->urlManager->createAbsoluteUrl($event->getFullViewUrl()) . '#tab-participants';
        $urlLabel = ($this->eventsModule->forceEventSubscription == true)
            ? '#view_event_details'
            : '#sign_to_accept_or_reject';
        $text .= Html::a(AmosEvents::t('amosevents', $urlLabel), $url);

        // SEND EMAIL
        if (strpos($from, ' ') !== false) {
            $splitFrom = explode(' ', $from);
            $first = reset($splitFrom);
            $bcc = [$first];
        } else {
            $bcc = [$from];
        }
        $ok = Email::sendMail($from, $to, $subject, $text, [], $bcc, [], 0, false);
        if ($ok) {
            Yii::$app->getSession()->addFlash('success',
                AmosEvents::t('amosevents', 'Your request has been forwarded to event manager for approval.'));
        } else {
            Yii::$app->getSession()->addFlash('danger',
                AmosEvents::t('amosevents', 'An error occurred while sending notification email'));
        }

        return $this->redirect($defaultAction);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionAccept()
    {
        $eventId = Yii::$app->request->get('eventId');
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');
        $event = $eventModel::findOne($eventId);
        /** @var User $user */
        $user = User::findOne(Yii::$app->getUser()->id);
        $userId = $user->id;
        $communityId = $event->community_id;

        $defaultAction = ['view', 'id' => $eventId];

        if (!$communityId) {
            Yii::$app->getSession()->addFlash('danger',
                AmosEvents::tHtml('amosevents', "It is not possible to subscribe the user. Missing parameter community."));
            return $this->redirect($defaultAction);
        }

        /////////////////////////////////////////////////////
        // User joins Community
        $communityUserMm = new CommunityUserMm();
        $communityUserMm->community_id = $communityId;
        $communityUserMm->user_id = $userId;
        $communityUserMm->status = CommunityUserMm::STATUS_ACTIVE;
        $communityUserMm->role = Event::EVENT_PARTICIPANT;
        if ($communityUserMm->save()) {
            Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Thank you to join the event.'));
        } else {
            Yii::$app->getSession()->addFlash('danger',
                AmosEvents::t('amosevents', 'An error occurred. You DID NOT join this event.'));
        }

        /////////////////////////////////////////////////////
        // Send email to event manager
        // Default email values
        $from = $this->getFromMail($event);
        $to = $communityUserMm->getCommunityManagerMailList($event->community_id);
        /** @var UserProfile $userProfile */
        $userProfile = $user->getProfile();
        // Populate SUBJECT
        $subject = AmosEvents::t('amosevents', 'User') . ' ' . $userProfile->getNomeCognome() . ' ' . AmosEvents::t('amosevents',
                'accepted to join the event') . ' ' . $event->title;

        // Populate TEXT
        $text = $subject;
        $text .= AmosEvents::t('amosevents', 'Type') . ': ' . !is_null($event->eventType) ? $event->eventType->title
            : '-' . '<br>';
        $text .= AmosEvents::t('amosevents', 'Title') . ': ' . $event->title . '<br>';
        $text .= AmosEvents::t('amosevents', 'Summary') . ': ' . $event->summary . '<br>';
        $text .= AmosEvents::t('amosevents', 'Published by') . ': ' . UserProfile::findOne(['user_id' => $event->created_by])->getNomeCognome();
        $createUrlParams = [
            '/events/event/view',
            'id' => $eventId
        ];
        $url = Yii::$app->urlManager->createAbsoluteUrl($createUrlParams) . '#tab-participants';
        $text .= Html::a(AmosEvents::t('amosevents', "Sign into the event."), $url);

        $files = [];
        $bcc[] = $user->email;
        $params = null;
        $priority = 0;
        $use_queue = true;

        // SEND EMAIL
        Email::sendMail(
            $from, $to, $subject, $text, $files, $bcc, $params, $priority, $use_queue
        );

        return $this->redirect($defaultAction);
    }

    /**
     * Action useful to validate a single event directly. It makes a check on the presence of at least
     * once confirmed manager when there's a community related to the event.
     * @param int $id The event id.
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionValidate($id)
    {
        /** @var Event $event */
        $event = $this->findModel($id);

        $ok = EventsUtility::checkOneConfirmedManagerPresence($event);
        if (!$ok) {
            Yii::$app->getSession()->addFlash('danger',
                AmosEvents::t('amosevents',
                    'The event can not be published. There must be at least one confirmed manager.'));
            return $this->redirect(Url::previous());
        }

        $event->status = Event::EVENTS_WORKFLOW_STATUS_PUBLISHED;
        $event->validated_at_least_once = Event::BOOLEAN_FIELDS_VALUE_YES;
        $event->visible_in_the_calendar = Event::BOOLEAN_FIELDS_VALUE_YES;
        $ok = $event->save(false);
        if ($ok) {
            Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Event successfully published.'));
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Error while publishing event.'));
        }

        return $this->redirect(Url::previous());
    }

    /**
     * Action to reject the event by an event validator.
     * @param int $id
     * @param bool $visibleInCalendar
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionReject($id, $visibleInCalendar)
    {
        /** @var Event $event */
        $event = $this->findModel($id);
        $event->status = Event::EVENTS_WORKFLOW_STATUS_DRAFT;
        $event->visible_in_the_calendar = $visibleInCalendar;
        $ok = $event->save(false);
        if ($ok) {
            Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Event successfully rejected.'));
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Error while rejecting event.'));
        }

        return $this->redirect(Url::previous());
    }

    /**
     * Sends invitations without registering users in the invitations table
     *
     * @param int $id Event id
     * @param array $rows Parsed rows
     * @param bool $registerSendDatetime
     * @return array Number of rows inserted
     */
    public function doSendInvitations($eid, array $rows, $registerSendDatetime = false)
    {
        $cnt = 0;
        $errs = '';
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');
        $event = $eventModel::findOne(['id' => $eid]);

        foreach ($rows as $r => $row) {
            try {
                // Sets sender
                $from = $this->getFromMail($event);

                EventMailUtility::setLayoutMail($event->email_ticket_layout_custom);
                if ($this->eventsModule->enableAutoInviteUsers && ($row['type'] == EventInvitation::INVITATION_TYPE_REGISTERED)) {
                    $user = User::findOne($row['user_id']);
                    $profile = $user->userProfile;
                    // Build url signup with user's data
                    $extUrlYes = Url::base(true) . Url::toRoute(['event-signup', 'eid' => $event->id, 'pName' => $row['name'],
                            'pSurname' => $row['surname'], 'pEmail' => $row['email'], 'pCode' => $row['code']]);
                    $regUrlNo = Url::base(true) . Url::toRoute(['reject', 'id' => $event->id]);
                    $row['email'] = $user['email'];
                    $viewInvitation = 'email_invitation_registered';
                    if (!empty($event->email_invitation_custom)) {
                        $viewInvitation = $event->email_invitation_custom;
                    }
                    $text = $this->renderPartial($viewInvitation,
                        [
                            'event' => $event,
                            'user' => $user,
                            'profile' => $profile,
                            'urlYes' => $extUrlYes,
                            'urlNo' => $regUrlNo,
                        ]);
                } else {
                    $viewInvitation = 'email_invitation';
                    if (!empty($event->email_invitation_custom)) {
                        $viewInvitation = $event->email_invitation_custom;
                    }
                    $extUrlYes = Url::base(true) . Url::toRoute(['event-signup', 'eid' => $event->id, 'pName' => $row['name'],
                            'pSurname' => $row['surname'], 'pEmail' => $row['email']]);
                    $text = $this->renderPartial($viewInvitation,
                        [
                            'event' => $event,
                            'user' => $row,
                            'urlYes' => $extUrlYes
                        ]);
                }
                // Sends e-mail
                $ok = Email::sendMail($from, [$row['email']], 'Invito - ' . html_entity_decode($event->title), $text, [], [], [], 0, true);
                if ($registerSendDatetime && $ok) {
                    // Marks invitation as sent
                    /** @var EventInvitation $eventInvitationModel */
                    $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');
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
     * UNUSED - old import invitations function based on event_invitation table
     * Puts parsed rows into the database
     *
     * @param int $id Event id
     * @param array $rows Parsed rows
     * @return array Number of rows inserted
     */
    private function doImportInvitations($eid, array $rows)
    {
        $cnt = 0;
        $errs = '';

        foreach ($rows as $r => $row) {
            try {
                /** @var EventInvitation $inv */
                $inv = $this->eventsModule->createModel('EventInvitation');
                $inv->event_id = $eid;
                $inv->code = EventInvitation::uuid4();
                $inv->type = EventInvitation::INVITATION_TYPE_IMPORTED;
                $inv->state = EventInvitation::INVITATION_STATE_INVITED;
                $inv->email = $row['email'];
                $inv->fiscal_code = trim($row['fiscal_code']);
                $inv->name = trim($row['name']);
                $inv->surname = trim($row['surname']);
                $inv->save();
                ++$cnt;
            } catch (\Exception $e) {
                $errs .= (strlen($errs) > 0 ? '<br>' : '') . $e->getMessage();
                $errs .= '<br>' . 'Errore in fase di importazione della riga ' . (1 + $r) . ' : codice fiscale già presente ';
            }
        }

        return ['cnt' => $cnt, 'errs' => $errs];
    }

    /**
     * Imports invitations from an excel workbook
     *
     * @param int $id Event id
     * @return array|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionImportInvitations($id)
    {
        /** @var EventInvitationsUpload $upload */
        $upload = $this->eventsModule->createModel('EventInvitationsUpload');
        if (Yii::$app->request->post() && $upload->load(Yii::$app->request->post())) {
            $rows = $upload->parse();
            if ($rows === false) {
                return $this->asJson(['success' => false, 'message' => 'Qualcosa è andato storto']);
            } else if (is_string($rows)) {
                return $this->asJson(['success' => false, 'message' => 'Qualcosa è andato storto: ' . $rows]);
            } else if (!$rows) {
                return $this->asJson(['success' => false, 'message' => 'Nessun invito da importare']);
            } else {
                Yii::$app->params['inviteEventExternalUsers'] = true; // IFL-487
                $rsp = EventsUtility::doSendInvitations($id, $rows); // Send invitations only
                $placeholder = (($rsp['cnt'] > 0) ? '#invitations_sent' : '#no_invitations_sent');
                $msg = AmosEvents::t('amosevents', $placeholder, $rsp);
                if (isset($rsp['errs'])) {
                    $msg .= '<p>' . $rsp['errs'];
                }
                return $this->asJson(['success' => true, 'message' => $msg]);
            }
        }
        return ['success' => false, 'message' => 'Azione non permessa'];
    }

    /**
     * @param Event $event
     * @throws \yii\base\InvalidConfigException
     */
    public function doAddUsersInvitations($event)
    {
        if (!is_null($event->eventType) && $event->eventType->event_type == EventType::TYPE_UPON_INVITATION) {
            // Gets user ids involved using cwh tags for this event
            $uids = $event->getCwhUserIdsToInvite();
            /** @var EventInvitation $eventInvitationModel */
            $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');
            // Puts the in invitation table
            foreach ($uids as $uid) {
                /** @var EventInvitation $evtInv */
                $evtInv = $this->eventsModule->createModel('EventInvitation');
                $evtInv->event_id = $event->id;
                $evtInv->type = EventInvitation::INVITATION_TYPE_REGISTERED;
                $evtInv->state = EventInvitation::INVITATION_STATE_INVITED;
                $evtInv->user_id = $uid;
                $evtInv->code = $eventInvitationModel::uuid4();
                $evtInv->save();
            }
        }
    }

    /**
     * @param Event $event
     */
    public function doSendMailWaiting($event)
    {
        // Sets sender
        $from = $this->getFromMail($event);

        $user = User::findOne($event->created_by);
        $profile = UserProfile::findOne(['user_id' => $event->created_by]);
        $text = $this->renderPartial('email_waiting',
            [
                'event' => $event,
                'user' => $user,
                'profile' => $profile
            ]);
        // Sends e-mail
        Email::sendMail($from, [$user->email], 'Invito - ' . $event->title, $text, [], [], [], 0, false);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionEventCalendarWidget()
    {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            if (!is_null($_POST['id'])) {
                /** @var Event $eventModel */
                $eventModel = $this->eventsModule->createModel('Event');
                $event = $eventModel::findOne($_POST['id']);
                return $this->renderAjax('eventCalendarWidget', ['model' => $event]);
            }
        }
        return '';
    }

    /**
     * Aggiunge un partecipante all'evento (crea un invito)
     * @param int $eid
     * @param array $dataParticipant
     * @param int $user_id
     * @param array $gdpr
     * @param EventInvitation|null $inv
     * @return EventInvitation
     * @throws \yii\base\InvalidConfigException
     */
    public function addParticipant($eid, $dataParticipant, $user_id, $gdpr, $inv = null, $isGroup = false)
    {
        if (is_null($inv)) {
            /** @var EventInvitation $eventInvitationModel */
            $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');
            /** @var EventInvitation $inv */
            $inv = $this->eventsModule->createModel('EventInvitation');
            $inv->event_id = $eid;
            $inv->code = $eventInvitationModel::uuid4();
            $inv->type = EventInvitation::INVITATION_TYPE_REGISTERED_BY_PUBLIC_FORM;
            $inv->user_id = $user_id;
            $inv->created_by = (!empty(\Yii::$app->user) && !empty(\Yii::$app->user->id)) ? \Yii::$app->user->id : $user_id;
            $inv->updated_by = (!empty(\Yii::$app->user) && !empty(\Yii::$app->user->id)) ? \Yii::$app->user->id : $user_id;
        }
        $inv->state = EventInvitation::INVITATION_STATE_ACCEPTED;
        $inv->email = trim($dataParticipant['email']);
        if (array_key_exists('codice_fiscale', $dataParticipant)) {
            $inv->fiscal_code = trim($dataParticipant['codice_fiscale']);
        }
        $inv->name = trim($dataParticipant['nome']);
        $inv->surname = trim($dataParticipant['cognome']);
        $inv->company = trim($dataParticipant['azienda']);
        $inv->notes = trim($dataParticipant['note']);

        if ($this->eventsModule->enableGdpr) {
            $inv->gdpr_answer_1 = array_key_exists('0', $gdpr) ? $gdpr['0'] : 0;
            $inv->gdpr_answer_2 = array_key_exists('1', $gdpr) ? $gdpr['1'] : 0;
            $inv->gdpr_answer_3 = array_key_exists('2', $gdpr) ? $gdpr['2'] : 0;
            $inv->gdpr_answer_4 = array_key_exists('3', $gdpr) ? $gdpr['3'] : 0;
            $inv->gdpr_answer_5 = array_key_exists('4', $gdpr) ? $gdpr['4'] : 0;
        }

        if ($isGroup) {
            $inv->is_group = true;
        }

        /** @var EventAccreditationList $eventAccreditationListModel */
        $eventAccreditationListModel = $this->eventsModule->createModel('EventAccreditationList');
        $accreditationLists = $eventAccreditationListModel::findAll(['event_id' => $eid]);
        if (count($accreditationLists) == 1) {
            $inv->accreditation_list_id = $accreditationLists[0]->id;
        }

        $inv->save();

        return $inv;
    }

    /**
     * Aggiunge un accompagnatore all'evento (viene associato il companion al partecipante/invitation)
     *
     * @param integer $eid
     * @param EventInvitation $participant
     * @param array $dataCompanion
     */
    public function addCompanion($eid, $participant, $dataCompanion)
    {
        // Verifico se è già iscritto alla piattaforma un utente con la mail indicata
        $user = User::findOne(['email' => $dataCompanion['email']]);
        if (!$user) {
            $user = User::findOne(['username' => $dataCompanion['email']]);
        }

        /** @var EventParticipantCompanion $companion */
        $companion = $this->eventsModule->createModel('EventParticipantCompanion');
        $companion->nome = $dataCompanion['nome'];
        $companion->cognome = $dataCompanion['cognome'];
        $companion->email = $dataCompanion['email'];
        if (array_key_exists('codice_fiscale', $dataCompanion)) {
            $companion->codice_fiscale = $dataCompanion['codice_fiscale'];
        }
        $companion->azienda = $dataCompanion['azienda'];
        $companion->note = $dataCompanion['note'];
        $companion->event_invitation_id = $participant->id;
        $companion->event_id = $eid;
        $companion->user_id = (!empty($user)) ? $user->id : null;

        $companion->created_by = (!empty(\Yii::$app->user) && !empty(\Yii::$app->user->id)) ? \Yii::$app->user->id : $participant->user_id;
        $companion->updated_by = (!empty(\Yii::$app->user) && !empty(\Yii::$app->user->id)) ? \Yii::$app->user->id : $participant->user_id;

        /** @var EventAccreditationList $eventAccreditationListModel */
        $eventAccreditationListModel = $this->eventsModule->createModel('EventAccreditationList');
        $accreditationLists = $eventAccreditationListModel::findAll(['event_id' => $eid]);
        if (count($accreditationLists) == 1) {
            $companion->event_accreditation_list_id = $accreditationLists[0]->id;
        }

        $companion->save();
        return $companion;
    }

    /**
     * @param int $eid
     * @param User $user
     * @param string $communityMemeberStatus
     * @return boolean
     */
    public function subscribeToEventCommunity($eid, $user, $communityMemeberStatus = '')
    {
        $eventId = $eid;
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');
        $event = $eventModel::findOne($eventId);

        $userId = $user->id;
        $communityId = $event->community_id;

        $defaultAction = ['view', 'id' => $eventId];

        if (!$communityId) {
            Yii::$app->getSession()->addFlash('danger',
                AmosEvents::tHtml('amosevents', "It is not possible to subscribe the user. Missing parameter community."));
            return false;
        }

        /////////////////////////////////////////////////////
        // User joins Community
        $communityUserMm = new CommunityUserMm();
        $communityUserMm->community_id = $communityId;
        $communityUserMm->user_id = $userId;
        $communityUserMm->created_by = $userId;
        $communityUserMm->updated_by = $userId;
        $communityUserMm->status = ((strlen($communityMemeberStatus) > 0) ? $communityMemeberStatus : CommunityUserMm::STATUS_ACTIVE);
        $communityUserMm->role = Event::EVENT_PARTICIPANT;

        if ($communityUserMm->save()) {
            Yii::$app->getSession()->addFlash('success',
                AmosEvents::t('amosevents', 'You have been registered to the event successfully.'));
        } else {
            Yii::$app->getSession()->addFlash('danger',
                AmosEvents::t('amosevents', 'There were some problems with your registration to the event'));
        }

        return true;
    }

    /**
     *
     * @param integer $eid
     * @return integer
     */
    public function checkParticipantsQuantity($eid)
    {
        $count = 0;

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');
        $participants = $eventInvitationModel::find()
            ->andWhere(['event_id' => $eid, 'state' => EventInvitation::INVITATION_STATE_ACCEPTED])
            ->andWhere(['deleted_at' => null, 'deleted_by' => null])
            ->asArray()
            ->all();
        $count = count($participants);

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');
        foreach ($participants as $participant) {
            $companions = $eventParticipantCompanionModel::find()
                ->andWhere(['event_invitation_id' => $participant['id']])
                ->andWhere(['deleted_at' => null, 'deleted_by' => null])
                ->asArray()
                ->all();
            $count += count($companions);
        }

        return $count;
    }

    /**
     * @param $eid
     * @param null $pName
     * @param null $pSurname
     * @param null $pEmail
     * @param bool $emptyFields
     * @param null $pCode
     * @param bool $isGroup
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionEventSignup($eid, $pName = null, $pSurname = null, $pEmail = null, $emptyFields = false,
                                      $pCode = null, $isGroup = false)
    {
        $this->setUpLayout('main');
        if (Yii::$app->user->isGuest) {
            Yii::$app->params['disablePlatformLinks'] = true;
            Yii::$app->params['disableHeaderMenu'] = true;
        }

        $ses = Yii::$app->getSession();
        $fwd = '/';

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $multipleRecording = $this->eventsModule->multipleRecording || (isset($this->eventsModule->params['multiple_event_signup'])
                && $this->eventsModule->params['multiple_event_signup']);

        $event = $eventModel::findOne(['id' => $eid]);


        if ($event) {

            $invitationUser = null;
            if ($multipleRecording == false) {
                if (!empty($pEmail)) {
                    $invitationUser = $eventInvitationModel::find()
                        ->andWhere(['event_id' => $eid])
                        ->andWhere(['email' => trim($pEmail)])
                        ->andFilterWhere(['name' => trim($pName)])
                        ->andFilterWhere(['surname' => trim($pSurname)])
                        ->one();
                }
            }

            // Controllo che le iscrizioni siano aperte (data inizio < della data odierna, data fine > della data odierna)
            if ($event->isSubscribtionsOpened()) {
                if ($event->eventType->event_type != EventType::TYPE_LIMITED_SEATS ||
                    ($event->eventType->event_type == EventType::TYPE_LIMITED_SEATS && ($event->checkParticipantsQuantity() < $event->seats_available))
                ) {

                    $gdprQuestions = $this->prepareArrayGdpr($event);

                    /** @var EventParticipantCompanion $eventParticipantModel */
                    $eventParticipantModel = $this->eventsModule->createModel('EventParticipantCompanion');
                    $eventParticipantModel->event_id = $eid;
                    /** @var EventParticipantCompanionDynamic $eventCompanionModel */
                    $eventCompanionModel = $this->eventsModule->createModel('EventParticipantCompanionDynamic');
                    $eventCompanionModel->event_id = $eid;

                    if (\Yii::$app->request->post()) {
                        $post = \Yii::$app->request->post();
                        $eventParticipantModel->load($post);
                        $participantData = $post['EventParticipantCompanion'];


                        // Controlla se qualcuno e' già stato iscritto all'evento con la stessa mail...
                        $invitationFound = $eventInvitationModel::findAll(['email' => $participantData['email'], 'event_id' => $eid]);

                        // ...altrimenti cerca utente associato a un invito per evitare l'iscrizione multipla
                        // cercandolo sia attraverso la sua mail...
                        if (count($invitationFound) == 0) {
                            $user = User::findOne(['email' => $participantData['email']]);
                            if ($user) {
                                $invitationFound = $eventInvitationModel::findAll(['user_id' => $user['id'], 'event_id' => $eid]);
                            }
                        }
                        // ...sia attraverso il suo nome utente
                        if (count($invitationFound) == 0) {
                            $user = User::findOne(['username' => $participantData['email']]);
                            if ($user) {
                                $invitationFound = $eventInvitationModel::findAll(['user_id' => $user['id'], 'event_id' => $eid]);
                            }
                        }

                        if (array_key_exists('EventParticipantCompanionDynamic', $post)) {
                            $companionsData = MultipleModel::createMultiple($this->eventsModule->createModel('EventParticipantCompanionDynamic'),
                                [], ['event_id' => $eid]);
                            MultipleModel::loadMultiple($companionsData, $post);
                        }

                        // Validating companion models
                        $modelValidations = true;
                        if (!empty($companionsData)) {
                            foreach ($companionsData as $companion) {
                                if ($modelValidations) {
                                    $modelValidations = $companion->validate();
                                }
                            }
                        }

                        if ($eventParticipantModel->validate() && $modelValidations) {
                            if ((count($invitationFound) == 0) || ($this->eventsModule->enableAutoInviteUsers && (count($invitationFound)
                                        > 0))) {

                                // Per controllare se risposte GDPR sono state marcate prima dell'invio dei dati
                                $gdprMarked = true;
                                if ($this->eventsModule->enableGdpr && $event->countGdprQuestions() > 0) {
                                    $gdprMarked = false;
                                    if (array_key_exists('gdprQuestion', $post)) {
                                        $gdprMarked = ($event->countGdprQuestions() == count($post['gdprQuestion']));
                                    }
                                }

                                if ($gdprMarked) {
                                    $companionsQuantity = 0;
                                    if (array_key_exists('EventParticipantCompanionDynamic', $post)) {
                                        $companionsQuantity = count($companionsData);
                                    }
                                    $participantsWantToJoin = $companionsQuantity + 1;

                                    if ($event->eventType->event_type == EventType::TYPE_LIMITED_SEATS &&
                                        $participantsWantToJoin > ($event->seats_available - $event->checkParticipantsQuantity())) {

                                        \Yii::$app->getSession()->addFlash('danger',
                                            AmosEvents::txt('#quantity_exceeded',
                                                ['quantity' => ($event->seats_available - $this->checkParticipantsQuantity($event->id))]));
                                        return $this->render((!empty($event->subscribe_form_page_view) ? $event->subscribe_form_page_view
                                            : 'event_signup'),
                                            [
                                                'event' => $event,
                                                'userData' => $participantData,
                                                'eventParticipantModel' => $eventParticipantModel,
                                                'eventCompanionModel' => $eventCompanionModel,
                                                'companions' => !empty($companionsData) ? $companionsData : [0 => $eventCompanionModel],
                                                'gdprQuestions' => $gdprQuestions,
                                                //'invitation' => $invitation,
                                                //'partners' => $partners,
                                            ]);
                                    } else {

                                        $user = User::findOne(['email' => $participantData['email']]);
                                        // Se utente non trovato tramite email, controllo anche tramite username (PR-336)
                                        if (!$user) {
                                            $user = User::findOne(['username' => $participantData['email']]);
                                        }
                                        // Se l'utente non e' ancora registrato, lo registro alla piattaforma
                                        if (!$user) {
                                            //$sendCredential = (($event->sent_credential == 1)? (!empty($event->email_credential_view)? 0 : 1) : 0);
                                            // Creo il nuovo account utente...
                                            $newUser = AmosAdmin::getInstance()->createNewAccount(
                                                $participantData['nome'], $participantData['cognome'],
                                                $participantData['email'], 0
                                            );
                                            $user = $newUser['user'];


                                            // ...e gli invio le credenziali
                                            /**
                                             * @var $newUserProfile UserProfile
                                             */
                                            $userId = $newUser['user']->id;
                                            //'email_ticket_layout_custom';
                                            $newUserProfile = UserProfile::findOne(['user_id' => $userId]);
                                            $newUserProfile->validato_almeno_una_volta = 1;
                                            $newUserProfile->save(false);

                                            if ($event->sent_credential == 1) {
                                                if (!empty($event->email_credential_view)) {
                                                    $sent = EventMailUtility::sendCredentialsMail($newUserProfile,
                                                        $event->email_credential_subject,
                                                        $event->email_credential_view, $event->email_ticket_sender,
                                                        $event->email_ticket_layout_custom,
                                                        $event->getCommunityModel());
                                                } else {
                                                    $sent = UserProfileUtility::sendCredentialsMail($newUserProfile);
                                                }
                                            }
                                        }

                                        $linkToken = null;
                                        if ($event->use_token == 1 && !empty($event->token_group_string_code)) {
                                            $linkToken = $event->getLinkWithToken($user->id,
                                                $event->token_group_string_code);
                                        }

                                        $gdprQuestions = [];
                                        if ($this->eventsModule->enableGdpr && array_key_exists('gdprQuestion', $post)) {
                                            $gdprQuestions = $post['gdprQuestion'];
                                        }

                                        $invitation = null;
                                        if ($this->eventsModule->enableAutoInviteUsers && (count($invitationFound) > 0)) {
                                            $invitation = $eventInvitationModel::findOne(['code' => $pCode]);
                                            if (!is_null($invitation)) {
                                                $invitation->state = EventInvitation::INVITATION_STATE_ACCEPTED;
                                                $invitation->invitation_response_on = new Expression('now()');
                                                $invitation->save(false);
                                            }
                                        }
                                        $participant = $this->addParticipant($eid, $post['EventParticipantCompanion'],
                                            $user->id, $gdprQuestions, $invitation);

                                        if ($event->thereAreAvailableSeats()) {
                                            $communityMemeberStatus = CommunityUserMm::STATUS_ACTIVE;
                                            $this->sendSignupConfirmEmail($event->id, $participant->id, $linkToken);

                                            if (array_key_exists('EventParticipantCompanionDynamic', $post)) {
                                                foreach ($post['EventParticipantCompanionDynamic'] as $companion) {
                                                    $this->addCompanion($eid, $participant, $companion);
                                                }
                                            }
                                            $thankYouPageToRender = 'event_signup_thankyou';
                                        } else {
                                            $communityMemeberStatus = CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER;
                                            // Send email for status waiting
                                            $this->doSendMailWaiting($event);
                                            $thankYouPageToRender = 'event_signup_thankyou_no_seats_available';
                                        }

                                        // Iscrivo utente alla community dell'evento
                                        $subscribedToEventCommunity = $this->subscribeToEventCommunity($eid, $user,
                                            $communityMemeberStatus);

                                        return $this->render((!empty($event->thank_you_page_view) ? $event->thank_you_page_view
                                            : $thankYouPageToRender),
                                            [
                                                'event' => $event,
                                                'linkToken' => $linkToken,
                                                'userData' => $participantData,
                                                'eventParticipantModel' => $eventParticipantModel,
                                                'eventCompanionModel' => $eventCompanionModel,
                                                'companions' => !empty($companionsData) ? $companionsData : [0 => $eventCompanionModel],
                                                'gdprQuestions' => $gdprQuestions,
                                            ]);
                                    }
                                } else {
                                    \Yii::$app->getSession()->addFlash('danger',
                                        AmosEvents::txt('Compilare tutte le domande relative alle condizioni e l\'uso dei dati personali'));
                                    return $this->render((!empty($event->subscribe_form_page_view) ? $event->subscribe_form_page_view
                                        : 'event_signup'),
                                        [
                                            'event' => $event,
                                            'userData' => $participantData,
                                            'eventParticipantModel' => $eventParticipantModel,
                                            'eventCompanionModel' => $eventCompanionModel,
                                            'companions' => !empty($companionsData) ? $companionsData : [0 => $eventCompanionModel],
                                            'gdprQuestions' => $gdprQuestions,
                                            //'invitation' => $invitation,
                                            //'partners' => $partners,
                                        ]);
                                }
                            } else {
                                \Yii::$app->getSession()->addFlash('danger',
                                    AmosEvents::txt('This user has already been registered at this event'));
                            }
                        } else {
                            \Yii::$app->getSession()->addFlash('danger',
                                AmosEvents::txt('Controllare riempimento dei campi obbligatori'));
                        }
                    } else {
                        if (\Yii::$app->user) {
                            $user = User::find()->andWhere(['id' => \Yii::$app->user->id])->andWhere(['deleted_at' => null,
                                'deleted_by' => null])->asArray()->one();
                            /** @var UserProfile $userProfile */
                            $userProfile = UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->andWhere([
                                'deleted_at' => null, 'deleted_by' => null])->asArray()->one();
                        }
                        $userData['nome'] = !$emptyFields ? (!empty($pName) ? $pName : ($userProfile ? $userProfile['nome']
                            : '')) : '';
                        $userData['cognome'] = !$emptyFields ? (!empty($pSurname) ? $pSurname : ($userProfile ? $userProfile['cognome']
                            : '')) : '';
                        $userData['email'] = !$emptyFields ? (!empty($pEmail) ? $pEmail : ($user ? $user['email']
                            : '')) : '';
                        $userData['codice_fiscale'] = $userProfile ? $userProfile['codice_fiscale'] : '';
                    }

                    if (($multipleRecording == false && empty($invitationUser)) || $multipleRecording == true) {
                        return $this->render((!empty($event->subscribe_form_page_view) ? $event->subscribe_form_page_view
                            : 'event_signup'),
                            [
                                'event' => $event,
                                'userData' => !empty($userData) ? $userData : $participantData,
                                'eventParticipantModel' => $eventParticipantModel,
                                'eventCompanionModel' => $eventCompanionModel,
                                'companions' => !empty($companionsData) ? $companionsData : [0 => $eventCompanionModel],
                                'gdprQuestions' => $gdprQuestions,
                                //'invitation' => $invitation,
                                //'partners' => $partners,
                            ]);
                    } else {
                        return $this->render((empty($event->thank_you_page_already_registered_view) ? 'already_registered'
                            : $event->thank_you_page_already_registered_view));
                    }
                } else {
                    return $this->render((!empty($event->event_full_page_view) ? $event->event_full_page_view : 'event_full'));
                }
            } else {
                return $this->render((!empty($event->event_closed_page_view) ? $event->event_closed_page_view : 'event_closed'));
            }
        }

        return $this->render('event_not_found');
    }

    /**
     * @param $eid
     * @param $pid
     * @param $iid
     * @param bool $booleanResponse
     * @return bool|string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRegisterParticipant($eid, $pid, $iid, $booleanResponse = false)
    {

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $event = $eventModel::find()->andWhere(['id' => $eid])->andWhere(['deleted_at' => null, 'deleted_by' => null])->asArray()->one();
        if ($event) {

            if ($event['has_tickets'] &&
                $event['begin_date_hour'] == null || (
                    (strtotime("now") >= strtotime($event['begin_date_hour']. ' - 6 hours')) && // per checkin X ore prima >> . ' - 6 hours')) &&
                    (date('Y-m-d H:i:s') <= date($event['end_date_hour']))
                )
            ) {
                /** @var User $user */
                $user = User::find()->andWhere(['id' => $pid])->andWhere(['deleted_at' => null, 'deleted_by' => null])->asArray()->one();

                $invitation = $eventInvitationModel::findOne(['id' => $iid]);
                if (!empty($pid) && !empty($user)) {

                    /** @var UserProfile $userProfile */
                    $userProfile = UserProfile::find()->andWhere(['user_id' => $user['id']])->andWhere(['deleted_at' => null,
                        'deleted_by' => null])->asArray()->one();

                    if ($invitation) {
                        if ($invitation->user_id == $pid && $invitation->event_id == $eid) {

                            if (!$invitation->presenza) {
                                $invitation->presenza = true;
                                $invitation->presenza_scansionata_il = date('Y-m-d H:i:s');
                                $invitation->save(false);
                                \open20\amos\core\models\UserActivityLog::registerLog(AmosEvents::t('amosevents', "Presente all'evento"), $invitation, Event::LOG_TYPE_ATTENDANT_TO_EVENT, null, $invitation->user_id);


                                if ($booleanResponse) {
                                    return true;
                                }

                                if ($event['seats_management']) {
                                    /** @var  $seat EventSeats */
                                    $seat = $invitation->getAssignedSeat();
                                    if ($seat && $seat->status == EventSeats::STATUS_REASSIGNED) {
                                        \Yii::$app->getSession()->addFlash('warning',
                                            AmosEvents::txt('Attenzione! Il  posto <strong>{seat}</strong> è stato riassegnato. Reindirizzare al desk accrediti!',
                                                [
                                                    'nomeCognome' => $userProfile['nome'] . ' ' . $userProfile['cognome'],
                                                    'seat' => $seat->getStringCoordinateSeat()
                                                ]));
                                    }
                                }
                                \Yii::$app->getSession()->addFlash('success',
                                    AmosEvents::txt('#user_registered',
                                        ['name_surname' => $userProfile['nome'] . ' ' . $userProfile['cognome']]));
                            } else {
                                \Yii::$app->getSession()->addFlash('danger',
                                    AmosEvents::txt('Already marked as attendant',
                                        ['name_surname' => $userProfile['nome'] . ' ' . $userProfile['cognome']]));
                            }
                        } else {
                            if ($booleanResponse) {
                                return false;
                            }
                            \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#invitation_invalid'));
                        }
                    } else {
                        if ($booleanResponse) {
                            return false;
                        }
                        \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#invitation_not_found'));
                    }
                } else if (!empty($invitation)) {
                    if ($invitation->event_id == $eid) {

                        if (!$invitation->presenza) {
                            $invitation->presenza = true;
                            $invitation->presenza_scansionata_il = date('Y-m-d H:i:s');
                            $invitation->save(false);
                            \open20\amos\core\models\UserActivityLog::registerLog(AmosEvents::t('amosevents', "Presente all'evento"), $invitation, Event::LOG_TYPE_ATTENDANT_TO_EVENT, null, $invitation->user_id);

                            if ($booleanResponse) {
                                return true;
                            }

                            if ($event['seats_management']) {
                                /** @var  $seat EventSeats */
                                $seat = $invitation->getAssignedSeat();
                                if ($seat && $seat->status == EventSeats::STATUS_REASSIGNED) {
                                    \Yii::$app->getSession()->addFlash('warning',
                                        AmosEvents::txt('Attenzione! Il  posto <strong>{seat}</strong> è stato riassegnato. Reindirizzare al desk accrediti!',
                                            [
                                                'nomeCognome' => ' ',
                                                'seat' => $seat->getStringCoordinateSeat()
                                            ]));
                                }
                            }
                            \Yii::$app->getSession()->addFlash('success',
                                AmosEvents::txt('#user_registered', ['name_surname' => '']));
                        } else {
                            \Yii::$app->getSession()->addFlash('danger',
                                AmosEvents::txt('Already marked as attendant', ['name_surname' => '']));
                        }
                    }
                } else {
                    if ($booleanResponse) {
                        return false;
                    }
                    \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#user_not_found'));
                }
            } else {
                if ($booleanResponse) {
                    return false;
                }
                \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('Attendance registration closed'));
            }

            return $this->redirect('/events/event/view?id=' . $eid . '#tab-participants');
        }

        return AmosEvents::txt('Event not found');
    }

    /**
     * @param $eid
     * @param $pid
     * @param $iid
     * @param bool $booleanResponse
     * @return bool|string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRemoveParticipantAttendance($eid, $pid, $iid, $booleanResponse = false)
    {

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $event = $eventModel::find()->andWhere(['id' => $eid])->andWhere(['deleted_at' => null, 'deleted_by' => null])->asArray()->one();

        if ($event) {

            if ($event['has_tickets'] &&
                $event['begin_date_hour'] == null || (
                    (strtotime("now") >= strtotime($event['begin_date_hour']. ' - 6 hours')) && // per checkin X ore prima >> . ' - 6 hours')) &&
                    (date('Y-m-d H:i:s') <= date($event['end_date_hour']))
                )
            ) {

                $user = User::find()->andWhere(['id' => $pid])->andWhere(['deleted_at' => null, 'deleted_by' => null])->asArray()->one();

                if ($user) {

                    $invitation = $eventInvitationModel::findOne(['id' => $iid]);

                    if ($invitation) {
                        if ($invitation->user_id == $pid && $invitation->event_id == $eid) {

                            $invitation->presenza = false;
                            $invitation->presenza_scansionata_il = null;
                            $invitation->save(false);
                            \open20\amos\core\models\UserActivityLog::registerLog(AmosEvents::t('amosevents', "Presenza all'evento rimossa"), $invitation, Event::LOG_TYPE_ATTENDANT_TO_EVENT, null, $invitation->user_id);


                            if ($booleanResponse) {
                                return true;
                            }
                            \Yii::$app->getSession()->addFlash('success', AmosEvents::txt('#user_registered'));
                        } else {
                            if ($booleanResponse) {
                                return false;
                            }
                            \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#invitation_invalid'));
                        }
                    } else {
                        if ($booleanResponse) {
                            return false;
                        }
                        \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#invitation_not_found'));
                    }
                } else {
                    if ($booleanResponse) {
                        return false;
                    }
                    \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#user_not_found'));
                }
            } else {
                if ($booleanResponse) {
                    return false;
                }
                \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('Attendance registration closed'));
            }

            return $this->redirect('/events/event/view?id=' . $eid . '#tab-participants');
        }

        return AmosEvents::txt('Event not found');
    }

    /**
     * @param $eid
     * @param $pid
     * @param $cid
     * @param $iid
     * @param bool $booleanResponse
     * @return bool|string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRegisterCompanion($eid, $pid, $cid, $iid, $booleanResponse = false)
    {

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        $event = $eventModel::find()->andWhere(['id' => $eid])->andWhere(['deleted_at' => null, 'deleted_by' => null])->asArray()->one();

        if ($event) {
            if ($event['has_tickets'] &&
                $event['begin_date_hour'] == null || (
                    (strtotime("now") >= strtotime($event['begin_date_hour']. ' - 6 hours')) && // per checkin X ore prima >> . ' - 6 hours')) &&
                    (date('Y-m-d H:i:s') <= date($event['end_date_hour']))
                )
            ) {

                $companion = $eventParticipantCompanionModel::findOne(['id' => $cid]);
                if ($companion) {
                    $user = User::find()->andWhere(['id' => $pid])->andWhere(['deleted_at' => null, 'deleted_by' => null])->asArray()->one();

                    if ($user) {
                        $invitation = $eventInvitationModel::find()->andWhere(['id' => $iid])->andWhere(['deleted_at' => null,
                            'deleted_by' => null])->asArray()->one();
                        if ($invitation) {
                            if ($invitation['user_id'] == $pid && $invitation['event_id'] == $eid && $companion->event_invitation_id
                                == $iid) {
                                if (!$companion->presenza) {
                                    $companion->presenza = true;
                                    $companion->presenza_scansionata_il = date('Y-m-d H:i:s');
                                    $companion->save(false);
                                    \open20\amos\core\models\UserActivityLog::registerLog(AmosEvents::t('amosevents', "Accompagnatore presente all'evento"), $companion, Event::LOG_TYPE_ATTENDANT_TO_EVENT, null, $invitation->user_id);


                                    if ($booleanResponse) {
                                        return true;
                                    }
                                    if ($event['seats_management']) {
                                        /** @var  $seat EventSeats */
                                        $seat = $companion->assignedSeat;
                                        if (!empty($seat) && $seat->status == EventSeats::STATUS_REASSIGNED) {
                                            \Yii::$app->getSession()->addFlash('warning',
                                                AmosEvents::txt('Attenzione! Il  posto <strong>{seat}</strong> è stato riassegnato. Reindirizzare al desk accrediti!',
                                                    [
                                                        'seat' => $seat->getStringCoordinateSeat()
                                                    ]));
                                        }
                                    }


                                    \Yii::$app->getSession()->addFlash('success',
                                        AmosEvents::txt('#companion_registered',
                                            ['name_surname' => $companion->nome . ' ' . $companion->cognome]));
                                } else {
                                    \Yii::$app->getSession()->addFlash('danger',
                                        AmosEvents::txt('Already marked as attendant',
                                            ['name_surname' => $companion['nome'] . ' ' . $companion['cognome']]));
                                }
                            } else {
                                if ($booleanResponse) {
                                    return false;
                                }
                                \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#invitation_invalid'));
                            }
                        } else {
                            if ($booleanResponse) {
                                return false;
                            }
                            \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#invitation_not_found'));
                        }
                    } else {
                        if ($booleanResponse) {
                            return false;
                        }
                        \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#user_not_found'));
                    }
                } else {
                    if ($booleanResponse) {
                        return false;
                    }
                    \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#companion_not_found'));
                }
            } else {
                if ($booleanResponse) {
                    return false;
                }
                \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('Attendance registration closed'));
            }


            return $this->redirect('/events/event/view?id=' . $eid . '#tab-participants');
        }

        return AmosEvents::txt('Event not found');
    }

    /**
     * @param $eid
     * @param $pid
     * @param $cid
     * @param $iid
     * @param bool $booleanResponse
     * @return bool|string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRemoveCompanionAttendance($eid, $pid, $cid, $iid, $booleanResponse = false)
    {

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        $event = $eventModel::find()->andWhere(['id' => $eid])->andWhere(['deleted_at' => null, 'deleted_by' => null])->asArray()->one();

        if ($event) {
            if ($event['has_tickets'] && $event['begin_date_hour'] == null || (
                    (strtotime("now") >= strtotime($event['begin_date_hour']. ' - 6 hours')) && // per checkin X ore prima >> . ' - 6 hours')) &&
                    (date('Y-m-d H:i:s') <= date($event['end_date_hour']))
                )
            ) {

                $companion = $eventParticipantCompanionModel::findOne(['id' => $cid]);
                if ($companion) {
                    $user = User::find()
                        ->andWhere(['id' => $pid])
                        ->andWhere(['deleted_at' => null, 'deleted_by' => null])
                        ->asArray()
                        ->one();

                    if ($user) {
                        $invitation = $eventInvitationModel::find()->andWhere(['id' => $iid])->andWhere(['deleted_at' => null,
                            'deleted_by' => null])->asArray()->one();
                        if ($invitation) {
                            if ($invitation['user_id'] == $pid && $invitation['event_id'] == $eid && $companion->event_invitation_id
                                == $iid) {

                                $companion->presenza = false;
                                $companion->presenza_scansionata_il = null;
                                $companion->save(false);
                                \open20\amos\core\models\UserActivityLog::registerLog(AmosEvents::t('amosevents', "Presenza accompagnatore all'evento rimossa"), $companion, Event::LOG_TYPE_ATTENDANT_TO_EVENT, null, $invitation->user_id);


                                if ($booleanResponse) {
                                    return true;
                                }
                                \Yii::$app->getSession()->addFlash('success', AmosEvents::txt('#companion_registered'));
                            } else {
                                if ($booleanResponse) {
                                    return false;
                                }
                                \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#invitation_invalid'));
                            }
                        } else {
                            if ($booleanResponse) {
                                return false;
                            }
                            \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#invitation_not_found'));
                        }
                    } else {
                        if ($booleanResponse) {
                            return false;
                        }
                        \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#user_not_found'));
                    }
                } else {
                    if ($booleanResponse) {
                        return false;
                    }
                    \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#companion_not_found'));
                }
            } else {
                if ($booleanResponse) {
                    return false;
                }
                \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('Attendance registration closed'));
            }

            return $this->redirect('/events/event/view?id=' . $eid . '#tab-participants');
        }

        return AmosEvents::txt('Event not found');
    }

    /**
     * @param $eid
     * @param $iid
     * @param $code
     * @return mixed|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDownloadTickets($eid, $iid, $code)
    {

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        /** @var Event $event */
        $event = $eventModel::findOne(['id' => $eid]);
        $seatModel = null;


        $filenameTicket = 'Ticket.pdf';

        if ($event) {
            if ($event->has_tickets) {
                /** @var EventInvitation $invitation */
                $invitation = $eventInvitationModel::findOne(['id' => $iid, 'code' => $code]);
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
                            $filenameTicket = $assignedSeat->getTicketName() . '.pdf';
                            $seatModel = $assignedSeat;
                        }
                    }

                    $content = $this->renderPartial(
                        !empty($event->ticket_layout_view) ? $event->ticket_layout_view : 'pdf-tickets/general-layout',
                        [
                            'eventData' => $event,
                            'participantData' => [
                                'nome' => $invitation->name,
                                'cognome' => $invitation->surname,
                                'azienda' => $invitation->company,
                                'codice_fiscale' => $event->abilita_codice_fiscale_in_form ? $invitation->fiscal_code : "",
                                'email' => $invitation->email,
                                'note' => $invitation->notes,
                                'accreditation_list_id' => $invitation->accreditation_list_id,
                                'accreditationModel' => $invitation->getAccreditationList()->one(),
                                'companion_of' => null,
                                'seat' => $seat,
                            ],
                            'seatModel' => $seatModel,
                            'qrcode' => $event->has_qr_code ? EventsUtility::createQrCode($event, $invitation,
                                'participant', null, null, 'png') : '',
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
                        $eventAccreditationListModel = $this->eventsModule->createModel('EventAccreditationList');
                        $content .= $this->renderPartial(!empty($event->ticket_layout_view) ? $event->ticket_layout_view
                            : 'pdf-tickets/general-layout',
                            [
                                'eventData' => $event,
                                'participantData' => [
                                    'nome' => $companion->nome,
                                    'cognome' => $companion->cognome,
                                    'azienda' => $companion->azienda,
                                    'codice_fiscale' => $event->abilita_codice_fiscale_in_form ? $companion->codice_fiscale : "",
                                    'email' => $companion->email,
                                    'note' => $companion->note,
                                    'accreditation_list_id' => $companion->event_accreditation_list_id,
                                    'accreditationModel' => $eventAccreditationListModel::findOne(['id' => $companion->event_accreditation_list_id]),
                                    'companion_of' => $invitation,
                                    'seat' => $seat,
                                ],
                                'seatModel' => $seatModel,
                                'qrcode' => $event->has_qr_code ? EventsUtility::createQrCode($event, $invitation,
                                    'companion', $companion, null, 'png') : "",
                            ]);
                    }

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

                    if($invitation->user_id == \Yii::$app->user->id) {
                        $invitation->ticket_downloaded_at = date("Y-m-d H:i:s");
                        $invitation->ticket_downloaded_by = (!empty(\Yii::$app->user) && !empty(\Yii::$app->user->id)) ? \Yii::$app->user->id
                            : $invitation->user_id;
                    }
                    $invitation->save(false);

                    return $pdf->render();
                } else {
                    \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#invitation_not_found'));
                }

                return $this->redirect('/events/event/view?id=' . $eid);
            } else {
                \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#function_not_available'));
                return $this->redirect("/");
            }
        }

        \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#event_not_found'));

        return $this->redirect("/");
    }

    /**
     * @param int $id dell'invito/partecipante
     * @return bool
     */
    public function actionChangeParticipantAccreditationList($id)
    {

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $invitation = $eventInvitationModel::findOne(['id' => $id]);

        if ($invitation) {
            if (Yii::$app->request->post() && array_key_exists('accreditationListId', Yii::$app->request->post())) {
                $invitation->accreditation_list_id = Yii::$app->request->post('accreditationListId');

                return $invitation->save(false);
            }
        }

        return false;
    }

    /**
     * @param int $id Id dell'accompagnatore
     * @return bool
     */
    public function actionChangeCompanionAccreditationList($id)
    {

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        $companion = $eventParticipantCompanionModel::findOne(['id' => $id]);

        if ($companion) {
            if (Yii::$app->request->post() && array_key_exists('accreditationListId', Yii::$app->request->post())) {
                $companion->event_accreditation_list_id = Yii::$app->request->post('accreditationListId');
                $companion->event_accreditation_list_id = Yii::$app->request->post('accreditationListId');

                return $companion->save(false);
            }
        }

        return false;
    }

    /**
     * @param int|null $eid
     * @param int|null $iid
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionShowCompanionsListOnly($eid = null, $iid = null)
    {
        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        $invitationId = '';
        $isGroup = false;
        if (empty($iid)) {
            $expandRowKey = \Yii::$app->request->post('expandRowKey');
            if ($expandRowKey && !empty($expandRowKey)) {
                $invitation = $eventInvitationModel::find()->andWhere(['user_id' => CommunityUserMm::findOne(['id' => $expandRowKey])->user_id,
                    'event_id' => $eid])->one();
                $invitationId = $invitation->id;
                $isGroup = $invitation->is_group;
            }
        } else {
            $invitationId = $iid;
        }

        if ($eid && $invitationId) {
            $companions = $eventParticipantCompanionModel::find()->andWhere(['event_invitation_id' => $invitationId]);
            /** @var Event $eventModelNew */
            $eventModelNew = $this->eventsModule->createModel('Event');
            $eventModel = $eventModelNew::findOne(['id' => $eid]);

            return $this->renderPartial('event_companion_list_only',
                [
                    'invitationId' => $invitationId,
                    'companions' => $companions,
                    'eventModel' => $eventModel,
                    'isGroup' => $isGroup
                ]);
        }

        return AmosEvents::txt('#companions_not_found');
    }

    /**
     * @param int $eid ID dell'evento
     */
    public function actionSendTicketsNotSent($eid)
    {

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $invitations = $eventInvitationModel::findAll(['event_id' => $eid, 'is_ticket_sent' => false]);

        $result = true;
        foreach ($invitations as $invitation) {
            $result = $this->sendTicket($eid, $invitation->id);
            if ($result) {
                $invitation->is_ticket_sent = true;
                $invitation->save(false);
            } else {
                break;
            }
        }

        if ($result) {
            \Yii::$app->getSession()->addFlash('success', AmosEvents::txt('Tickets sent'));
        } else {
            \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('Error sending tickets'));
        }

        return $this->redirect('/events/event/view?id=' . $eid . '#tab-participants');
    }

    /**
     * @param $eid
     * @param $iid
     * @return \yii\web\Response
     */
    public function actionSendTicket($eid, $iid)
    {

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $invitation = $eventInvitationModel::findOne(['id' => $iid]);
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');
        $event = $eventModel::findOne(['id' => $eid]);

        if (!empty($invitation) && !empty($event)) {
            $result = $this->sendTicket($eid, $iid);
            if ($result) {
                $invitation->is_ticket_sent = true;
                $invitation->save(false);
            }

            if ($result) {
                \Yii::$app->getSession()->addFlash('success', AmosEvents::txt('Ticket sent'));
            } else {
                \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('Error sending ticket'));
            }
        } else {
            \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#invitation_not_found'));
        }

        return $this->redirect(Url::previous());
    }

    /**
     * @param int $eid Id dell'evento
     * @param int $iid Id dell'invito/partecipante
     */
    public function sendSignupConfirmEmail($eid, $iid, $linkToken = null)
    {

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        /** @var Event $event */
        $event = $eventModel::findOne(['id' => $eid]);
        /** @var EventInvitation $invitation */
        $invitation = $eventInvitationModel::findOne(['id' => $iid]);
        /** @var User $user */
        $user = User::findOne(['id' => $invitation->user_id]);
        /** @var EventParticipantCompanion $companions */
        $companions = $eventParticipantCompanionModel::find()->andWhere(['event_invitation_id' => $invitation->id])->andWhere([
            'deleted_at' => null, 'deleted_by' => null])->asArray()->all();

        /////////////////////////////////////////////////////
        // Send email to participant
        // Default email values
        $from = $this->getFromMail($event);

        $to = $user->email;
        /** @var UserProfile $userProfile */
        $userProfile = $user->getProfile();
        // Populate SUBJECT
        $subject = AmosEvents::t('amosevents', 'Signup confirm for event') . ' ' . html_entity_decode($event->title);

        // Populate TEXT
        $createCommunityUrlParams = [
            '/community/join',
            'id' => $event->community_id,
        ];
        $communityUrl = Yii::$app->urlManager->createAbsoluteUrl($createCommunityUrlParams);

        // Populate TEXT
        $createRemoveUrlParams = [
            '/events/event/remove-signup-to-event',
            'eid' => $eid,
            'iid' => $iid,
            'code' => $invitation->code,
        ];
        $removeInvitationUrl = Yii::$app->urlManager->createAbsoluteUrl($createRemoveUrlParams);

        // Create download ics link
        $createDownloadIcsParams = [
            '/events/event/download-ics',
            'eid' => $eid,
            'iid' => $iid,
            'code' => $invitation->code,
        ];
        $downloadIcsUrl = Yii::$app->urlManager->createAbsoluteUrl($createDownloadIcsParams);
        if (empty($linkToken)) {
            $linkToken = $communityUrl;
        }
        $text = $this->renderPartial((!empty($event->email_subscribe_view) ? $event->email_subscribe_view : 'event_confirm_mail'),
            [
                'userProfile' => $userProfile,
                'companions' => $companions,
                'event' => $event,
                'linkToken' => $linkToken,
                'invitation' => $invitation,
                'communityLink' => $communityUrl,
                'removeInvitationLink' => $removeInvitationUrl,
                'downloadIcsLink' => $downloadIcsUrl,
            ]);

        $files = [];
        $bcc[] = $user->email;
        $params = [];
        $priority = 0;
        $use_queue = false;

        // SEND EMAIL
        Email::sendMail(
            $from, $to, $subject, $text, $files, $bcc, $params, $priority, $use_queue
        );

        return true;
    }

    /**
     * @param int $eid Id dell'evento
     * @param int $iid Id dell'invito/partecipante
     */
    public function sendTicket($eid, $iid)
    {

        $moduleEvent = \Yii::$app->getModule('events');
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        /** @var Event $event */
        $event = $eventModel::findOne(['id' => $eid]);

        /** @var EventInvitation $invitation */
        $invitation = $eventInvitationModel::find()
            ->andWhere(['id' => $iid])
            ->andWhere(['event_id' => $eid])
            ->one();

        if ($invitation) {
            /** @var User $user */
            $user = User::findOne(['id' => $invitation->user_id]);
            /** @var EventParticipantCompanion $companions */
            $companions = $eventParticipantCompanionModel::find()->andWhere(['event_invitation_id' => $invitation->id])->andWhere([
                'deleted_at' => null, 'deleted_by' => null])->asArray()->all();

            /////////////////////////////////////////////////////
            // Send email to participant
            // Default email values
            $from = $this->getFromMail($event);

            //Switch mail
            if ($user && $user->id) {
                $to = $user->email;

                /** @var UserProfile $userProfile */
                $userProfile = $user->getProfile();
            } elseif ($invitation->email) {
                $to = $invitation->email;
                $userProfile = new UserProfile();
                $userProfile->nome = $invitation->name;
                $userProfile->cognome = $invitation->surname;
            } else {
                return false;
            }

            // Populate SUBJECT
            $ticketSubject = trim($event->email_ticket_subject);
            $ticketText = '';
            if ($moduleEvent->enableNewWizard) {
                $eventTemplate = $event->eventEmailTemplates;
                if (!empty($event->eventEmailTemplates)) {
                    $subject_attributes = EventEmailTemplates::TEMPLATE_SEND_TICKETS . '_subject';
                    $text_attribute = EventEmailTemplates::TEMPLATE_SEND_TICKETS;
                    if (!empty($eventTemplate->$subject_attributes)) {
                        $ticketSubjectPersonalized = EventMailUtility::parseEmailWithParams($event, $user->id, $eventTemplate->$subject_attributes, false);
                    }
                    if (!empty($eventTemplate->$text_attribute)) {
                        $ticketText = EventMailUtility::parseEmailWithParams($event, $user->id, $eventTemplate->$text_attribute);
                    }
                }
            }

            if(!empty($ticketSubjectPersonalized)){
                $subject = $ticketSubjectPersonalized;
            } else if (!empty($ticketSubject)) {
                $subject = $event->email_ticket_subject;
            } else {
                $subject = AmosEvents::t('amosevents', 'Your ticket for event') . ' ' . $event->title;
            }

            // Populate TEXT
            $createUrlParams = [
                '/events/event/download-tickets',
                'eid' => $eid,
                'iid' => $iid,
                'code' => $invitation->code,
            ];
            $url = Yii::$app->urlManager->createAbsoluteUrl($createUrlParams);

            // Create download ics url
            $createDownloadIcsParams = [
                '/events/event/download-ics',
                'eid' => $eid,
                'iid' => $iid,
                'code' => $invitation->code,
            ];
            $downloadIcsUrl = Yii::$app->urlManager->createAbsoluteUrl($createDownloadIcsParams);


            if($moduleEvent->enableNewWizard){
                $footerType = $eventTemplate->getFooterText()[EventEmailTemplates::TEMPLATE_SEND_TICKETS];
                $ticketText.="<p>" ."<a href='$url'>".AmosEvents::t('amosevents', "Per scaricare i biglietti, clicca qui")."</a>" ."</p>";
                $ticketText.="<p>" ."<a href='$downloadIcsUrl'>".AmosEvents::t('amosevents', "Per scaricare il file calendario (.ics), clicca qui.")."</a>" ."</p>";
               return EventMailUtility::sendEmailTest($from, $to, $subject, $ticketText, $event, $footerType);
            }

            $text = $this->renderPartial((!empty($event->email_view) ? $event->email_view : 'event_ticket_mail'),
                [
                    'userProfile' => $userProfile,
                    'companions' => $companions,
                    'downloadTicketsLink' => $url,
                    'downloadIcsLink' => $downloadIcsUrl,
                    'event' => $event,
                    'invitation' => $invitation,
                    'ticketText' => $ticketText
                ]);

            $files = [];
            $bcc = []; //$user->email;
            $params = [];
            $priority = 0;
            $use_queue = false;

            // SEND EMAIL
            if (!empty(trim($event->email_ticket_layout_custom))) {
                $mailModule = \Yii::$app->getModule("email");
                $mailModule->defaultLayout = $event->email_ticket_layout_custom;
            }

            Email::sendMail(
                $from, $to, $subject, $text, $files, $bcc, $params, $priority, $use_queue
            );

            return true;
        }
        return false;
    }

    /**
     * @param Event $event
     * @param EventInvitation $invitation
     * @return bool
     * @throws \Exception
     */
    public function removeSignupToEvent($event, $invitation)
    {

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        $result = true;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Soft delete companions
            $companions = $eventParticipantCompanionModel::findAll(['event_invitation_id' => $invitation->id]);
            foreach ($companions as $companion) {
                // free the seat for the user
                $seat = $companion->assignedSeat;
                if ($seat) {
                    $seat->event_participant_companion_id = null;
                    $seat->status = EventSeats::STATUS_EMPTY;
                    $seat->save(false);
                }
                $companion->deleted_at = date('Y-m-d H:i:s');
                $companion->deleted_by = (!empty(\Yii::$app->user) && !empty(\Yii::$app->user->id) ? \Yii::$app->user->id
                    : $invitation->user_id);
                if ($result) {
                    $result = $companion->save(false);
                }
            }
            if (!empty($event->community_id)) {
                // Soft delete CommunityUserMm record
                $communityUserMm = CommunityUserMm::findOne(['user_id' => $invitation->user_id, 'community_id' => $event->community_id]);
                $communityUserMm->deleted_at = date('Y-m-d H:i:s');
                $communityUserMm->deleted_by = (!empty(\Yii::$app->user) && !empty(\Yii::$app->user->id) ? \Yii::$app->user->id
                    : $invitation->user_id);
                if ($result) {
                    $result = $communityUserMm->save(false);
                }
            }

            // free the seat for the user
            $seat = $invitation->getAssignedSeat();
            if ($seat) {
                $seat->user_id = null;
                $seat->status = EventSeats::STATUS_EMPTY;
                $seat->save(false);
            }
            // Soft delete invitation
            $invitation->deleted_at = date('Y-m-d H:i:s');
            $invitation->deleted_by = (!empty(\Yii::$app->user) && !empty(\Yii::$app->user->id) ? \Yii::$app->user->id : $invitation->user_id);
            if ($result) {
                $result = $invitation->save(false);
            }

            if ($result) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (\Exception $exception) {
            $transaction->rollBack();
            return false;
        }

        return $result;
    }

    /**
     * @param int $eid
     * @param int $iid
     * @param string $code
     * @param bool $autoRemove
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRemoveSignupToEvent($eid, $iid, $code, $autoRemove = false)
    {
        $this->setUpLayout('main');

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var Event $event */
        $event = $eventModel::findOne(['id' => $eid]);
        /** @var EventInvitation $invitation */
        $invitation = $eventInvitationModel::findOne(['id' => $iid, 'code' => $code]);

        $previousUrl = Url::previous();
        $previousUrl = !empty($previousUrl) ? $previousUrl : '/';
        $confirmUrl = Url::current() . '&confirm=1';

        if ($event && $invitation && !empty($event) && !empty($invitation)) {
            $get = \Yii::$app->request->get();

            if (!array_key_exists('confirm', $get)) {
                return $this->render('remove_signup_to_event',
                    [
                        'user' => User::findOne(['id' => $invitation->user_id]),
                        'previousUrl' => $previousUrl,
                        'confirmUrl' => $confirmUrl,
                        'autoRemove' => $autoRemove,
                    ]);
            } else {
                if ($get['confirm']) {
                    $result = $this->removeSignupToEvent($event, $invitation);
                    if ($result) {
                        \Yii::$app->getSession()->addFlash('success',
                            AmosEvents::txt('La partecipazione è stata rimossa con successo'));
                    } else {
                        \Yii::$app->getSession()->addFlash('danger',
                            AmosEvents::txt('La partecipazione non è stata rimossa a causa di un errore'));
                    }
                }
            }
        } else {
            \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('L\'url non è valido'));
        }

        return $this->redirect($previousUrl);
    }

    /**
     * @param $eid
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSendTicketsMassive($eid)
    {
        $this->setUpLayout('main');

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var Event $event */
        $event = $eventModel::findOne(['id' => $eid]);

        if ($event && !empty($event)) {
            $previousUrl = Url::previous();
            $previousUrl = !empty($previousUrl) ? $previousUrl . "#tab-participants" : '/';

            $post = \Yii::$app->request->post();
            if ($post) {
                $sentOk = true;
                if (array_key_exists('selectedInvitations', $post)) {
                    foreach ($post['selectedInvitations'] as $selectedInvitation) {
                        $invitation = $eventInvitationModel::findOne(['event_id' => $eid, 'id' => $selectedInvitation]);
                        if ($invitation) {
                            $sentOk = $this->sendTicket($eid, $selectedInvitation);
                            if ($sentOk) {
                                $invitation->is_ticket_sent = true;
                                $invitation->save(false);
                            } else {
                                break;
                            }
                        }
                    }
                }

                if ($sentOk) {
                    \Yii::$app->getSession()->addFlash('success', AmosEvents::txt('Tickets sent'));
                } else {
                    \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('Error sending tickets'));
                }

                return $this->redirect($previousUrl);
            }

            $invitations = $eventInvitationModel::find()
                ->andWhere(['event_invitation.event_id' => $event->id])
                ->andWhere(['event_invitation.deleted_at' => null, 'event_invitation.deleted_by' => null])
                ->orderBy('is_ticket_sent');

            if ($event->isSeatManagement()) {
                $invitations->innerJoin('event_seats', 'event_seats.user_id = event_invitation.user_id');
            }

            $invitationsFiltered = [];
            foreach ($invitations->all() as $invitation) {
                if ($invitation->everyoneInSameInvitationHasAccreditationList()) {
                    $invitationsFiltered[] = $invitation->id;
                }
            }

            $invitations->andWhere(['event_invitation.id' => $invitationsFiltered]);


            return $this->render('event_send_tickets_massive',
                [
                    'currentView' => $this->getCurrentView(),
                    'invitations' => $invitations,
                    'event' => $event,
                    'previousUrl' => $previousUrl,
                ]);
        }

        return $this->render('event_not_found');
    }

    /**
     * @param Event $event
     * @return string
     */
    public function generateIcs($event)
    {
        // Address
        $location = ($event->event_location) ? $event->event_location . ' - ' : ''; //'-';
        $address = ($event->event_address) ? $event->event_address . ', ' : ''; //'-';
        $addressNumber = ($event->event_address_house_number) ? $event->event_address_house_number . ' ' : ''; //'-';
        $cap = ($event->event_address_cap) ? '- ' . $event->event_address_cap . ' ' : ''; //'-';
        $city = ($event->cityLocation) ? $event->cityLocation->nome . ' ' : ''; //'-';
        $province = ($event->provinceLocation) ? '(' . $event->provinceLocation->sigla . ') ' : ''; //'-';
        $country = ($event->countryLocation) ? '- ' . $event->countryLocation->nome : ''; //'-' ;

        $locationString = $event->getFullLocationString();
        if(empty($locationString)){
            $loc   =  $event->eventLocation->name . (!empty($event->eventLocationEntrance) ? $event->eventLocationEntrance->name : '' );
        }
        else {
            $loc = $location . $address . $addressNumber . $cap . $city . $province . $country;
        }

        // Event view url
        $createUrlParams = [
            '/events/event/view',
            'id' => $event->id,
        ];
        $url = Yii::$app->urlManager->createAbsoluteUrl($createUrlParams);

        $ics = new ICS([
            'location' => $loc,
            'description' => html_entity_decode(strip_tags($event->description)),
            'dtstart' => $event->begin_date_hour,
            'dtend' => $event->end_date_hour,
            'summary' => html_entity_decode(strip_tags($event->title)),
            'url' => $url
        ]);

        return $ics->to_string();
    }

    /**
     * @param $event
     * @return string
     */
    public function downloadIcs($event)
    {
        $ics = $this->generateIcs($event);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="calendar.ics"');

        return \Yii::$app->response->data = $ics;
    }

    /**
     * @param $eid
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionForceDownloadIcs($eid)
    {
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var Event $event */
        $event = $eventModel::findOne(['id' => $eid]);

        $previousUrl = Url::previous();
        $previousUrl = !empty($previousUrl) ? $previousUrl : '/';

        if ($event) {
            return $this->downloadIcs($event);
        } else {
            \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('Event not found'));
        }

        return $this->redirect($previousUrl);
    }

    /**
     * @param $eid
     * @param $iid
     * @param $code
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDownloadIcs($eid, $iid, $code)
    {
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var Event $event */
        $event = $eventModel::findOne(['id' => $eid]);

        $previousUrl = Url::previous();
        $previousUrl = !empty($previousUrl) ? $previousUrl : '/';

        if ($event) {
            /** @var EventInvitation $invitation */
            $invitation = $eventInvitationModel::findOne(['id' => $iid, 'code' => $code]);
            if ($invitation) {
                return $this->downloadIcs($event);
            } else {
                \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('#invitation_not_found'));
            }
        } else {
            \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('Event not found'));
        }

        return $this->redirect($previousUrl);
    }

    /**
     * @param $eid
     * @param $iid
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGetQrCodeParticipant($eid, $iid)
    {
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $event = $eventModel::find()->andWhere(['id' => $eid])->andWhere(['deleted_at' => null, 'deleted_by' => null])->asArray()->one();

        if ($event) {
            $invitation = $eventInvitationModel::findOne(['id' => $iid]);
            if ($invitation) {
                return EventsUtility::createQrCode($event, $invitation, 'participant');
            }
        }

        return '';
    }

    /**
     * @param $eid
     * @param $iid
     * @param $cid
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGetQrCodeCompanion($eid, $iid, $cid)
    {
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        $event = $eventModel::find()
            ->andWhere([
                'id' => $eid,
                'deleted_at' => null,
                'deleted_by' => null
            ])
            ->asArray()
            ->one();

        if ($event) {
            $invitation = $eventInvitationModel::findOne(['id' => $iid, 'event_id' => $eid]);
            if ($invitation) {
                $companion = $eventParticipantCompanionModel::findOne(['id' => $cid, 'event_invitation_id' => $iid]);
                if ($companion) {
                    return EventsUtility::createQrCode($event, $invitation, 'companion', $companion);
                }
            }
        }

        return '';
    }

    /**
     * @param $eid
     * @param $iid
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionParticipantDetail($eid, $iid)
    {
        $this->setUpLayout('main');

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        $event = $eventModel::find()->andWhere(['id' => $eid])->andWhere(['deleted_at' => null, 'deleted_by' => null])->asArray()->one();

        if ($event) {
            $invitation = $eventInvitationModel::findOne(['id' => $iid, 'event_id' => $eid]);
            if ($invitation) {
                $companions = $eventParticipantCompanionModel::findAll(['event_invitation_id' => $invitation->id]);

                return $this->render('event_participant_detail',
                    [
                        'event' => $event,
                        'invitation' => $invitation,
                        'companions' => $companions,
                    ]);
            }

            return AmosEvents::txt('#invitation_not_found');
        }

        return AmosEvents::txt('Event not found');
    }

    /**
     * @param int|null $communityId
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionParticipants($communityId = null)
    {
        $this->setUpLayout('main');

        $previousUrl = Url::previous();
        $previousUrl = !empty($previousUrl) ? $previousUrl : '/';

        if ($communityId) {
            /** @var Event $eventModel */
            $eventModel = $this->eventsModule->createModel('Event');
            $event = $eventModel::findOne(['community_id' => $communityId]);
            if ($event) {
                return $this->render(
                    'participants', [
                        'model' => $event,
                    ]
                );
            }

            \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('Event not found'));
        }

        \Yii::$app->getSession()->addFlash('danger', AmosEvents::txt('Event not found'));

        return $this->redirect($previousUrl);
    }

    /**
     * @param $eid
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDownloadParticipantsExcel($eid)
    {
        $this->setUpLayout('main');

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $event = $eventModel::find()->andWhere(['id' => $eid])->andWhere(['deleted_at' => null, 'deleted_by' => null])->asArray()->one();

        if ($event) {
            $participants = $eventInvitationModel::findAll(['event_id' => $event['id']]);
            if (!empty($participants)) {
                $columnsHeader = [
                    'name_surname' => AmosEvents::txt('#participant_nome') . ' ' . AmosEvents::txt('#participant_cognome'),
                    'companion_of' => AmosEvents::txt('Accompagnatore di'),
                    'company' => AmosEvents::txt('#participant_azienda'),
                    'accreditation_list' => AmosEvents::txt('Accreditation list'),
                    'ticket_sent' => AmosEvents::txt('Tickets sent?'),
                    'downloadedat' => AmosEvents::txt('Ticket downloaded at'),
                    'downloadedby' => AmosEvents::txt('Ticket downloaded by'),
                    'attendant' => AmosEvents::txt('Attendant')
                ];

                $results = [];

                /** @var EventInvitation $participant */
                foreach ($participants as $participant) {
                    $results[] = [
                        'name_surname' => $participant->name . ' ' . $participant->surname,
                        'companion_of' => '',
                        'company' => $participant->company,
                        'accreditation_list' => !empty($participant->getAccreditationList()->one()) ? $participant->getAccreditationList()->one()->title
                            : '',
                        'ticket_sent' => ($participant->is_ticket_sent ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore',
                            'No')
                        ),
                        'downloadedat' => !empty($participant->ticket_downloaded_at) ? date("d-m-Y H:i:s",
                            strtotime($participant->ticket_downloaded_at)) : '',
                        'downloadedby' => !empty($participant->ticket_downloaded_by) ? (!empty(UserProfile::findOne(['user_id' => $participant->ticket_downloaded_by]))
                            ? (UserProfile::findOne(['user_id' => $participant->ticket_downloaded_by])['nomeCognome']) : '')
                            : '',
                        'attendant' => ($participant->presenza ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No')
                        ),
                    ];

                    $companions = $participant->getCompanions()->all();
                    if (!empty($companions)) {
                        /** @var EventParticipantCompanion $companion */
                        foreach ($companions as $companion) {
                            $results[] = [
                                'name_surname' => $companion->nome . ' ' . $companion->cognome,
                                'companion_of' => $participant->name . ' ' . $participant->surname,
                                'company' => $companion->azienda,
                                'accreditation_list' => !empty($companion->getAccreditationList()->one()) ? $companion->getAccreditationList()->one()->title
                                    : '',
                                'ticket_sent' => '',
                                'downloadedat' => '',
                                'downloadedby' => '',
                                'attendant' => ($companion->presenza ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore',
                                    'No')
                                ),
                            ];
                        }
                    }
                }

                header('Content-Type: application/vnd.ms-excel; charset=utf-8');
                header("Content-Disposition: attachment; filename=\"Partecipanti Evento {$event['title']}.xlsx\"");
                \Yii::$app->response->content = Excel::export([
                    'models' => $results,
                    'columns' => [
                        'name_surname',
                        'companion_of',
                        'company',
                        'accreditation_list',
                        'ticket_sent',
                        'downloadedat',
                        'downloadedby',
                        'attendant',
                    ],
                    'headers' => $columnsHeader,
                    'format' => 'Xlsx'
                ]);
            }
        }

        return AmosEvents::txt('Event not found');
    }

    /**
     * @param $eid
     * @param $iid
     * @param $cid
     * @param bool $booleanResponse
     * @return bool|string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRemoveCompanion($eid, $iid, $cid, $booleanResponse = false)
    {

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        $event = $eventModel::find()->andWhere(['id' => $eid])->andWhere(['deleted_at' => null, 'deleted_by' => null])->asArray()->one();

        if ($event) {
            $participant = $eventInvitationModel::findOne(['id' => $iid, 'event_id' => $eid]);
            if ($participant) {
                $companion = $eventParticipantCompanionModel::findOne(['id' => $cid, 'event_invitation_id' => $participant->id]);
                if ($companion) {
                    $companion->deleted_at = date("Y-m-d H:i:s");
                    $companion->deleted_by = \Yii::$app->user->id;
                    $done = $companion->save(false);

                    if ($done) {
                        if (!$booleanResponse) {
                            return AmosEvents::txt('L\'accompagnatore è stato rimosso con successo');
                        } else {
                            return true;
                        }
                    } else {
                        if (!$booleanResponse) {
                            return AmosEvents::txt('Error');
                        }
                    }
                } else {
                    if (!$booleanResponse) {
                        return AmosEvents::txt('#companion_not_found');
                    }
                }
            } else {
                if (!$booleanResponse) {
                    return AmosEvents::txt('#user_not_found');
                }
            }
        } else {
            if (!$booleanResponse) {
                return AmosEvents::txt('Event not found');
            }
        }

        return false;
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function actionImportSeats($id)
    {
        /** @var  $model Event */
        $model = $this->findModel($id);
        $ok = $model->import();
        $n_seats = $model->getEventSeats()->count();
        $model->seats_available = $n_seats;
        $model->save(false);
        return $this->redirect(['view', 'id' => $id, '#' => 'tab-seats_management']);
    }

    /**
     *
     */
    public function actionDownloadImportFileExample()
    {
        $path = Yii::getAlias('@vendor') . '/open20/amos-events/src/downloads';
        $file = $path . '/Import_seats_example.xls';
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewSector($id)
    {

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setUpLayout('main_events');
        } else {
            $this->setUpLayout('main');
        }
        $resetScope = Yii::$app->request->get('resetscope');
        if (!is_null($resetScope) && ($resetScope == 1)) {
            $cwhModule = \Yii::$app->getModule('cwh');
            if (isset($cwhModule)) {
                /** @var \open20\amos\cwh\AmosCwh $cwhModule */
                $cwhModule->resetCwhScopeInSession();
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $this->eventsModule->createModel('EventSeats');

        $seat = $eventSeatsModel::findOne($id);
        if (empty($seat)) {
            throw new NotFoundHttpException('Not found');
        }

        $event = $seat->event;
        $this->model = $event;
        $dataProvider = new ActiveDataProvider([
            'query' => $event->getEventSeats()->andWhere(['sector' => $seat->sector])
        ]);
        return $this->render('view_sector',
            [
                'dataProvider' => $dataProvider,
                'model' => $event,
                'sector' => $seat->sector
            ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDeleteSector($id)
    {

        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $this->eventsModule->createModel('EventSeats');

        $seat = $eventSeatsModel::findOne($id);
        if (empty($seat)) {
            throw new NotFoundHttpException('Not found');
        }

        $this->model = $seat->event;
        $seats = $eventSeatsModel::find()->andWhere([
            'event_id' => $seat->event_id,
            'sector' => $seat->sector,
        ])->all();


        foreach ($seats as $seat) {
            $seat->delete();
        }
        \Yii::$app->session->addFlash('success', AmosEvents::t('amosevents', 'Settore eliminato correttamente'));
        return $this->redirect(['view', 'id' => $this->model->id, '#' => 'tab-seats_management']);
    }

    /**
     * @param $eid
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionEventSignupGroup($eid)
    {
        $this->setUpLayout('form');
        $event = $this->findModel($eid);
        /** @var  $event Event */
        $this->model = $event;

        $gdprQuestions = $this->prepareArrayGdpr($event);

        /** @var RegisterGroupForm $registerGroupForm */
        $registerGroupForm = $this->eventsModule->createModel('RegisterGroupForm');
        $registerGroupForm->event_id = $eid;

        // Controllo che le iscrizioni siano aperte (data inizio < della data odierna, data fine > della data odierna)
        if ($event->isSubscribtionsOpened()) {

            $post = \Yii::$app->request->post();
            if ($post && $registerGroupForm->load($post)) {
                // Controlla se qualcuno e' già stato iscritto all'evento con la stessa mail...
                $invitationFound = $this->isInvitationGroupFound($eid, $registerGroupForm);

                // ----------------------------
                if ($registerGroupForm->validate()) {
                    if ($invitationFound == 0 || ($this->eventsModule->enableAutoInviteUsers && count($invitationFound) > 0)) {
                        // Per controllare se risposte GDPR sono state marcate prima dell'invio dei dati
                        $gdprMarked = true;
                        if ($this->eventsModule->enableGdpr && $event->countGdprQuestions() > 0) {
                            $gdprMarked = false;
                            if (array_key_exists('gdprQuestion', $post)) {
                                $gdprMarked = ($event->countGdprQuestions() == count($post['gdprQuestion']));
                            }
                        }

                        if ($gdprMarked) {
                            $companionsQuantity = $registerGroupForm->nSeats;
                            $participantsWantToJoin = $companionsQuantity;
                            if ($event->eventType->limited_seats &&
                                (
//                                    ($participantsWantToJoin > ($event->seats_available - $event->checkParticipantsQuantity())) ||
                                !$event->canSubscribeGroup($participantsWantToJoin)
                                )
                            ) {
                                \Yii::$app->session->addFlash('danger', 'Limite posti superato');
                            } else {
                                // registro l'utente Referente del gruppo
                                $user = $this->registerUser($registerGroupForm);
                                if ($user && $user->userProfile->attivo) {

                                    $gdprQuestions = [];
                                    if ($this->eventsModule->enableGdpr && array_key_exists('gdprQuestion', $post)) {
                                        $gdprQuestions = $post['gdprQuestion'];
                                    }

                                    //creo il record di event_invitation per il referente
                                    $invitation = null;
                                    $dataParticipant ['nome'] = $registerGroupForm->groupName;
                                    $dataParticipant ['cognome'] = "Group";
                                    $dataParticipant ['email'] = $registerGroupForm->email;
                                    $dataParticipant ['note'] = $registerGroupForm->note;
                                    $participant = $this->addParticipant($eid, $dataParticipant,
                                        $user->id, $gdprQuestions, $invitation, true);

                                    // Iscrivo utente alla community dell'evento
                                    $subscribedToEventCommunity = $this->subscribeToEventCommunity($eid, $user);

                                    // creo gli accompagnatori/memebri del gruppo
                                    $companions = [];
                                    for ($i = 1; $i <= $registerGroupForm->nSeats - 1; $i++) {
                                        $dataCompanion['nome'] = $registerGroupForm->groupName;
                                        $dataCompanion['cognome'] = $i;
                                        $dataCompanion['email'] = $registerGroupForm->email;
                                        $companions [] = $this->addCompanion($eid, $participant,
                                            $dataCompanion);
                                    }

                                    if ($event->isSeatManagement()) {
                                        $this->assignSeats($this->model, $participant, $companions,
                                            $registerGroupForm->sector);
                                    }

                                    $this->sendSignupConfirmEmail($event->id, $participant->id);
                                    \Yii::$app->session->addFlash('success',
                                        AmosEvents::t('amosevents', "E' stato iscritto un gruppo di {n} persone.",
                                            ['n' => $registerGroupForm->nSeats]));
                                    return $this->redirect(['view', 'id' => $eid, '#' => 'tab-participants']);

//                            return $this->render((!empty($event->thank_you_page_view) ? $event->thank_you_page_view : 'event_signup_thankyou'), [
//                                'event' => $event,
//                                'userData' => $participantData,
//                                'eventParticipantModel' => $eventParticipantModel,
//                                'eventCompanionModel' => $eventCompanionModel,
//                                'companions' => !empty($companionsData) ? $companionsData : [0 => $eventCompanionModel],
//                                'gdprQuestions' => $gdprQuestions,
//                            ]);
                                } else {
                                    \Yii::$app->getSession()->addFlash('danger',
                                        AmosEvents::txt('La email del gruppo inserita è già presente nella piattaforma ma l\'utente non risulta più attivo, occorre cambiare la email o riattivare l\'utente'));
                                }
                            }
                        } else {
                            \Yii::$app->getSession()->addFlash('danger',
                                AmosEvents::txt('Compilare tutte le domande relative alle condizioni e l\'uso dei dati personali'));
                            return $this->redirect(['view', 'id' => $event->id, '#' => 'tab-seats_managment']);
//                        return $this->render((!empty($event->subscribe_form_page_view) ? $event->subscribe_form_page_view : 'event_signup'), [
//                            'event' => $event,
//                            'userData' => $participantData,
//                            'eventParticipantModel' => $eventParticipantModel,
//                            'eventCompanionModel' => $eventCompanionModel,
//                            'companions' => !empty($companionsData) ? $companionsData : [0 => $eventCompanionModel],
//                            'gdprQuestions' => $gdprQuestions,
//                            //'invitation' => $invitation,
//                            //'partners' => $partners,
//                        ]);
                        }
                    } else {
                        \Yii::$app->getSession()->addFlash('danger',
                            AmosEvents::txt('This user has already been registered at this event'));
                    }
                }
            }
        } else {
            return $this->render((!empty($event->event_closed_page_view) ? $event->event_closed_page_view : 'event_closed'));
        }

        return $this->render('event_signup_group',
            [
                'event' => $this->model,
                'registerGroupForm' => $registerGroupForm,
                'gdprQuestions' => $gdprQuestions
            ]);
    }

    /**
     * @param $event
     * @return mixed
     */
    public function prepareArrayGdpr($event)
    {
        $gdprQuestions = [];
        if (!$this->eventsModule->enableGdpr) {
            return $gdprQuestions;
        }
        if ($event->gdpr_question_1) {
            $gdprQuestions[0] = " " . $event->gdpr_question_1;
        }
        if ($event->gdpr_question_2) {
            $gdprQuestions[1] = " " . $event->gdpr_question_2;
        }
        if ($event->gdpr_question_3) {
            $gdprQuestions[2] = " " . $event->gdpr_question_3;
        }
        if ($event->gdpr_question_4) {
            $gdprQuestions[3] = " " . $event->gdpr_question_4;
        }
        if ($event->gdpr_question_5) {
            $gdprQuestions[4] = " " . $event->gdpr_question_5;
        }
        return $gdprQuestions;
    }

    /**
     * @param $eid
     * @param $registerGroupForm
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function isInvitationGroupFound($eid, $registerGroupForm)
    {
        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $invitationFound = $eventInvitationModel::find()
            ->andWhere(['email' => $registerGroupForm->email, 'event_id' => $eid])->count();
        // ...altrimenti cerca utente associato a un invito per evitare l'iscrizione multipla
        // cercandolo sia attraverso la sua mail...
        if ($invitationFound == 0) {
            $user = User::find()->andWhere(['OR',
                ['email' => $registerGroupForm->email],
                ['username' => $registerGroupForm->email],
            ])->one();
            if ($user) {
                $invitationFound = $eventInvitationModel::find()
                    ->andWhere(['user_id' => $user->id, 'event_id' => $eid])->count();
            }
        }

        return $invitationFound;
    }

    /**
     * @param $registerGroupForm
     * @return User|null
     */
    public function registerUser($registerGroupForm)
    {
        $user = User::findOne(['email' => $registerGroupForm->email]);
        // Se utente non trovato tramite email, controllo anche tramite username (PR-336)
        if (!$user) {
            $user = User::findOne(['username' => $registerGroupForm->email]);
        }
        // Se l'utente non e' ancora registrato, lo registro alla piattaforma
        if (!$user) {
            // Creo il nuovo account utente...
            $newUser = AmosAdmin::getInstance()->createNewAccount(
                $registerGroupForm->groupName, 'Group', $registerGroupForm->email, 0
            );
            $user = $newUser['user'];
        }
        return $user;
    }

    /**
     * @param $event Event
     * @param $participant
     * @param $companions
     * @param $selectedSector
     */
    public function assignSeats($event, $participant, $companions, $selectedSector)
    {

        $sectors = $event->getSectorsAvailableForGroups();
        $participantAssigned = false;
        $toAssign = false;
        $i = 0;
        // start assigning seats from the sector selected
        foreach ($sectors as $key => $sector) {
            if ($sector->sector == $selectedSector || $toAssign) {
                $toAssign = true;
                $seats = $event->getSeatsAvailableForGroups($sector->sector);
//                pr($sector->sector, 'sector');
//                pr(count($seats),'seats for sector');
                if (!empty($seats)) {
                    // assign the seat to the user referent for the group
                    if (!$participantAssigned) {
                        $currentSeat = array_shift($seats);
                        $currentSeat->user_id = $participant->user_id;
                        $currentSeat->status = EventSeats::STATUS_ASSIGNED;
                        $currentSeat->type_of_assigned_participant = 1;
                        $currentSeat->save(false);
                        $participantAssigned = true;
                    }

                    // assign the seat tothe companions
                    while (count($companions) != 0) {
//                        $i++;
//                        if($i > 30 ){
//                            die;
//                        }
                        $currentSeat = null;
                        $currentSeat = array_shift($seats);
                        if (!empty($currentSeat)) {
                            $companion = array_shift($companions);
                            $currentSeat->event_participant_companion_id = $companion->id;
                            $currentSeat->status = EventSeats::STATUS_ASSIGNED;
                            $currentSeat->type_of_assigned_participant = 2;
                            $currentSeat->save(false);
                        } else {
                            unset($sectors[$key]);
                            break;
                        }
                    }
                }
            }
            if ($toAssign) {
                unset($sectors[$key]);
            }
        }


        // doing a second cycle to take the remaining sectors
        foreach ($sectors as $key => $sector) {
            $seats = $event->getSeatsAvailableForGroups($sector->sector);
//            pr($sector->sector, 'sector');
//            pr(count($seats),'seats for sector');

            while (count($companions) != 0) {
//                $i++;
//                if($i > 30 ){
//                    die;
//                }
                $currentSeat = null;
                $currentSeat = array_shift($seats);
                if (!empty($currentSeat)) {
                    $companion = array_pop($companions);
                    $currentSeat->event_participant_companion_id = $companion->id;
                    $currentSeat->status = EventSeats::STATUS_ASSIGNED;
                    $currentSeat->type_of_assigned_participant = 2;
                    $currentSeat->save(false);
                } else {
                    unset($sectors[$key]);
                    break;
                }
            }
        }
    }

    /**
     * @param $id
     * @param $user_id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAssignSeat($id, $user_id = null, $event_companion_id = null)
    {
        $this->setUpLayout('form');

        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $this->eventsModule->createModel('EventSeats');

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $this->model = $this->findModel($id);
        $n_seats_to_assign = $this->model->getEventSeats()
            ->andWhere(['status' => [EventSeats::STATUS_EMPTY, EventSeats::STATUS_TO_REASSIGN]])->count();
        $user = User::findOne($user_id);
        $eventCompanion = $eventInvitationModel::findOne($event_companion_id);

        /** @var FormAssignSeat $formModel */
        $formModel = $this->eventsModule->createModel('FormAssignSeat');

        if (\Yii::$app->request->post() && $formModel->load(\Yii::$app->request->post())) {
            /** @var  $seat EventSeats */
            $seat = $eventSeatsModel::find()
                ->andWhere(['event_id' => $id])
                ->andWhere(['sector' => $formModel->sector])
                ->andWhere(['row' => $formModel->row])
                ->andWhere(['seat' => $formModel->seat])
                ->one();

            if ($seat) {
                if ($seat->status == EventSeats::STATUS_TO_REASSIGN) {
                    $seat->status = EventSeats::STATUS_REASSIGNED;
                } else {
                    $seat->status = EventSeats::STATUS_ASSIGNED;
                }
                if (!empty($event_companion_id)) {
                    $seat->type_of_assigned_participant = 2;
                    $seat->user_id = null;
                    $seat->event_participant_companion_id = $event_companion_id;
                } else {
                    $seat->type_of_assigned_participant = 1;
                    $seat->user_id = $user_id;
                    $seat->event_participant_companion_id = null;
                }

                if ($seat->save()) {
                    \Yii::$app->session->addFlash('success',
                        AmosEvents::t('amosevents',
                            "Posto settore {sector} - fila {row} - posto {seat} assegnato correttamente all'utente {nomeCognome}",
                            [
                                'row' => $formModel->row,
                                'sector' => $formModel->sector,
                                'seat' => $formModel->seat,
                                'nomeCognome' => $user->userProfile->nomeCognome
                            ]));
                    $this->redirect(['view', 'id' => $id, '#' => 'tab-participants']);
                } else {
                    \Yii::$app->session->addFlash('danger',
                        AmosEvents::t('amosevents', "Errore nell'assegnamento del posto"));
                }
            }
        }
        return $this->render('assign_seat',
            [
                'model' => $this->model,
                'modelForm' => $formModel,
                'user' => $user,
                'eventCompanion' => $eventCompanion,
                'n_seats_to_assign' => $n_seats_to_assign
            ]);
    }

    /**
     * @param $id
     * @param $user_id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionRemoveSeat($id, $user_id = null, $event_companion_id = null)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);

        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $this->eventsModule->createModel('EventSeats');

        if (!empty($event_companion_id)) {
            $seat = $eventSeatsModel::find()
                ->andWhere(['event_id' => $id])
                ->andWhere(['event_participant_companion_id' => $event_companion_id])->one();
        } else {
            $seat = $eventSeatsModel::find()
                ->andWhere(['event_id' => $id])
                ->andWhere(['user_id' => $user_id])->one();
        }
        if (empty($seat)) {
            throw new NotFoundHttpException('Pagina non trovata');
        }
        $seat->user_id = null;
        $seat->event_participant_companion_id = null;
        $seat->status = EventSeats::STATUS_TO_REASSIGN;

        if ($seat->save()) {
            \Yii::$app->session->addFlash('success', AmosEvents::t('amosevents', "Posto liberato correttamente"));
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'tab-participants']);
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGetRowsAjax()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $sector_id = $parents[0];

                if (!empty($_POST['depdrop_params'])) {
                    $params = $_POST['depdrop_params'];
                    $event_id = $params[0]; // get the value of input-type-1
                }

                /** @var EventSeats $eventSeatsModel */
                $eventSeatsModel = $this->eventsModule->createModel('EventSeats');

                $rows = $eventSeatsModel::find()
                    ->andWhere(['event_id' => $event_id])
                    ->andWhere(['sector' => $sector_id])
                    ->andWhere(['status' => [EventSeats::STATUS_EMPTY, EventSeats::STATUS_TO_REASSIGN]])
                    ->groupBy('row')->all();

                foreach ($rows as $row) {
                    $out [] = ['name' => $row->row, 'id' => $row->row];
                }

                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGetSeatsAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $row_id = $parents[0];

                if (!empty($_POST['depdrop_params'])) {
                    $params = $_POST['depdrop_params'];
                    $event_id = $params[0]; // get the value of input-type-1
                    $sector_id = $params[1]; // get the value of input-type-1
                }

                /** @var EventSeats $eventSeatsModel */
                $eventSeatsModel = $this->eventsModule->createModel('EventSeats');

                $seats = $eventSeatsModel::find()
                    ->andWhere(['event_id' => $event_id])
                    ->andWhere(['row' => $row_id])
                    ->andWhere(['sector' => $sector_id])
                    ->andWhere(['status' => [EventSeats::STATUS_EMPTY, EventSeats::STATUS_TO_REASSIGN]])
                    ->groupBy('seat')->all();

                foreach ($seats as $seat) {
                    $out [] = ['name' => $seat->seat, 'id' => $seat->seat];
                }

                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    protected function getFromMail($event)
    {
        $from = '';
        if (!empty(trim($event->email_ticket_sender))) {
            $from = $event->email_ticket_sender;
        } else if (isset(Yii::$app->params['adminEmail'])) {
            $from = Yii::$app->params['adminEmail'];
        } else if (isset(Yii::$app->params['email-assistenza'])) {
            $from = Yii::$app->params['email-assistenza'];
        }
        return $from;
    }
}