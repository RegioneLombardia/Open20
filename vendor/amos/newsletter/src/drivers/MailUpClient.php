<?php

namespace amos\newsletter\drivers;

use amos\newsletter\exceptions\MailUpException;
use amos\newsletter\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class MailUpClient implements ServiceMailDriverInterface
{

    var $logonEndpoint;
    var $authorizationEndpoint;
    var $tokenEndpoint;
    var $consoleEndpoint;
    var $mailstatisticsEndpoint;
    var $transactionalEndpoint;

    var $clientId;
    var $clientSecret;
    var $callbackUri;
    var $accessToken;
    var $refreshToken;

    //--------------------
    public $module;
    public $username;
    public $password;

    function getLogonEndpoint()
    {
        return $this->logonEndpoint;
    }

    function setLogonEndpoint($inLogonEndpoint)
    {
        $this->logonEndpoint = $inLogonEndpoint;
    }

    function getAuthorizationEndpoint()
    {
        return $this->authorizationEndpoint;
    }

    function setAuthorizationEndpoint($inAuthorizationEndpoint)
    {
        $this->authorizationEndpoint = $inAuthorizationEndpoint;
    }

    function getTransactionalEndpoint()
    {
        return $this->transactionalEndpoint;
    }

    function setTransactionalEndpoint($endpoint)
    {
        $this->transactionalEndpoint = $endpoint;
    }

    function getTokenEndpoint()
    {
        return $this->tokenEndpoint;
    }

    function setTokenEndpoint($inTokenEndpoint)
    {
        $this->tokenEndpoint = $inTokenEndpoint;
    }

    function getConsoleEndpoint()
    {
        return $this->consoleEndpoint;
    }

    function setConsoleEndpoint($inConsoleEndpoint)
    {
        $this->consoleEndpoint = $inConsoleEndpoint;
    }

    function getMailstatisticsEndpoint()
    {
        return $this->mailstatisticsEndpoint;
    }

    function setMailstatisticsEndpoint($inMailstatisticsEndpoint)
    {
        $this->mailstatisticsEndpoint = $inMailstatisticsEndpoint;
    }

    function getClientId()
    {
        return $this->clientId;
    }

    function setClientId($inClientId)
    {
        $this->clientId = $inClientId;
    }

    function getClientSecret()
    {
        return $this->clientSecret;
    }

    function setClientSecret($inClientSecret)
    {
        $this->clientSecret = $inClientSecret;
    }

    function getCallbackUri()
    {
        return $this->callbackUri;
    }

    function setCallbackUri($inCallbackUri)
    {
        $this->callbackUri = $inCallbackUri;
    }

    function getAccessToken()
    {
        return $this->accessToken;
    }

    function setAccessToken($inAccessToken)
    {
        $this->accessToken = $inAccessToken;
    }

    function getRefreshToken()
    {
        return $this->refreshToken;
    }

    function setRefreshToken($inRefreshToken)
    {
        $this->refreshToken = $inRefreshToken;
    }

    function __construct($inClientId = null, $inClientSecret = null, $inCallbackUri = null)
    {
        $this->logonEndpoint = "https://services.mailup.com/Authorization/OAuth/LogOn";
        $this->authorizationEndpoint = "https://services.mailup.com/Authorization/OAuth/Authorization";
        $this->tokenEndpoint = "https://services.mailup.com/Authorization/OAuth/Token";
        $this->consoleEndpoint = "https://services.mailup.com/API/v1.1/Rest/ConsoleService.svc";
        $this->mailstatisticsEndpoint = "https://services.mailup.com/API/v1.1/Rest/MailStatisticsService.svc";
        $this->transactionalEndpoint = "https://send.mailup.com/API/v2.0";

        $this->module = \Yii::$app->getModule('newsletter');
        if ($this->module) {
            $this->clientId = !empty($inClientId) ? $inClientId : $this->module->client_id;
            $this->clientSecret = !empty($inClientSecret) ? $inClientSecret : $this->module->client_secret;
            $this->callbackUri = !empty($inCallbackUri) ? $inCallbackUri : $this->module->callback_uri;
            $this->username = $this->module->username;
            $this->password = $this->module->password;
        }
        $this->loadToken();

    }


    function getLogOnUri()
    {
        $url = $this->getLogonEndpoint() . "?client_id=" . $this->getClientId() . "&client_secret=" . $this->getClientSecret() . "&response_type=code&redirect_uri=" . $this->getCallbackUri();
        return $url;
    }

    function logOn()
    {
        $url = $this->getLogOnUri();
        header("Location: " . $url);
    }

    function logOnWithPassword($username, $password)
    {
        return $this->retreiveAccessToken($username, $password);
    }

    function retreiveAccessTokenWithCode($code)
    {
        $url = $this->getTokenEndpoint() . "?code=" . $code . "&grant_type=authorization_code";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($code != 200 && $code != 302) throw new MailUpException($code, "Authorization error");

        $result = json_decode($result);

        $this->accessToken = $result->access_token;
        $this->refreshToken = $result->refresh_token;

        $this->saveToken();

        return $this->accessToken;
    }

    function retreiveAccessToken($login, $password)
    {
        $url = $this->getTokenEndpoint();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, 1);

        $body = "grant_type=password&username=" . $login . "&password=" . urlencode($password) . "&client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret;
        $headers = array();
        $headers["Content-length"] = strlen($body);
        $headers["Accept"] = "application/json";
        $headers["Authorization"] = "Basic " . base64_encode($this->clientId . ":" . $this->clientSecret);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        $result = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($code != 200 && $code != 302) throw new MailUpException($code, "Authorization error");

        $result = json_decode($result);

        $this->accessToken = $result->access_token;
        $this->refreshToken = $result->refresh_token;

        $this->saveToken();

        return $this->accessToken;
    }

    function refreshAccessToken()
    {
        $url = $this->getTokenEndpoint();
        $body = "client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret . "&refresh_token=" . $this->refreshToken . "&grant_type=refresh_token";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded", "Content-length: " . strlen($body)));
        $result = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($code != 200 && $code != 302) throw new MailUpException($code, "Authorization error");

        $result = json_decode($result);

        $this->accessToken = $result->access_token;
        $this->refreshToken = $result->refresh_token;

        $this->saveToken();

        return $this->accessToken;
    }

    /**
     * @param $url
     * @param $verb
     * @param string $body
     * @param string $contentType
     * @param bool $refresh
     * @return mixed
     */
    function callMethod($url, $verb, $body = "", $contentType = "JSON", $refresh = true)
    {
        $temp = null;
        $cType = ($contentType == "XML" ? "application/xml" : "application/json");

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if ($verb == "POST") {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: " . $cType, "Content-length: " . strlen($body), "Accept: " . $cType, "Authorization: Bearer " . $this->accessToken));
        } else if ($verb == "PUT") {
            curl_setopt($curl, CURLOPT_PUT, 1);
            $temp = tmpfile();
            fwrite($temp, $body);
            fseek($temp, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: " . $cType, "Content-length: " . strlen($body), "Accept: " . $cType, "Authorization: Bearer " . $this->accessToken));
            curl_setopt($curl, CURLOPT_INFILE, $temp);
            curl_setopt($curl, CURLOPT_INFILESIZE, strlen($body));
        } else if ($verb == "DELETE") {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: " . $cType, "Content-length: 0", "Accept: " . $cType, "Authorization: Bearer " . $this->accessToken));
        } else {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: " . $cType, "Content-length: 0", "Accept: " . $cType, "Authorization: Bearer " . $this->accessToken));
        }

        $result = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($temp != null) fclose($temp);
        curl_close($curl);

//            if ($code == 401 && $refresh == true) {
//                $this->refreshAccessToken();
//                return $this->callMethod($url, $verb, $body, $contentType, false);
//            } else if ($code == 401 && $refresh == false) throw new MailUpException($code, "Authorization error");
//             else if ($code != 200 && $code != 302) throw new MailUpException($code, "Unknown error");

        return $result;
    }

    function loadToken()
    {
        if (isset($_COOKIE["access_token"])) $this->accessToken = $_COOKIE["access_token"];
        if (isset($_COOKIE["refresh_token"])) $this->refreshToken = $_COOKIE["refresh_token"];
    }

    function saveToken()
    {
        setcookie("access_token", $this->accessToken, time() + 60 * 60 * 24 * 30,'','',true,true);
        setcookie("refresh_token", $this->refreshToken, time() + 60 * 60 * 24 * 30,'','',true,true);
    }


    // --------------------- IMPLEMENTATION INTERFACE --------------------

    /**
     * @return mixed
     */
    public function oauth2Autentication()
    {
        return $this->logOnWithPassword($this->username, $this->password);
    }

    /**
     * @param $list_id
     * @param array $params
     * @return mixed
     */
    public function getTemplates($list_id, $params = [])
    {
        $this->oauth2Autentication();
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/List/$list_id/Templates"], $params), 'https');
        $result = $this->callMethod($url, "GET", null, "JSON");
        return json_decode($result);
    }


    /**
     * @param $list_id
     * @param array $params
     * @return [
     *    'CreationDate' => '',
     *    'Notes' =>  '',
     *    'Subject' => Test,
     *    'idList' => 33,
     *    'idMessage' => 4738,
     *    'Content' => <HTML>,
     *    'Fields' => [['Description' => 'nome', 'Id' => 1, 'Value' => ''], [...]],
     *    'UseDynamicField' => 1,
     *    'Attachments => []
     * ]
     */
    public function getEmail($list_id, $message_id, $params = [])
    {
        $this->oauth2Autentication();
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/List/$list_id/Email/$message_id"], $params), 'https');
        $result = $this->callMethod($url, "GET", null, "JSON");
        return json_decode($result);
    }

    /**
     * @param $list_id
     * @param $message_id
     * @param array $params
     */
    public function updateEmail($list_id, $message_id, $post, $params = [])
    {
        $this->oauth2Autentication();
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/List/$list_id/Email/$message_id"], $params), 'https');
        $result = $this->callMethod($url, "PUT", json_encode($post), "JSON");
        return json_decode($result);
    }

    /**
     * @param $list_id
     * @param $message_id
     * @param array $params
     */
    public function enableDisableDynamicFieldsEmail($list_id, $message_id, $enable, $params = [])
    {
        $this->oauth2Autentication();
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/List/$list_id/Email/$message_id/EnableDynamicFields/$enable"], $params), 'https');
        $result = $this->callMethod($url, "PUT", null, "JSON");
        return json_decode($result);
    }


    /**
     * @param $list_id
     * @param $template_id
     * @param array $params
     * @return mixed
     */
    public function sendEmail($list_id, $message_id, $params = [])
    {
        $this->oauth2Autentication();
        $params = $this->adjustPageNumber($params);
        //params = inGroups=12&notInGroups=89,90
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/List/$list_id/Email/$message_id/Send"], $params), 'https');
        $result = $this->callMethod($url, "POST", null, "JSON");
        return json_decode($result);
    }

    /**
     * @param $group_id
     * @param $message_id
     * @param array $params
     * @return mixed
     */
    public function sendEmailToGroup($group_id, $message_id, $params = [])
    {
        $this->oauth2Autentication();
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Group/$group_id/Email/$message_id/Send"], $params), 'https');
        $result = $this->callMethod($url, "POST", null, "JSON");
        return json_decode($result);
    }

    /**
     * @param $group_id
     * @param $message_id
     * @param array $params
     * @return mixed
     */
    public function sendSMSToGroup($group_id, $message_id)
    {
        $this->oauth2Autentication();
        $url = Url::toRoute($this->getConsoleEndpoint() . "/Console/Sms/Group/$group_id/Message/$message_id/Send", 'https');
        $result = $this->callMethod($url, "POST", null, "JSON");
        return json_decode($result);
    }

    /**
     * @param $message_id
     * @param $email
     * @return mixed
     */
    public function sendEmailToRecipient($message_id, $email)
    {
        $this->oauth2Autentication();
        $body = [
            'Email' => $email,
            'idMessage' => $message_id,
        ];

        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Email/Send"], []), 'https');
        $result = $this->callMethod($url, "POST", json_encode($body), "JSON");
        return json_decode($result);
    }

    /**
     * @param $list_id
     * @param array $params
     * @return mixed
     */
    public function getEmailList($list_id, $params = [])
    {
        $this->oauth2Autentication();
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/List/$list_id/Emails"], $params), 'https');
        $result = $this->callMethod($url, "GET", null, "JSON");
        return json_decode($result);
    }


    /**
     * @param $group_id
     * @param $params
     * @return mixed
     */
    public function getSubscribtionsToGroup($group_id, $params = [])
    {
        $this->oauth2Autentication();
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Group/$group_id/Recipients"], $params), 'https');
        $result = $this->callMethod($url, "GET", null, "JSON");
        return json_decode($result);
    }

    /**
     * @param $group_id
     * @param $data
     * @return mixed
     *
     * ```php
     * Example of $data
     * [
     *      [
     *      'Email' => 'test@test.it',
     *      'Fields' => [
     *              [
     *              'Description'=>'String description',
     *              'Id' => 1,
     *              'Value' => 'String value'
     *              ]
     *       ],
     *      'MobileNumber' =>'',
     *      'MobilePrefix'=> '',
     *      'Name' => 'Test'
     *      ]
     * ]
     * ```
     */
    public function subscribeToGroup($group_id, $data, $confirmEmail = false)
    {
        $this->oauth2Autentication();
        $url = $this->getConsoleEndpoint() . "/Console/Group/$group_id/Recipients";
        if ($confirmEmail) {
            $url .= '?ConfirmEmail=true';
        }
        $encode_data = json_encode($data);

        return json_decode($this->callMethod($url, "POST", $encode_data, "JSON"));
    }


    /**
     * @param $recipient_id
     * @param $data
     * @return mixed
     */
    public function updateRecipient($recipient_id, $data)
    {
        $this->oauth2Autentication();
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Recipient/Detail"], []), 'https');
        $encode_data = json_encode($data);
        return json_decode($this->callMethod($url, "PUT", $encode_data, "JSON"));


    }

    /**
     * @param $group_id
     * @param $data
     * @return mixed
     */
    public function assignRecipientToGroup($group_id, $data)
    {
        $this->oauth2Autentication();

        $url = $this->getConsoleEndpoint() . "/Console/Group/$group_id/Subscribe/{id_Recipient}?confirmSubscription=false";
        $encode_data = json_encode($data);

        return json_decode($this->callMethod($url, "POST", $encode_data, "JSON"));
    }

    /**
     * @param $params
     * @return mixed
     */
    public function importRecipientsToGroups($users, $group_id, $params = [], $signedForSMS = false){
        $forSMS = ($signedForSMS)? "/Sms": "";
        $this->oauth2Autentication();
        $url = $this->getConsoleEndpoint() . "/Console$forSMS/Group/$group_id/Recipients";
        $encode_data = json_encode($users);
        return json_decode($this->callMethod($url, "POST", $encode_data, "JSON"));
    }

    /**
     * @param $import_id
     * @param array $params
     * @return mixed
     */
     public function getImport($import_id, $params = []){
        $this->oauth2Autentication();
        $url = $this->getConsoleEndpoint() . "/Console/Import/$import_id";
        return json_decode($this->callMethod($url, "GET", [], "JSON"));
    }

    /**
     * @param $list_id
     * @param $email
     * @return null
     */
    public function checkIfSubscriberExist($list_id, $email)
    {
        $this->oauth2Autentication();

        $url = $this->getConsoleEndpoint() . "/Console/List/$list_id/Recipients/Subscribed?filterby=\"Email=='{$email}'";

        $decoded = json_decode($this->callMethod($url, "GET", null, "JSON"));
        if (!empty($decoded->TotalElementsCount) && $decoded->TotalElementsCount >= 1) {
            $item = $decoded->Items[0];
            return $item->idRecipient;
        }
        return null;

    }


    /**
     * @param $list_id
     * @param $data
     * @return mixed
     *
     * ```php
     * Example of $data
     * [
     *      [
     *      'Email' => 'test@test.it',
     *      'Fields' => [
     *              [
     *              'Description'=>'String description',
     *              'Id' => 1,
     *              'Value' => 'String value'
     *              ]
     *       ],
     *      'MobileNumber' =>'',
     *      'MobilePrefix'=> '',
     *      'Name' => 'Test'
     *      ]
     * ]
     * ```
     */
    public function subscribeToList($list_id, $data)
    {
        $this->oauth2Autentication();
        $url = $this->getConsoleEndpoint() . "/Console/List/$list_id/Recipients";
        $encode_data = json_encode($data);

        return json_decode($this->callMethod($url, "POST", $encode_data, "JSON"));
    }

    /**
     * @param $recipient_id
     * @param array $params
     * @return mixed
     */
    public function getRecipient($recipient_id, $params = [])
    {
        $this->oauth2Autentication();
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Recipients/$recipient_id"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }


    /**
     * @return mixed
     */
    public function getLists($params = [])
    {
        $this->oauth2Autentication();
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/List"], $params), 'https');

        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @return mixed
     */
    public function getSubscribersByList($id, $params = [])
    {
        $this->oauth2Autentication();
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/List/$id/Recipients/Subscribed"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @return mixed
     */
    public function getGroupsByList($id)
    {
        $this->oauth2Autentication();
        $url = $this->getConsoleEndpoint() . "/Console/List/$id/Groups";
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idList
     * @param $data ['Name' => 'NOME_GROUPPO', 'Notes' => 'MY_NOTES']
     * @return mixed
     */
    public function createGroup($idList, $data){
        $this->oauth2Autentication();
        $url = $this->getConsoleEndpoint() . "/Console/List/$idList/Group";
        $encode_data = json_encode($data);

        return json_decode($this->callMethod($url, "POST", $encode_data, "JSON"));
    }


    /**
     * @return mixed
     */
    public function getDynamicFields($params = [])
    {
        $this->oauth2Autentication();
        $defaultParams = ['PageSize' => 50, 'orderby' => 'Id asc'];
        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Recipient/DynamicFields"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }


    /**
     * @param $list_id
     * @param $message_id
     * @param $data
     * @return mixed
     */
    public function copyMessage($list_id, $message_id, $data)
    {
        $this->oauth2Autentication();
        $url = $this->getConsoleEndpoint() . "/Console/List/$list_id/Email/$message_id";
        $encode_data = json_encode($data);

        return json_decode($this->callMethod($url, "POST", $encode_data, "JSON"));
    }

    /**
     * @return mixed
     */
    public function getTags($list_id, $params = [])
    {
        $this->oauth2Autentication();
        $defaultParams = ['PageSize' => 50];
        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/List/$list_id/Tags"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }





    //------------ EMAIL STATISTICS -------------------------

    /**
     * @return mixed
     */
    public function getEmailStatistic($params = [])
    {
        $this->oauth2Autentication();
        $defaultParams = ['PageSize' => 50, 'orderby' => 'Id asc'];
        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Recipient/DynamicFields"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idList
     * @param $idMessage
     * @param array $params
     */
    public function getEmailSendHistory($list_id, $message_id, $params = [])
    {
        $this->oauth2Autentication();
        $defaultParams = ['PageSize' => 50, 'orderby' => 'Id asc'];
        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/List/$list_id/Email/$message_id/SendHistory"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idSending
     * @param array $params
     * @return mixed
     */
    public function getEmailRecipients($idMessage, $params = [])
    {
        $this->oauth2Autentication();
//        $defaultParams = ['PageSize' => 50, 'orderby' => 'Id asc'];
//        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/List/Recipients"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params
     * @return mixed
     */
    public function getStatisticOpened($idMessage, $onlyCount = true, $params = [])
    {
        $this->oauth2Autentication();
//        $defaultParams = ['PageSize' => 50, 'orderby' => 'Id asc'];
//        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        if ($onlyCount) {
            $type = 'Count';
        } else {
            $type = 'List';
        }
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/$type/Views"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params
     * @return mixed
     */
    public function getStatisticClicks($idMessage, $onlyCount = true, $params = [])
    {
        $this->oauth2Autentication();
//        $defaultParams = ['PageSize' => 50, 'orderby' => 'Id asc'];
//        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        if ($onlyCount) {
            $type = 'Count';
        } else {
            $type = 'List';
        }
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/$type/Clicks"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }


    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params
     * @return mixed
     */
    public function getStatisticClickedLinks($idMessage, $onlyCount = true, $params = [])
    {
        $this->oauth2Autentication();
//        $defaultParams = ['PageSize' => 50, 'orderby' => 'Id asc'];
//        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        if ($onlyCount) {
            $type = 'UrlClicks';
        } else {
            $type = 'UrlClickDetails';
        }
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/List/$type"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params
     * @return mixed
     */
    public function getStatisticMessagePages($idMessage)
    {
        $this->oauth2Autentication();
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/Pages"], []), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params example ['pageSize' => 5, 'pageNumber' => 2]
     * @return mixed
     */
    public function getStatisticMessageListViews($idMessage, $params = [])
    {
        $this->oauth2Autentication();
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/List/Views"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params example ['pageSize' => 5, 'pageNumber' => 2]
     * @return mixed
     */
    public function getStatisticMessageCountViews($idMessage, $params = [])
    {
        $this->oauth2Autentication();
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/Count/Views"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params example ['pageSize' => 5, 'pageNumber' => 2]
     * @return mixed
     */
    public function getStatisticMessageListClicks($idMessage, $params = [])
    {
        $this->oauth2Autentication();
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/List/Clicks"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }    
    
    /**
    * @param $idMessage
    * @param bool $onlyCount
    * @param array $params example ['pageSize' => 5, 'pageNumber' => 2]
    * @return mixed
    */
   public function getStatisticMessageCountClicks($idMessage, $params = [])
   {
       $this->oauth2Autentication();
       $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/Count/Clicks"], $params), 'https');
       return json_decode($this->callMethod($url, "GET", null, "JSON"));
   }

       /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params example ['pageSize' => 5, 'pageNumber' => 2]
     * @return mixed
     */
    public function getStatisticMessageListBounces($idMessage, $params = [])
    {
        $this->oauth2Autentication();
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/List/Bounces"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }    
    
    /**
    * @param $idMessage
    * @param bool $onlyCount
    * @param array $params example ['pageSize' => 5, 'pageNumber' => 2]
    * @return mixed
    */
   public function getStatisticMessageCountBounces($idMessage, $params = [])
   {
       $this->oauth2Autentication();
       $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/Count/Bounces"], $params), 'https');
       return json_decode($this->callMethod($url, "GET", null, "JSON"));
   }

    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params example ['pageSize' => 5, 'pageNumber' => 2]
     * @return mixed
     */
    public function getStatisticMessageCountRecipients($idMessage, $params = [])
    {
        $this->oauth2Autentication();
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/Count/Recipients"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params
     * @return mixed
     */
    public function getStatisticBounces($idMessage, $onlyCount = true, $params = [])
    {
        $this->oauth2Autentication();
//        $defaultParams = ['PageSize' => 50, 'orderby' => 'Id asc'];
//        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        if ($onlyCount) {
            $type = 'Count';
        } else {
            $type = 'List';
        }
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/$type/Bounces"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params
     * @return mixed
     */
    public function getStatisticUnsubscribed($idMessage, $onlyCount = true, $params = [])
    {
        $this->oauth2Autentication();
//        $defaultParams = ['PageSize' => 50, 'orderby' => 'Id asc'];
//        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        if ($onlyCount) {
            $type = 'Count';
        } else {
            $type = 'List';
        }
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Message/$idMessage/$type/Unsubscriptions"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }


    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params
     * @return mixed
     */
    public function getStatisticRecipientDeliveries($idRecipient, $idMessage = null, $onlyCount = true, $params = [])
    {
        $this->oauth2Autentication();
        if ($idMessage) {
            $defaultParams = ['filterby' => 'IdMessage==' . $idMessage];
            $params = ArrayHelper::merge($defaultParams, $params);
        }
        $params = $this->adjustPageNumber($params);
        if ($onlyCount) {
            $type = 'Count';
        } else {
            $type = 'List';
        }
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Recipient/$idRecipient/$type/Deliveries"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params
     * @return mixed
     */
    public function getStatisticRecipientOpened($idRecipient, $idMessage = null, $onlyCount = true, $params = [])
    {
        $this->oauth2Autentication();
        if ($idMessage) {
            $defaultParams = ['filterby' => 'IdMessage==' . $idMessage];
            $params = ArrayHelper::merge($defaultParams, $params);
        }
        $params = $this->adjustPageNumber($params);
        if ($onlyCount) {
            $type = 'Count';
        } else {
            $type = 'List';
        }
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Recipient/$idRecipient/$type/Views"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params
     * @return mixed
     */
    public function getStatisticRecipientClicks($idRecipient, $idMessage = null, $onlyCount = true, $params = [])
    {
        $this->oauth2Autentication();
        if ($idMessage) {
            $defaultParams = ['filterby' => 'IdMessage==' . $idMessage];
            $params = ArrayHelper::merge($defaultParams, $params);
        }
        $params = $this->adjustPageNumber($params);
        if ($onlyCount) {
            $type = 'Count';
        } else {
            $type = 'List';
        }
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Recipient/$idRecipient/$type/Clicks"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }


    /**
     * @param $idMessage
     * @param bool $onlyCount
     * @param array $params
     * @return mixed
     */
    public function getStatisticRecipientBounces($idRecipient, $idMessage = null, $onlyCount = true, $params = [])
    {
        $this->oauth2Autentication();
        if ($idMessage) {
            $defaultParams = ['filterby' => 'IdMessage==' . $idMessage];
            $params = ArrayHelper::merge($defaultParams, $params);
        }
        $params = $this->adjustPageNumber($params);
        if ($onlyCount) {
            $type = 'Count';
        } else {
            $type = 'List';
        }
        $url = Url::toRoute(ArrayHelper::merge([$this->getMailstatisticsEndpoint() . "/Recipient/$idRecipient/$type/Bounces"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    //----------------------------------

    /** @return string */
    public function getMailServiceName()
    {
        return 'mailup';
    }

    /** @return  array ['field', 'field2] */
    public function getListFields()
    {
        return [
            'IdList',
            'Name',
            'Company',
            'Description'
        ];
    }

    /** @return  array ['field' => 'label'] */
    public function getListLabels()
    {
        return [
            'IdList' => Module::t('amosnewsletter', 'Id'),
            'Name' => Module::t('amosnewsletter', 'Name'),
            'Company' => Module::t('amosnewsletter', 'Company'),
            'Description' => Module::t('amosnewsletter', 'Description')
        ];
    }

    /** @return  array ['field', 'field2] */
    public function getGroupFields()
    {
        return [
            'IdGroup',
            'Name',
        ];
    }

    /** @return  array ['field' => 'label'] */
    public function getGroupLabels()
    {
        return [
            'IdGroup' => Module::t('amosnewsletter', 'Id'),
            'Name' => Module::t('amosnewsletter', 'Name'),
            'Notes' => Module::t('amosnewsletter', 'Name'),
        ];
    }

    /** @return  array ['field', 'field2] */
    public function getSubscriberFields()
    {
        return [
            'IdRecipient',
            'Name',
            'Email'
        ];
    }

    /** @return  array ['field' => 'label'] */
    public function getSubscriberLabels()
    {
        return [
            'IdRecipient' => Module::t('amosnewsletter', 'Id'),
            'Name' => Module::t('amosnewsletter', 'Name'),
            'Email' => Module::t('amosnewsletter', 'Email'),

        ];
    }

    /**
     * @return array
     */
    public function getPaginationConfigs()
    {
        return [
            'pageParam' => 'PageNumber',
            'totalCount' => 'TotalElementsCount'
        ];
    }

    /**
     * @param $queryParams
     * @param $searchParams
     * @return mixed
     */
    public function buildQueryParams($queryParams, $searchParams)
    {
        foreach ($searchParams as $param => $value) {
            $queryParams['filterby'] = isset($queryParams['filterby']) ? $queryParams['filterby'] . "&\"$param=='$value'\"" : "\"$param=='$value'\"";
        }
        return $queryParams;
    }

    public function getSearchField()
    {
        return [
            'Email'
        ];
    }


    /**
     * @param $params
     */
    public function adjustPageNumber($params)
    {
        if (!empty($params['PageNumber'])) {
            $params['PageNumber'] = $params['PageNumber'] - 1;
        }
        return $params;
    }


    /**
     * @return array
     */
    public function getSmtpPlusCredential()
    {
        $module = \Yii::$app->getModule('newsletter');
        $credentials = [];
        if ($module) {
            $username = $module->SMTP_username;
            $password = $module->SMTP_password;
            $credentials = [
                "Username" => $username,
                "Secret" => $password
            ];
        }
        return $credentials;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function getOngoingSending( $params = []){
        $this->oauth2Autentication();
        $defaultParams = ['PageSize' => 50];
//        if($idSending){
//            $defaultParams['filterby'] = "\"IdMessage==$idSending\"";
//        }
        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Email/Sendings/Immediate"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $params
     * @return mixed
     */
    public function getWaitingSending($params = []){
        $defaultParams = ['PageSize' => 50];
        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Email/Sendings/Deferred"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $idSending
     * @param $params
     * @return mixed
     */
    public function getFirstAvailableSendingDate($idSending, $params){
        $defaultParams = ['PageSize' => 50];
        $params = ArrayHelper::merge($defaultParams, $params);
        $params = $this->adjustPageNumber($params);
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Email/Sending/{$idSending}/Deferred"], $params), 'https');
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }

    /**
     * @param $template_id
     * @param $subject
     * @param $content
     * @param $user
     */
    public function sendTransationalEmail($template_id, $subject, $content, $user)
    {
        $url = Url::toRoute(ArrayHelper::merge([$this->getTransactionalEndpoint() . "/messages/sendtemplate"], []), 'https');

        $credentials = self::getSmtpPlusCredential();
        $body = [
            'TemplateId' => $template_id,
            'Subject' => $subject,
            "From" => ["Name" => "Piattaforma eventi", "Email" => "michele.lafrancesca@open20.it"],
            "To" => [["Name" => $user['name'], "Email" => $user['email']]],
            "Cc" => [],
            "Bcc" => [],
            "ReplyTo" => null,
            "CharSet" => "utf-8",
            "ExtendedHeaders" => null,
            "Attachments" => null,
            "EmbeddedImages" => null,
            "XSmtpAPI" => [
                "DynamicFields" => [
                    ["N" => "content", "V" => $content],
                ],
            ],

        ];
        $body['User'] = $credentials;
        return json_decode($this->callMethod($url, "POST", json_encode($body), "JSON"));

    }

       /**
     * @param $idSending
     * @param $params
     * @return mixed
     */
    public function stopSending($idSending){
        $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Email/Sendings/{$idSending}"], []), 'https');
        return json_decode($this->callMethod($url, "DELETE", null, "JSON"));
    }

    /**
     * @param $list_id
     * @param $data
     * @return mixed
     */
    public function createSMS($list_id, $data)
    {
        $this->oauth2Autentication();

        $url = $this->getConsoleEndpoint() . "/Console/Sms/List/$list_id/Message";
        $encode_data = json_encode($data);
        return json_decode($this->callMethod($url, "POST", $encode_data, "JSON"));
    }

        /**
     * @param $list_id
     * @param $data
     * @return mixed
     */
    public function reportSMS($message_id)
    {
        $this->oauth2Autentication();
        $url = $this->getConsoleEndpoint() . "/Console/Sms/$message_id/Sendings/Report";
        return json_decode($this->callMethod($url, "GET", null, "JSON"));
    }



}


?>
