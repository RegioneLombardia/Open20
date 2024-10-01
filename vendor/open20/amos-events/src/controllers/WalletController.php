<?php

namespace open20\amos\events\controllers;

use open20\amos\attachments\models\File;
use open20\amos\core\controllers\AmosController;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\user\User;
use open20\amos\events\helpers\apple_api\ApplePayUtility;
use open20\amos\events\helpers\google_api\Services;
use open20\amos\events\helpers\google_api\VerticalType;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use yii\base\Exception;
use PKPass\PKPass;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class WalletController extends AmosController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'save-to-google',
                            'save-to-ios',
                            'register-device',
                            'updated-pass',
                            'log',
                            'test'
                        ],
                    ],


                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
                    'unregister-device' => ['delete']

                ]
            ]
        ]);
    }

    /**
     *
     */
    public function actionTest()
    {
        $invitation = EventInvitation::find()
            ->andWhere(['event_id' => 20])
            ->andWhere(['id' => 3442])->one();
        ApplePayUtility::sendNotification($invitation);
        DIE;
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * @param $id
     * @param null $iid
     * @param null $code
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionSaveToGoogle($id, $iid = null, $code = null)
    {
        $event = Event::findOne($id);
        $googleApiParams = \Yii::$app->params['googleApi'] ?: [];

        if (empty($googleApiParams)) {
            throw new ForbiddenHttpException('Google Wallet Not Enabled');
        }

        if ($code && $iid) {
            $invitation = EventInvitation::find()->andWhere(
                [
                    'event_id' => $id,
                    'id' => $iid,
                    'code' => $code
                ]
            )->one();
        } else {
            $invitation = EventInvitation::find()->andWhere(
                [
                    'event_id' => $id,
                    'user_id' => \Yii::$app->user->id
                ]
            )->one();
        }
        $services = new Services(['event_id' => $id]);
        $verticalType = VerticalType::EVENTTICKET;
        $classId = $services->getTicketClassId($event);
        $objectId = $services->getTicketObjectId($invitation);

        $skinnyJwt = $services->makeSkinnyJwt($verticalType, $classId, $objectId, $invitation->user_id);
//pr($skinnyJwt); die;
        if ($skinnyJwt != null) {
            return $this->redirect("https://pay.google.com/gp/v/save/{$skinnyJwt}");
        } else {
            return $this->redirect('/');
        }
    }


    /**
     * @param $id
     * @param null $iid
     * @param null $code
     * @throws ForbiddenHttpException
     */
    public function actionSaveToIos($id, $iid = null, $code = null)
    {
        $appleApiParams = \Yii::$app->params['appleApi'] ?: [];

        if (empty($appleApiParams)) {
            throw new ForbiddenHttpException('Apple Pass Not Enabled');
        }

        $pass = new PKPass(\Yii::getAlias($appleApiParams['p12CertificateFile']), $appleApiParams['p12CertificatePassword']);

        $teamIdentifier = \Yii::$app->params['appleApi']['teamIdentifier'];
        $passTypeIdentifier = \Yii::$app->params['appleApi']['passTypeIdentifier'];
//        pr($passTypeIdentifier);
//        pr($teamIdentifier);die;
        /**
         * @var $event Event
         */
        $event = Event::findOne(['id' => $id]);

        /**
         * @var $invitation EventInvitation
         */
        if ($code && $iid) {
            $invitation = EventInvitation::find()->andWhere(
                [
                    'event_id' => $id,
                    'id' => $iid,
                    'code' => $code
                ]
            )->one();
            $user = User::findOne($invitation->user_id);

        } else {
            $invitation = EventInvitation::find()->andWhere(
                [
                    'event_id' => $id,
                    'user_id' => \Yii::$app->user->id
                ]
            )->one();
            $user = User::findOne(\Yii::$app->user->id);
        }

//        pr($user->userProfile->nomeCognome); die;

        $qrCodeUrl = Url::base(true) . Url::toRoute(
                [
                    'register-participant',
                    'eid' => $invitation->event_id,
                    'pid' => (empty($invitation->user_id) ? '' : $invitation->user_id),
                    'iid' => $invitation->id
                ]);

        $startDate = new \DateTime($event->begin_date_hour);
        $date = $startDate->format("Y-m-d") . 'T' . $startDate->format("H:i:s") . '+01:00';
        // Pass content
        $data = [
            'description' => $event->title,
            'formatVersion' => 1,
            'organizationName' => \Yii::$app->name,
            'passTypeIdentifier' => $passTypeIdentifier,
            'serialNumber' => ApplePayUtility::getSerialnumber($invitation),
            'teamIdentifier' => $teamIdentifier,
            'eventTicket' => [
                'headerFields' => [
                    [
                        "dateStyle" => "PKDateStyleMedium",
                        "isRelative" => true,
                        "key" => "doors-open",
                        "label" => "Data",
                        "timeStyle" => "PKDateStyleShort",
                        "value" => $date
                    ],
                ],
                'primaryFields' => [
                    [
                        'key' => 'event-name',
                        'label' => 'Evento',
                        'value' => $event->title,
                    ],
                ],
                'secondaryFields' => [
                    [
                        "key" => "event-location",
                        "label" => $event->event_location,
                        'dataDetectorTypes' => ['PKDataDetectorTypeAddress'],
//                        "numberStyle" => "PKNumberStyleSpellOut",
                        "value" => $event->getFullLocationString()
                    ],


                ],
                "auxiliaryFields" => [
                    [
                        "key" => "event-passenger-name",
                        "label" => "Nome",
                        "value" => "" . $user->userProfile->nomeCognome . ""
                    ],


                ],

            ],
            /*'boardingPass' => [
                'primaryFields' => [
                    [
                        'key' => 'origin',
                        'label' => 'San Francisco',
                        'value' => 'SFO',
                    ],
                    [
                        'key' => 'destination',
                        'label' => 'London',
                        'value' => 'LHR',
                    ],
                ],
                'secondaryFields' => [
                    [
                        'key' => 'gate',
                        'label' => 'Gate',
                        'value' => 'F12',
                    ],
                    [
                        'key' => 'date',
                        'label' => 'Departure date',
                        'value' => '07/11/2012 10:22',
                    ],
                ],
                'backFields' => [
                    [
                        'key' => 'passenger-name',
                        'label' => 'Passenger',
                        'value' => 'John Appleseed',
                    ],
                ],
                'transitType' => 'PKTransitTypeAir',
            ],*/
            'barcode' => [
                'format' => 'PKBarcodeFormatQR',
                'message' => $qrCodeUrl,
                'messageEncoding' => 'iso-8859-1',
            ],
            'backgroundColor' => 'rgb(41,122,56)',
            'labelColor' => 'rgb(255,255,255)',
            'foregroundColor' => 'rgb(255,255,255)',
//            'logoText' => 'Ticket',
            'relevantDate' => $date,
            'authenticationToken' => '1234567812345678',
            'webServiceURL' => \Yii::$app->params['platform']['backendUrl'] . '/events/wallet'
        ];

        if ($event->seats_management) {
            $seat = $invitation->getAssignedSeat();
            if ($seat) {
                $stringSeat = $seat->getStringCoordinateSeat(2);
            }
            $data ['eventTicket']['auxiliaryFields'][] = [
                "key" => "seating-section",
                "label" => "Posto",
                "value" => $stringSeat
            ];
        }

        $pass->setData($data);

        // Add files to the pass package
       
        $pass->addFile(\Yii::$app->basePath . '/web/img/apple-wallet/icon.png');
        $pass->addFile(\Yii::$app->basePath . '/web/img/apple-wallet/logo.png');
//        $pass->addFile(\Yii::$app->basePath . '/web/img/apple-wallet/icon@2x.png');
//        $pass->addFile('images/icon@2x.png');
//        $pass->addFile('images/logo.png');

        // Create and output the pass
        if (!$pass->create(true)) {
            throw new ForbiddenHttpException($pass->getError());
        }

        die;
    }

    public function defineServiceAccount()
    {
        // Identifiers of Service account
        $googleApiParams = \Yii::$app->params['googleApi'] ?: [];
        define('SERVICE_ACCOUNT_EMAIL_ADDRESS', $googleApiParams['serviceAccountEmail']);
        define('SERVICE_ACCOUNT_FILE', \Yii::getAlias($googleApiParams['serviceAccountFile']));

        // Used by the Google Pay API for Passes Client library
        define('APPLICATION_NAME', \Yii::$app->name);

        // Identifier of Google Pay API for Passes Merchant Center
        define('ISSUER_ID', $googleApiParams['issuerId']);

        // List of origins for save to phone button. Used for JWT
        //// See https://developers.google.com/pay/passes/reference/s2w-reference
        $ORIGINS = array('https://pre-prod-sql8.stage.demotestwip.it');

        // Constants that are application agnostic. Used for JWT
        define('AUDIENCE', 'google');
        define('JWT_TYPE', 'savetoandroidpay');
        define('SCOPES', 'https://www.googleapis.com/auth/wallet_object.issuer');

        // Load the private key as String from service account file
        $jsonFile = file_get_contents(SERVICE_ACCOUNT_FILE);
        $credentialJson = json_decode($jsonFile, true);
        define('SERVICE_ACCOUNT_PRIVATE_KEY', $credentialJson['private_key']);
        return;
    }


    public function actionLog()
    {
        $myfile = fopen("register_apple_log.txt", "a") or die("Unable to open file!");
        fwrite($myfile, "######### " . date("d-m-Y H:i:s") . "\n");
        $txt = json_encode($_GET) . "\n";
        fwrite($myfile, $txt);
        $txt = json_encode($_POST) . "\n";
        fwrite($myfile, $txt);
        fwrite($myfile, "--------- \n");
        fclose($myfile);
    }

    /**
     * @param $iddevice
     * @param $passidentifier
     * @param $serialnumber
     */
    public function actionRegisterDevice()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if (\Yii::$app->request->isDelete) {
            return $this->unregisterDevice();
        }
        if (\Yii::$app->request->isPost) {
            return $this->registerDevice();
        }
        if (\Yii::$app->request->isGet) {
            return $this->serialNumbersIos();
        }
    }

    /**
     * @return string
     */
    public function registerDevice()
    {
        $serialnumber = $_GET['serialnumber'];
        $passidentifier = $_GET['passidentifier'];
        $iddevice = $_GET['iddevice'];
        $myfile = fopen("register_apple.txt", "a") or die("Unable to open file!");

        fwrite($myfile, "######### REGISTER " . date("d-m-Y H:i:s") . "\n");
        $txt = json_encode($_GET) . "\n";
        fwrite($myfile, $txt);
        $txt = json_encode($_POST) . "\n";
        fwrite($myfile, $txt);
        fclose($myfile);
//        DIE;
        $invitationId = str_replace('IDN_', '', $serialnumber);
        $invitation = EventInvitation::findOne($invitationId);
        if ($invitation) {
            if ($invitation->apple_wallet_device_id == $iddevice) {
                \Yii::$app->response->statusCode = 200;
            } else {
                \Yii::$app->response->statusCode = 201;
            }
            $invitation->apple_wallet_device_id = $iddevice;
            $invitation->save(false);
        } else {
            \Yii::$app->response->statusCode = 401;
        }
        return '';
    }

    /**
     * @return array|string
     */
    public function unregisterDevice()
    {
        $deviceLibraryIdentifier = $_GET['deviceLibraryIdentifier'];
        $serialNumber = $_GET['serialnumber'];
        $passTypeIdentifier = $_GET['passTypeIdentifier'];

        $myfile = fopen("register_apple.txt", "a") or die("Unable to open file!");
        fwrite($myfile, "######### UN-REGISTER " . date("d-m-Y H:i:s") . "\n");
        $txt = json_encode($_POST) . "\n";
        fwrite($myfile, $txt);
        fclose($myfile);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $invitationId = ApplePayUtility::getInvitationIdFromSerialNumber($serialNumber);
        $invitation = EventInvitation::findOne($invitationId);


        \Yii::$app->response->statusCode = 200;
        return '';

        if ($invitation) {
            $invitation->apple_wallet_device_id = null;
            $invitation->save(false);
            \Yii::$app->response->statusCode = 200;
            return '';
        } else {
            \Yii::$app->response->statusCode = 401;
            return '';
        }
    }

    /**
     * @param $deviceLibraryIdentifier
     * @param $passidentifier
     * @param $passesUpdatedSince
     * @return array
     */
    public function serialNumbersIos()
    {
        $deviceLibraryIdentifier = $_GET['deviceLibraryIdentifier'];
        $passesUpdatedSince = $_GET['passesUpdatedSince'];
        $passidentifier = $_GET['passidentifier'];

        $myfile = fopen("register_apple.txt", "a") or die("Unable to open file!");
        fwrite($myfile, "######### SERIAL NUMBERS " . date("d-m-Y H:i:s") . "\n");
        $txt = json_encode($_GET) . "\n";
        fwrite($myfile, $txt);
        $txt = json_encode($_POST) . "\n";
        fwrite($myfile, $txt);
        fclose($myfile);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $invitations = EventInvitation::find()
            ->andWhere(['apple_wallet_device_id' => $deviceLibraryIdentifier])->all();
        $lastUpdated = date('d/m/Y H:i:s');
        $serials = [];
        if (empty($invitations)) {
            \Yii::$app->response->statusCode = 404;
            return [];
        }
        foreach ($invitations as $invitation) {
            $serials [] = ApplePayUtility::getSerialnumber($invitation);
        }
        return [
            'lastUpdated' => $lastUpdated,
            'serialNumbers' => $serials
        ];
    }


    /**
     *
     */
    public function actionUpdatedPass()
    {
        $serialNumber = $_GET['serialnumber'];
        $invitationId = ApplePayUtility::getInvitationIdFromSerialNumber($serialNumber);
        $invitation = EventInvitation::findOne($invitationId);

        $myfile = fopen("register_apple.txt", "a") or die("Unable to open file!");
        fwrite($myfile, "######### UPDATED PASS " . date("d-m-Y H:i:s") . "\n");
        $txt = json_encode($_GET) . "\n";
        fwrite($myfile, $txt);
        $txt = json_encode($_POST) . "\n";

        if ($invitation) {
            $txt = $invitation->event_id . ' - ' . $invitation->event->title . "\n";
            fwrite($myfile, $txt);

            $txt = $invitation->user_id . ' - ' . $invitation->user->userProfile->nomeCognome . "\n";
            fwrite($myfile, $txt);

        }
        fwrite($myfile, $txt);
        fclose($myfile);
        return ApplePayUtility::createPkPass($invitation->event_id, $invitation);
    }


}