<?php

namespace open20\amos\videoconference\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\news\AmosNews;
use open20\amos\notificationmanager\models\NotificationsRead;
use open20\amos\videoconference\models\base\UserProfileForm;
use open20\amos\videoconference\models\search\UserMeetingSearch;
use open20\amos\videoconference\models\VideoconfUsersMm;
use open20\amos\videoconference\utils\EmailUtil;
use open20\amos\videoconference\utils\VideoconfUtil;
use Yii;
use open20\amos\videoconference\models\Videoconf;
use open20\amos\videoconference\models\VideoconfSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use open20\amos\videoconference\AmosVideoconference;
use yii\web\Response;

/**
 * This is the class for controller "VideoconfController".
 */
class VideoconfController extends CrudController
{
    public $model_partecipanti;
    public $partecipanti;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'meet',
                                'json-videoconf-data',
                                'participants',
                                'past-videoconferences'
                            ],
                            'roles' => ['VIDEOCONF_READ']
                        ],
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
        ]);
        return $behaviors;
    }


    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = AmosVideoconference::t('amosvideoconference', 'Videoconferenza');
            $urlLinkAll   = '';

            $labelSigninOrSignup = AmosVideoconference::t('amosvideoconference', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = AmosVideoconference::t(
                'amosvideoconference',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = AmosVideoconference::t('amosvideoconference', '#beforeActionCtaLogin');
            $titleSignin = AmosVideoconference::t(
                'amosvideoconference',
                '#beforeActionCtaLoginTitle',
                ['platformName' => \Yii::$app->name]
            );

            $labelLink = $labelSigninOrSignup;
            $titleLink = $titleSigninOrSignup;
            $socialAuthModule = Yii::$app->getModule('socialauth');
            if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                $labelLink = $labelSignin;
                $titleLink = $titleSignin;
            }

            $ctaLoginRegister = Html::a(
                $labelLink,
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => $titleLink
                ]
            );
            $subTitleSection  = Html::tag(
                'p',
                AmosNews::t(
                    'amosnews',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = AmosVideoconference::t('amosvideoconference', 'Videoconferenze');
            $labelLinkAll = AmosVideoconference::t('amosvideoconference', 'Elenco videoconferenze passate');
            $urlLinkAll   = '/videoconference/videoconf/past-videoconferences';
            $titleLinkAll = AmosVideoconference::t('amosvideoconference', 'Visualizza la lista delle videoconferenze');

            $subTitleSection = Html::tag('p', AmosVideoconference::t('amosnews', '#beforeActionSubtitleSectionLogged'));
        }

        $labelCreate = AmosVideoconference::t('amosvideoconference', 'Nuova');
        $titleCreate = AmosVideoconference::t('amosvideoconference', 'Crea una nuova videoconferenza');
        $labelManage = AmosVideoconference::t('amosvideoconference', 'Gestisci');
        $titleManage = AmosVideoconference::t('amosvideoconference', 'Gestisci le videoconferenze');
        $urlCreate   = '/videoconference/videoconf/create';
        $urlManage   = null;

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'news',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];

        // Lasciare qui questo if e return perché se no va in loop...
        if (!parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    /**
     *
     * @return array
     */
    public static function getManageLinks()
    {
        $links = [];

        $links[] = [
            'title' => AmosVideoconference::t('amosvideoconference', 'Visualizza la lista di tutte le videoconferenze'),
            'label' => AmosVideoconference::t('amosvideoconference', 'Tutte le Videoconferenze'),
            'url' => '/videoconference/videoconf/index'
        ];

        $links[] = [
            'title' => AmosVideoconference::t('amosvideoconference', 'Visualizza la lista di tutte le videoconferenze passate'),
            'label' => AmosVideoconference::t('amosvideoconference', 'Videoconferenze passate'),
            'url' => '/videoconference/videoconf/past-videoconferences'
        ];

        if (\Yii::$app->user->can('AMMINISTRATORE_VIDEOCONF') || CommunityUtil::isLoggedCommunityManager()) {
            $links[] = [
                'title' => AmosVideoconference::t('amosvideoconference', 'Amministra le Videoconferenze'),
                'label' => AmosVideoconference::t('amosvideoconference', 'Amministra'),
                'url' => '/videoconference/videoconf/amministra'
            ];
        }


        return $links;
    }

    /**
     * manda la mail agli utenti collegati alla videoconferenza
     */
    public function actionSendMail()
    {

        // trova la videoconferenza e gli utenti collegati
        $videoconfId = Yii::$app->request->get('id');

        $videoconference = Videoconf::findOne($videoconfId);
        if ($videoconference) {
            $collegati = $videoconference->getVideoconfUsersMms()->all();
            if (\is_array($collegati)) {
                foreach ($collegati as $u) {
                    $sent = EmailUtil::sendEmailPartecipant($videoconference, $u->user_id);
                }
            }
        }
    }

    public function init()
    {
        $this->setModelObj(new Videoconf());
        $this->setModelSearch(new VideoconfSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => Yii::t('amoscore', '{iconaTabella}'.Html::tag('p', Yii::t('amoscore', 'Table')),
                    [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
            /* 'list' => [
              'name' => 'list',
              'label' => Yii::t('amoscore', '{iconaLista}'.Html::tag('p',Yii::t('amoscore', 'List')), [
              'iconaLista' => AmosIcons::show('view-list')
              ]),
              'url' => '?currentView=list'
              ],
              'icon' => [
              'name' => 'icon',
              'label' => Yii::t('amoscore', '{iconaElenco}'.Html::tag('p',Yii::t('amoscore', 'Icons')), [
              'iconaElenco' => AmosIcons::show('grid')
              ]),
              'url' => '?currentView=icon'
              ],
              'map' => [
              'name' => 'map',
              'label' => Yii::t('amoscore', '{iconaMappa}'.Html::tag('p',Yii::t('amoscore', 'Map')), [
              'iconaMappa' => AmosIcons::show('map')
              ]),
              'url' => '?currentView=map'
              ],
              'calendar' => [
              'name' => 'calendar',
              'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
              //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
              'label' => Yii::t('amoscore', '{iconaCalendario}'.Html::tag('p',Yii::t('amoscore', 'Calendar')), [
              'iconaMappa' => AmosIcons::show('calendar')
              ]),
              'url' => '?currentView=calendar'
              ], */
        ]);

        parent::init();
    }

    public function actionTest()
    {
        //$videoconference = new \open20\amos\videoconference\models\Videoconf();
        $videoconference = \open20\amos\videoconference\models\Videoconf::findOne(1);
        // pr($videoconference->toArray(), 'videoconference');//exit;
        $users           = $videoconference->getVideoconfUsersMms()->all();
        // $users = $videoconference->getVideoconfUsers();

        foreach ($users as $u) {
            pr($u->toArray(), '$u relazione'); //exit;
            $userProfile = \open20\amos\admin\models\UserProfile::findOne($u->user_id);
            // pr($userProfile->toArray(), '$userProfile');//exit;
            $user        = $userProfile->getUser()->one();
            // pr($user->toArray(), '$user');//exit;
            $userEmail   = $userProfile->getUser()->one()->email;
            //  print "Email: $userEmail;<br />";
        }
        //pr($users->toArray(), '$users');exit;/****/
    }

    /**
     * Lists all Videoconf models.
     * @return mixed
     */
    public function actionIndex($layout = null)
    {
        Url::remember();

        //label pulsante "nuovo"
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosVideoconference::t('amosvideoconference', 'Nuova'),
        ];
        $filter = ArrayHelper::merge(Yii::$app->request->getQueryParams(), [
            'VideoconfSearch' => ['status' => [Videoconf::STATUS_FUTURE, Videoconf::STATUS_RUNNING]]
        ]);
        $this->setDataProvider($this->getModelSearch()->searchAll($filter));
        return parent::actionIndex();
    }

    /**
     * Displays a single Videoconference.
     * @param integer $id
     * @return mixed
     */
    public function actionMeet($id_room)
    {
        $record = Videoconf::findOne(['id_room_videoconference' => $id_room]);
        if (is_object($record) && $record->id) {
            $id    = $record->id;
            $model = $this->findModel($id);
        }
        return $this->render('meet', ['model' => $model]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionJsonVideoconfData($id)
    {
        $model = $this->findModel($id);

        $jitsiDomain           = Yii::$app->getModule('videoconference')->jitsiDomain ?: null;

        $loggedUser        = \Yii::$app->getUser()->identity;
        $loggedUserProfile = $loggedUser->getProfile();

        //pr($loggedUserProfile);
        $data = [
            "domain" => $jitsiDomain,
            "displayName" => $loggedUserProfile->nome." ".$loggedUserProfile->cognome,
            "roomName" => $model->id_room_videoconference,
            "titolo" => $model->title,
            "avatar" => $loggedUserProfile->getAvatarWebUrl()
        ];
        return json_encode($data);
    }

    /**
     * Displays a single Videoconf model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new Videoconf model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Videoconf;
        $moduleVideoconf = Yii::$app->getModule('videoconference');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            // Popolamento manuale di end_hour
            $dateTime = new \DateTime($model->start_hour);
            $dateTime->modify("+$moduleVideoconf->videoconferenceMinutesLength minutes");
            $model->end_hour = $dateTime->format('H:i:s'); // Formatta l'orario risultante

            VideoconfUtil::implodeStartEndDate($model);
            /*
            if(!VideoconfUtil::implodeStartEndDate($model)) {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', '#ErrorSameStartEndHour'));
                return $this->render('create', [
                    'model' => $model
                ]);
            }*/

            if ($model->validate()) {
                //creo una transazione in modo che se non salvasse correttamente i partecipanti, non si creerebbe un record video
                //$transaction = \Yii::$app->db->beginTransaction();

                $scope = VideoconfUtil::getScopeCommunity();
                if ($scope) {
                    $model->community_id = $scope;
                }

                // Conversione Datetime -> integer
                $explodedNotificationTime = explode(':', $model->notification_before_conference);
                $totalMinutes = $explodedNotificationTime[0] * 60 + $explodedNotificationTime[1];
                $model->notification_before_conference = $totalMinutes;

                if ($model->save()) {
                    //$transaction->commit();
                    return $this->redirect(['participants', 'id' => $model->id, 'redirectUrl' => '/videoconference/videoconf/update?id=' . $model->id]);

                } else {
                    //$transaction->rollBack();
                    $errors = implode('<br> ', VideoconfUtil::extractErrorsFlash($model->getErrors()));
                    Yii::$app->getSession()->addFlash('danger', $errors);
                    return $this->render('create', [
                        'model' => $model
                    ]);
                }
            } else {
                $errors = implode('<br> ', VideoconfUtil::extractErrorsFlash($model->getErrors()));
                Yii::$app->getSession()->addFlash('danger', $errors);
                return $this->render('create', [
                    'model' => $model
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * @param $id
     * @param $redirectUrl
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionParticipants($id, $redirectUrl = null){
        Url::remember();
        $this->model = $this->findModel($id);
        $this->loadPartecipanti($id = null);

        $modelSearch = new UserMeetingSearch();
        $dataProvider = $modelSearch->search(\Yii::$app->request->get());
        $dataProviderInvitationUsers = new ActiveDataProvider([
            'query' => $this->model->getUserProfileParticipants()
        ]);

        $arrayUsers = $this->model->getUserProfileParticipants()->asArray()->select('id')->all();
        $participants_user_profile_ids = [];
        foreach ($arrayUsers as $user_profile_id) {
            $participants_user_profile_ids  [] = $user_profile_id['id'];
        }
		
		
		

        $countInvitationUsers = $this->model->getUserProfileParticipants()->count();
        if (\Yii::$app->request->isPost && \Yii::$app->request->post('submit_participants')) {
            $this->model_partecipanti->videoconf_id = $this->model->id;
            //aggiorno i partecipanti della conferenza con quelli in post
            $users_ids_str = \Yii::$app->request->post('selected_users_ids');
            $user_ids = explode(',' , $users_ids_str);
			
			$users_deleted_ids_str = \Yii::$app->request->post('deleted_users_ids');
            $user_deleted_ids = explode(',' , $users_deleted_ids_str);
			
            $this->setPartecipanti($user_ids);

            //salvo i partecipanti e creo una notification (se non già inviata) per inviare successivamente la mail
            $saved_partecipanti = $this->model_partecipanti->saveUser2Videoconference($user_deleted_ids);

            if ($saved_partecipanti) {
                //Notifica i partecipanti ancora da avvisare
                $notifiche = $this->model->getNotification2Notify();
                $this->notifyUsers($notifiche);

                Yii::$app->getSession()->addFlash('success', "Inviti alla videoconferenza {$this->model->title} spediti con successo");
                return $this->redirect(['index']);
            }
        }

        return $this->render('participants', [
            'model' => $this->model,
            'modelSearch' => $modelSearch,
            'dataProvider' => $dataProvider,
            'dataProviderInvitationUsers' => $dataProviderInvitationUsers,
            'countInvitationUsers' => $countInvitationUsers,
            'participants_user_profile_ids' => $participants_user_profile_ids,
            'readOnly' => false,
            'redirectUrl' => $redirectUrl
        ]);
    }

    /**
     * Updates an existing Videoconf model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var $model Videoconf */
        $model = $this->findModel($id);
        $moduleVideoconf = Yii::$app->getModule('videoconference');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        VideoconfUtil::explodeStartEndDate($model);
        if ($model->load(Yii::$app->request->post())) {
            // Popolamento manuale di end_hour
            $dateTime = new \DateTime($model->start_hour);
            $dateTime->modify("+$moduleVideoconf->videoconferenceMinutesLength minutes");
            $model->end_hour = $dateTime->format('H:i:s'); // Formatta l'orario risultante

            VideoconfUtil::implodeStartEndDate($model);
            if($model->validate()) {
                //aggiorno i partecipanti della conferenza con quelli in post
                //$this->setPartecipanti(Yii::$app->request->post()[$this->model_partecipanti->getModelName()]['ids']);

                // Conversione Datetime -> integer
                $explodedNotificationTime = explode(':', $model->notification_before_conference);
                $totalMinutes = $explodedNotificationTime[0] * 60 + $explodedNotificationTime[1];
                $model->notification_before_conference = $totalMinutes;

                if ($model->save()) {
                    return $this->redirect(['index']);
                } else {
                    $errors = implode('<br> ', VideoconfUtil::extractErrorsFlash($model->getErrors()));
                    Yii::$app->getSession()->addFlash('danger', $errors);
                    return $this->render('update',
                        [
                            'model' => $model
                        ]);
                }
            } else {
                $errors = implode('<br> ', VideoconfUtil::extractErrorsFlash($model->getErrors()));
                Yii::$app->getSession()->addFlash('danger', $errors);
                return $this->render('update',
                    [
                        'model' => $model
                    ]);
            }
        } else {

            //$model->sheduledVideoconfCheckBox = !empty($model->begin_date_hour);
            return $this->render('update',
                [
                'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing Videoconf model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            //si può sostituire il  delete() con forceDelete() in caso di SOFT DELETE attiva
            //In caso di soft delete attiva e usando la funzione delete() non sarà bloccata
            //la cancellazione del record in presenza di foreign key quindi
            //il record sarà cancelleto comunque anche in presenza di tabelle collegate a questo record
            //e non saranno cancellate le dipendenze e non avremo nemmeno evidenza della loro presenza
            //In caso di soft delete attiva è consigliato modificare la funzione oppure utilizzare il forceDelete() che non andrà
            //mai a buon fine in caso di dipendenze presenti sul record da cancellare
            $model->delete();
            if (!empty($model->deleted_at)) {
                //cancello tutti partecipanti eventualmente presenti
                VideoconfUsersMm::deleteAll(['videoconf_id' => $id]);

                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item deleted'));
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not deleted because of dependency'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not found'));
        }
        return $this->redirect([Url::previous()]);
    }

    public function loadPartecipanti($videoconf_id)
    {
        $this->model_partecipanti               = new UserProfileForm();
        $this->model_partecipanti->videoconf_id = $videoconf_id;
        //$this->model_partecipanti->load(\Yii::$app->request->post());
        /* if ($this->model_partecipanti->load(\Yii::$app->request->post())) {
          if ($this->model_partecipanti->validate()) {
          //TODO
          //$model->saveFavorites();
          //return $this->redirect(['index']);
          }
          } */
        //carica i partecipanti (da DB) per la conferenza settata: popola $this->ids
        $this->model_partecipanti->loadUsers();

        $this->partecipanti = UserProfileForm::getAvailableUsers();
    }

    /**
     * @param $ids
     * @param bool $videoconf_id
     * Setta gli id degli user_profile ricevuti
     */
    public function setPartecipanti($ids, $videoconf_id = false)
    {
        if (!$this->model_partecipanti) {
            $this->model_partecipanti = new UserProfileForm();
        }
        $this->model_partecipanti->ids = $ids;
    }

    public function notifyPartecipantiProgrammed($videoconf_id)
    {
        $VideoconfRecord = Videoconf::findOne(['id' => $videoconf_id]);

        if (is_object($VideoconfRecord) && $VideoconfRecord->id) {
            $a = $VideoconfRecord->getNotification2Notify(/* $VideoconfRecord->id */);
        }
    }

    /**
     * @param $notificaRecord
     * @return bool
     * dato un record notification, crea un corrispondente record NotificationRead
     */
    protected function createNotificationRead($notificaRecord)
    {
        if (is_object($notificaRecord) && $notificaRecord->id) {
            $notiRead                  = new NotificationsRead();
            $notiRead->user_id         = $notificaRecord->user_id;
            $notiRead->notification_id = $notificaRecord->id;
            //pr($notiRead->toArray(), "SALVEREI notification READ");
            $notiRead->save(false);
        }

        return false;
    }

    /**
     * @param $notifiche Array Records notification
     * Dato un array contentente i record notification da notificare: invia la mail di notifica agli utenti settati nel record in questione
     */
    protected function notifyUsers($notifiche)
    {
        foreach ($notifiche as $k_n => $notificaRecord) {
            //pr($notificaRecord->toArray(), "---- notificare");

            $model_record = $notificaRecord->class_name;
            $id_record    = $notificaRecord->content_id;
            $id_user      = $notificaRecord->user_id;

            //$userProfileModel = AmosAdmin::instance()->createModel('UserProfile');
            $userRecord = User::findOne($id_user);

            //risalgo al record videoconferenza salvato nella notifica
            $videoconfRecord = $model_record::findOne($id_record);

            //invio email
            $send = EmailUtil::sendEmailPartecipant($videoconfRecord, $userRecord);

            //se la email è stata inviata, scrivo in notificationRead in modo da settare la notifica come 'letta'
            if ($send) {
                $this->createNotificationRead($notificaRecord);
            }
        }
    }

    /**
     * Create a video conference between to users
     *
     * @param $user_profile_id_sender
     * @param $user_profile_id_receiver
     * @return null|string
     */
    public function actionCreateVideoConferenceAjax($user_profile_id_sender, $user_profile_id_receiver)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $profile                     = UserProfile::find()->andWhere(['user_id' => $user_profile_id_sender])->one();
        $videoconfList               = Videoconf::find()
                ->innerJoinWith('videoconfUsersMms')
                ->andWhere(['videoconf.created_by' => $profile->user_id])
                ->andWhere(['status' => Videoconf::STATUS_RUNNING])
                ->andWhere(['videoconf_users_mm.invited_only' => 0])
                ->andWhere(['videoconf_users_mm.user_profile_id' => $user_profile_id_receiver])->all();

        $model = null;
        /** @var  $videoconf Videoconf */
        foreach ($videoconfList as $videoconf) {
            if ($videoconf->isVideoConferenceRoomActive() && count($videoconf->videoconfUsersParticipants) == 2) {
                $model = $videoconf;
            }
        }
        // if there is a videconference is between the two users  already active
        if (!empty($model)) {
            return $model->id_room_videoconference;
        } else {
            // create a new videoconference between two users
            $model        = new Videoconf();
            $model->title = "Videoconference ".$user_profile_id_sender."-".$user_profile_id_receiver;
            if ($model->save()) {
                $partecipante1                  = new VideoconfUsersMm();
                $partecipante1->videoconf_id    = $model->id;
                $partecipante1->user_profile_id = $user_profile_id_sender;

                $partecipante2                  = new VideoconfUsersMm();
                $partecipante2->videoconf_id    = $model->id;
                $partecipante2->user_profile_id = $user_profile_id_receiver;

                if ($partecipante1->save() && $partecipante2->save()) {
                    return $model->id_room_videoconference;
                }
            }
        }

        return null;
    }

    public function actionRegisterLoggedUser($idVideoconf) {
        $videoconf = Videoconf::findOne($idVideoconf);
        $userProfile = UserProfile::findOne(['user_id' => \Yii::$app->user->id]);
        $user_profile_id = $userProfile->id;

        //controllo esistenza record già presente
        $query = \open20\amos\videoconference\models\base\VideoconfUsersMm::find()
            ->andWhere(['videoconf_id' => $idVideoconf, 'user_profile_id' => $userProfile->id,
                'deleted_by' => null]);
        $exist = ($query->count() > 0);

        //se non esiste: salvo associazione
        if (!$exist || $query->andWhere(['invited_only' => 1])->one()) {
            $mm = new VideoconfUsersMm();
            $mm->videoconf_id = $idVideoconf;
            $mm->user_profile_id = $userProfile->id;
            $mm->invited_only = 0;
            if ($mm->save()) {
                if (isset($userProfile->user_id)) {
                    $permissions = \Yii::$app->authManager->getPermissionsByUser($userProfile->user_id);
                    if (!isset($permissions['JoinOwnVideoconference'])) {
                        $perm = \Yii::$app->authManager->getPermission('JoinOwnVideoconference');
                        if (!empty($perm)) {
                            \Yii::$app->authManager->assign($perm, $userProfile->user_id);
                        }
                    }
                }
                Yii::$app->getSession()->addFlash('success', AmosVideoconference::t('amosvideoconference', '#correctlyRegistered'));
            }

            //controllo se esiste già almeno una notifica per videoconferenza_id e user_id
            $has_notification = Videoconf::checkHasNotificationByVideoconfAndUser($idVideoconf,
                $userProfile->id);
            //se non esiste: lo creo in modo che venga poi inviata
            if ($has_notification === false) {
                Videoconf::createNotificationByVideoconfAnduser($idVideoconf,
                    $userProfile->id);
            }
        }
        if($videoconf->status == Videoconf::STATUS_FUTURE) {
            $this->redirect(['index']);
        } else {
            $this->redirect(['/videoconference/videoconf/meet', 'id_room' => $videoconf->id_room_videoconference]);
        }
    }

    /**
     * @param $layout
     * @return string
     */
    public function actionPastVideoconferences($layout = null) {
        Url::remember();
        $this->setUpLayout('list');
        //label pulsante "nuovo"

        $this->view->params['titleSection'] = AmosVideoconference::t('amosvideoconference', '#PAST_VIDEOCONFERENCE_TITLE');
        $this->view->params['labelLinkAll'] = AmosVideoconference::t('amosvideoconference', '#ALL_VIDEOCONFERENCES');
        $this->view->params['urlLinkAll'] = '/videoconference/videoconf/index';
        $filter = ArrayHelper::merge(Yii::$app->request->getQueryParams(), [
            'VideoconfSearch' => ['status' => Videoconf::STATUS_END]
        ]);
        $this->setDataProvider($this->getModelSearch()->searchAll($filter));
        return $this->render('index', [
            'model' => $this->getModelSearch(),
            'dataProvider' => $this->getDataProvider(),
            'currentView' => $this->getCurrentView()
        ]);
    }

    /**
     * @param $layout
     * @return string
     */
    public function actionAmministra($layout = null) {
        Url::remember();
        $this->setUpLayout('list');
        //label pulsante "nuovo"

        $this->view->params['titleSection'] = AmosVideoconference::t('amosvideoconference', '#VIDEOCONFERENCE_ADMINISTRATION');
        $this->view->params['labelLinkAll'] = AmosVideoconference::t('amosvideoconference', '#ALL_VIDEOCONFERENCES');
        $this->view->params['urlLinkAll'] = '/videoconference/videoconf/index';

        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        return $this->render('index', [
            'model' => $this->getModelSearch(),
            'dataProvider' => $this->getDataProvider(),
            'currentView' => $this->getCurrentView()
        ]);
    }

    public function actionCloseVideoconference($id) {
        /** @var $model Videoconf */
        $model = $this->findModel($id);

        $model->status = Videoconf::STATUS_END;
        $model->id_room_videoconference = null;
        $model->save(false);
        $this->redirect('index');
    }
}
