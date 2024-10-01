<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 02/11/2020
 * Time: 12:28
 */

namespace open20\amos\events\helpers\apple_api;

use open20\amos\admin\models\UserProfile;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use open20\amos\mobile\bridge\modules\v1\models\ChatMessages;
use open20\amos\mobile\bridge\modules\v1\models\User;

use Firebase\JWT\JWT;
//use Lcobucci\JWT\Signer\Key;
//use Lcobucci\JWT\Signer\Ecdsa\Sha256;
//use Lcobucci\JWT\Configuration;
//use Lcobucci\JWT\Builder;

use PKPass\PKPass;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

//use Lcobucci\JWT\Signer\Rsa\Sha256;



class ApplePayUtility
{

//    public static function sendPushNotification(){
//        \open20\amos\mobile\bridge\utility\MobileUtility::sendNotification($user_id, $title, $body, $content_type, $content_id);
//    }


    /**
     * @param $invitation EventInvitation
     * @param $title
     * @param $body
     * @param $content_type
     * @param $content_id
     * @return bool
     */
    public static function sendNotification($invitation)
    {
        $moduleMobileBridge = \Yii::$app->getModule('mobilebridge');
        if ($moduleMobileBridge) {
            $ret = false;
            \Yii::error("Notifica a {$invitation->user_id}");

            if ($invitation) {
                $token = $invitation->apple_wallet_device_id;
                //Se non ci sono tokens a cui mandare salto la procedura
                if (empty($token)) {
                    return false;
                }
                if (!empty($token)) {
//                    self::sendPushToApplev3($token);
                    \Yii::error("Risultato notifica ");
                }
                $ret = true;
            }
            return $ret;
        }
        return false;
    }

    /**
     * @param $token
     */
    public static function sendPushToApple($token){
        $appleApiParams = \Yii::$app->params['appleApi'] ?: [];

//        $deviceToken = '6e1326c0e4758b54332fab3b3cb2f9ed92e2cd332e9ba8182f49027054b64d29'; //  iPad 5s Gold prod
//        $passphrase = '';
//        $certificate = 'pushcert.pem';

         $passphrase = $appleApiParams['p12CertificatePassword'];
         $certificate = \Yii::getAlias($appleApiParams['p12CertificateFile']);
         $deviceToken = $token;
         $cafile = \Yii::getAlias('@backend/web/entrust_2048_ca.cer');
pr($certificate);
pr($passphrase);
pr($cafile);
pr('dddd');
        $err = '';
        $errstr = '';
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $certificate); // Pem file to generated // openssl pkcs12 -in pushcert.p12 -out pushcert.pem -nodes -clcerts // .p12 private key generated from Apple Developer Account
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        stream_context_set_option($ctx, 'ssl', 'cafile', $passphrase);
        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); // production
//        $fp = stream_socket_client('ssl://api.push.apple.com:442', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); // production
//        $fp = stream_socket_client('tls://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); // developement

        stream_set_blocking ($fp, 0);
        echo "<p>Connection Open</p>";
        if(!$fp){
            echo "<p>Failed to connect!<br />Error Number: " . $err . " <br />Code: " . $errstr . "</p>";
            return;
        } else {
            echo "<p>Sending notification!</p>";
        }


//        $body['aps'] = array('alert' => 'Oh hai!', 'badge' => 1, 'sound' => 'default');;
        $body = [];
        $body['aps'] = [];
        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
//        $msg = chr(0).chr(0).chr(32).$deviceToken.chr(0).chr(strlen($payload)).$payload;
//var_dump($msg)
        $result = fwrite($fp, $msg, strlen($msg));
        if (!$result)
            echo '<p>Message not delivered ' . PHP_EOL . '!</p>';
        else
            pr($result);
            echo '<p>Message successfully delivered ' . PHP_EOL . '!</p>';
        fclose($fp);
die;
    }

    public static function sendPushToApplev2($token)
    {
        /*$appleApiParams = \Yii::$app->params['appleApi'] ?: [];
        $passphrase = $appleApiParams['p12CertificatePassword'];
        $certificate = \Yii::getAlias($appleApiParams['p12CertificateFile']);
        $privateKeyFile = \Yii::getAlias($appleApiParams['privateKey']);

        $deviceToken = $token;
        $teamIdentifier = \Yii::$app->params['appleApi']['teamIdentifier'];
        $apns_topic = 'com.apple.Passbook';
        $payload = [];


//
//        $device_token = "device_token_here";
//        $apns_topic = 'to.dev.ios-application';
//        $p8file = $certificate; //"/home/dave/samauto/key_from_apple.p8";
//
//        $config = new Configuration();
//        $token = (string) $config->createBuilder()
//            ->issuedBy($teamIdentifier) // (iss claim) // teamId
//            ->issuedAt(time()) // time the token was issuedAt
//            ->withHeader('kid', "ABC123DEFG")
//            ->setKey('file://' . $p8file)
//            ->setSigner(new Sha256()) // APNs only supports the ES256 algorithm
//            ->getToken(); // get the generated token
//
//        $token = (new Builder())
//            ->issuedBy($teamIdentifier) // Configures the issuer (iss claim)
//        ->permittedFor('http://example.org') // Configures the audience (aud claim)
//        ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
//        ->issuedAt(time()) // Configures the time that the token was issue (iat claim)
//        ->canOnlyBeUsedAfter(time() + 60) // Configures the time that the token can be used (nbf claim)
//        ->expiresAt(time() + 3600) // Configures the expiration time of the token (exp claim)
//        ->withClaim('uid', 1) // Configures a new claim, called "uid"
//        ->getToken(); // Retrieves the generated token
//        pr(file_get_contents($privateKey),'dd');die;
        $signer = new Sha256();
        $privateKey = new Key(file_get_contents($privateKeyFile));
        $time = time();

        $token = (new Builder())
        ->issuedBy($teamIdentifier) // Configures the issuer (iss claim)
//        ->permittedFor('http://example.org') // Configures the audience (aud claim)
     //   ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
        ->issuedAt($time) // Configures the time that the token was issue (iat claim)
//        ->canOnlyBeUsedAfter($time + 60) // Configures the time that the token can be used (nbf claim)
        ->expiresAt($time + 3600) // Configures the expiration time of the token (exp claim)
        ->withHeader('kid',$passphrase) // Configures a new claim, called "uid"
        ->withHeader('alg', 'ES256')
//        ->withHeader('iss',$teamIdentifier)
//        ->withHeader('isat',$time)
        ->getToken($signer,  $privateKey); // Retrieves the generated token
//pr($token); die;

        pr(" ".$token. 'token jwt');
        pr(" ".$deviceToken,'devicetoken');

        $payloadArray['aps'] = [
//            'alert' => [
//                'title' => "Dev.To Push Notification", // title of the notification
//                'body' => "Visit SamAuto.nl for more awesome scripts", // content/body of the notification
//            ],
//            'sound' => 'default',
//            'badge' => 1
        ];

        $payloadJSON = json_encode($payloadArray);

//        $url = "https://api.sandbox.push.apple.com/3/device/$deviceToken";
        $url = "https://api.push.apple.com/3/device/$deviceToken";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJSON);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token","apns-topic: $apns_topic"]);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// On successful response you should get true in the response and a status code of 200
// A list of responses and status codes is available at
// https://developer.apple.com/library/ios/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Chapters/TheNotificationPayload.html#//apple_ref/doc/uid/TP40008194-CH107-SW1

        var_dump($response);
        var_dump($httpcode);
        die;
*/
    }

    /**
     * @param $token
     */
    public static function sendPushToApplev3($token){

        $appleApiParams = \Yii::$app->params['appleApi'] ?: [];

//        $deviceToken = '6e1326c0e4758b54332fab3b3cb2f9ed92e2cd332e9ba8182f49027054b64d29'; //  iPad 5s Gold prod
//        $passphrase = '';
//        $certificate = 'pushcert.pem';

        $passphrase = $appleApiParams['p12CertificatePassword'];
        $certificate = \Yii::getAlias($appleApiParams['p12CertificateFile']);
        $cafile = \Yii::getAlias('@backend/web/entrust_2048_ca.cer');
//        $certificatev1 = \Yii::getAlias('@common/uploads/apple/Pass_Type_ID_ pass.openinnovation.pem');
//        $certificatev1 = \Yii::getAlias('@common/uploads/apple/Push_ID_ web.openinnovation.push.pem');




        pr(file_get_contents($certificate));

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        stream_context_set_option($ctx, 'ssl', 'local_cert', $certificate);
//        stream_context_set_option($ctx, 'ssl', 'cafile', $cafile);

        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
        stream_set_blocking ($fp, 0);


        if (!$fp) {
            //ERROR
            echo "Failed to connect (stream_socket_client): $err $errstr";
        } else {

            // Create the payload body

            $body['aps'] = array(
//                'alert' => "Hello World",
//                'sound' => 'default',
//                'link_url' => "http://www.sgr.fr",
            );

            $payload = json_encode($body);

            //Enhanced Notification
            $msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;

            //SEND PUSH
            $result = fwrite($fp, $msg);
            //We can check if an error has been returned while we are sending, but we also need to check once more after we are done sending in case there was a delay with error response.
            self::checkAppleErrorResponse($fp);
            usleep(500000); //Pause for half a second. Note I tested this with up to a 5 minute pause, and the error message was still available to be retrieved
            self::checkAppleErrorResponse($fp);

            if (!$result)
                echo 'Message not delivered' . PHP_EOL;
            else {
                echo 'Message successfully delivered' . PHP_EOL;
                var_dump($result);
            }

            // Close the connection to the server
            fclose($fp);
            die;
        }


    }

    public static function checkAppleErrorResponse($fp) {

        //byte1=always 8, byte2=StatusCode, bytes3,4,5,6=identifier(rowID). Should return nothing if OK.
        $apple_error_response = fread($fp, 6);
        //NOTE: Make sure you set stream_set_blocking($fp, 0) or else fread will pause your script and wait forever when there is no response to be sent.
        if ($apple_error_response) {
            //unpack the error response (first byte 'command" should always be 8)
            $error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response);

            if ($error_response['status_code'] == '0') {
                $error_response['status_code'] = '0-No errors encountered';
            } else if ($error_response['status_code'] == '1') {
                $error_response['status_code'] = '1-Processing error';
            } else if ($error_response['status_code'] == '2') {
                $error_response['status_code'] = '2-Missing device token';
            } else if ($error_response['status_code'] == '3') {
                $error_response['status_code'] = '3-Missing topic';
            } else if ($error_response['status_code'] == '4') {
                $error_response['status_code'] = '4-Missing payload';
            } else if ($error_response['status_code'] == '5') {
                $error_response['status_code'] = '5-Invalid token size';
            } else if ($error_response['status_code'] == '6') {
                $error_response['status_code'] = '6-Invalid topic size';
            } else if ($error_response['status_code'] == '7') {
                $error_response['status_code'] = '7-Invalid payload size';
            } else if ($error_response['status_code'] == '8') {
                $error_response['status_code'] = '8-Invalid token';
            } else if ($error_response['status_code'] == '255') {
                $error_response['status_code'] = '255-None (unknown)';
            } else {
                $error_response['status_code'] = $error_response['status_code'] . '-Not listed';
            }

            echo '<br><b>+ + + + + + ERROR</b> Response Command:<b>' . $error_response['command'] . '</b>&nbsp;&nbsp;&nbsp;Identifier:<b>' . $error_response['identifier'] . '</b>&nbsp;&nbsp;&nbsp;Status:<b>' . $error_response['status_code'] . '</b><br>';
            echo 'Identifier is the rowID (index) in the database that caused the problem, and Apple will disconnect you from server. To continue sending Push Notifications, just start at the next rowID after this Identifier.<br>';
            return true;
        }
        return false;
    }



        /**
     * @param $invitation
     * @return string
     */
    public static function getSerialnumber($invitation){
        return 'IDN_' . $invitation->id;
    }

    /**
     * @param $serialnumber
     * @return mixed
     */
    public static function getInvitationIdFromSerialNumber($serialnumber){
        return $invitationId = str_replace('IDN_', '', $serialnumber);
    }


    /**
     * @param $event_id
     * @param null $invitation
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public static function createPkPass($event_id, $invitation = null){
        $appleApiParams = \Yii::$app->params['appleApi'] ?: [];

        if (empty($appleApiParams)) {
            throw new ForbiddenHttpException('Apple Pass Not Enabled');
        }

        $pass = new PKPass(\Yii::getAlias($appleApiParams['p12CertificateFile']), $appleApiParams['p12CertificatePassword']);
        $teamIdentifier = \Yii::$app->params['appleApi']['teamIdentifier'];
        $passTypeIdentifier = \Yii::$app->params['appleApi']['passTypeIdentifier'];


        /**
         * @var $event Event
         */
        $event = Event::findOne(['id' => $event_id]);

        /**
         * @var $invitation EventInvitation
         */
        if(empty($invitation)) {
            $invitation = EventInvitation::findOne(
                [
                    'event_id' => $event_id,
                    'user_id' => \Yii::$app->user->id
                ]
            );
        }
        $user = \open20\amos\core\user\User::findOne(\Yii::$app->user->id);

        $qrCodeUrl = Url::base(true) . Url::toRoute(
                [
                    'register-participant',
                    'eid' => $invitation->event_id,
                    'pid' => (empty($invitation->user_id) ? '' : $invitation->user_id),
                    'iid' => $invitation->id
                ]);

        $startDate = new \DateTime($event->begin_date_hour);
        $date = $startDate->format("Y-m-d") . 'T' . $startDate->format("H:i:s.z") . 'Z';
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
            'webServiceURL' => \Yii::$app->params['platform']['backendUrl'].'/events/wallet'
        ];

        if ($event->seats_management) {
            $seat = $invitation->getAssignedSeat();
            if($seat){
                $stringSeat =  $seat->getStringCoordinateSeat(2);
            }
            $data ['eventTicket']['auxiliaryFields'][] = [
                "key" => "seating-section",
                "label" => "Posto",
                "value" => $stringSeat
            ];
        }

        $pass->setData($data);

        // Add files to the pass package
        /** @var  $logo File */
        $logo = $event->eventLogo;
        $logo->getPath();
//        pr(\Yii::$app->basePath.'/web/img/logo.png');
//        pr(file_exists(\Yii::$app->basePath.'/web/img/logo.png'),'exist');die;
//        pr(\Yii::$app->basePath.'/backend/web/img/logo.png');die;
        $pass->addFile(\Yii::$app->basePath . '/web/img/apple-wallet/icon.png');
        $pass->addFile(\Yii::$app->basePath . '/web/img/apple-wallet/logo.png');
//        $pass->addFile(\Yii::$app->basePath . '/web/img/apple-wallet/icon@2x.png');
//        $pass->addFile('images/icon@2x.png');
//        $pass->addFile('images/logo.png');

        // Create and output the pass
        return $pass->create(true);

    }


}