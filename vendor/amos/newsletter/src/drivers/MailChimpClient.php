<?php

namespace amos\newsletter\drivers;

use amos\newsletter\exceptions\MailUpException;
use amos\newsletter\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class MailChimpClient implements ServiceMailDriverInterface
{

        var $clientId;
        var $clientSecret;
        var $callbackUri;
        var $accessToken;
        var $refreshToken;

        //--------------------
        public $module;
        public $username;
        public $password;
        
        function getLogonEndpoint() {
            return $this->logonEndpoint;
        }

        function setLogonEndpoint($inLogonEndpoint) {
            $this->logonEndpoint = $inLogonEndpoint;
        }

        function getAuthorizationEndpoint() {
            return $this->authorizationEndpoint;
        }

        function setAuthorizationEndpoint($inAuthorizationEndpoint) {
            $this->authorizationEndpoint = $inAuthorizationEndpoint;
        }

        function getTokenEndpoint() {
            return $this->tokenEndpoint;
        }

        function setTokenEndpoint($inTokenEndpoint) {
            $this->tokenEndpoint = $inTokenEndpoint;
        }

        function getConsoleEndpoint() {
            return $this->consoleEndpoint;
        }

        function setConsoleEndpoint($inConsoleEndpoint) {
            $this->consoleEndpoint = $inConsoleEndpoint;
        }

        function getMailstatisticsEndpoint() {
            return $this->mailstatisticsEndpoint;
        }

        function setMailstatisticsEndpoint($inMailstatisticsEndpoint) {
            $this->mailstatisticsEndpoint = $inMailstatisticsEndpoint;
        }
        
        function getClientId() {
            return $this->clientId;
        }
        
        function setClientId($inClientId) {
            $this->clientId = $inClientId;
        }
        
        function getClientSecret() {
            return $this->clientSecret;
        }
        
        function setClientSecret($inClientSecret) {
            $this->clientSecret = $inClientSecret;
        }
        
        function getCallbackUri() {
            return $this->callbackUri;
        }
        
        function setCallbackUri($inCallbackUri) {
            $this->callbackUri = $inCallbackUri;
        }
        
        function getAccessToken() {
            return $this->accessToken;
        }
        
        function setAccessToken($inAccessToken) {
            $this->accessToken = $inAccessToken;
        }
        
        function getRefreshToken() {
            return $this->refreshToken;
        }
        
        function setRefreshToken($inRefreshToken) {
            $this->refreshToken = $inRefreshToken;
        }
        
        function __construct($inClientId = null, $inClientSecret = null, $inCallbackUri = null) {
            $this->logonEndpoint = "https://services.mailup.com/Authorization/OAuth/LogOn";
            $this->authorizationEndpoint = "https://login.mailchimp.com/oauth2/authorize";
            $this->tokenEndpoint = "https://login.mailchimp.com/oauth2/token";
            $this->consoleEndpoint = "https://services.mailup.com/API/v1.1/Rest/ConsoleService.svc";
            $this->mailstatisticsEndpoint = "https://services.mailup.com/API/v1.1/Rest/MailStatisticsService.svc";

            $this->module = \Yii::$app->getModule('newsletter');
            if($this->module){
                $this->clientId = !empty($inClientId) ? $inClientId : $this->module->client_id;
                $this->clientSecret = !empty($inClientSecret) ? $inClientSecret : $this->module->client_secret;
                $this->callbackUri = !empty($inCallbackUri) ? $inCallbackUri : $this->module->callback_uri;
                $this->username =  $this->module->username;
                $this->password = $this->module->password;
            }
            $this->loadToken();

        }


        function getLogOnUri() {
            $url = $this->getLogonEndpoint() . "?client_id=" . $this->getClientId() . "&client_secret=" . $this->getClientSecret() . "&response_type=code&redirect_uri=" . $this->getCallbackUri();
            return $url;
        }
        
        function logOn() {
            $url = $this->getLogOnUri();
            header("Location: " . $url);
        }
        function logOnWithPassword($username, $password) {
        	return $this->retreiveAccessToken($username, $password);
		}
        function retreiveAccessTokenWithCode($code) {
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
        
        function retreiveAccessToken() {
            $url = $this->getTokenEndpoint();
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, 1);

			$body = "grant_type=authorization_code&client_id=".$this->clientId."&client_secret=".$this->clientSecret;
		
			$headers = array();
			$headers["Content-length"] = strlen($body);
			$headers["Accept"] = "application/json";
			$headers["Authorization"] = "Basic ".base64_encode($this->clientId.":".$this->clientSecret);
			
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
        
        function refreshAccessToken() {
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
        
        function callMethod($url, $verb, $body = "", $contentType = "JSON", $refresh = true) {
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
        
        function loadToken() {
            if (isset($_COOKIE["access_token"])) $this->accessToken = $_COOKIE["access_token"];
            if (isset($_COOKIE["refresh_token"])) $this->refreshToken = $_COOKIE["refresh_token"];
        }
        
        function saveToken() {
            setcookie("access_token", $this->accessToken, time()+60*60*24*30);
            setcookie("refresh_token", $this->refreshToken, time()+60*60*24*30);
        }


        // --------------------- IMPLEMENTATION INTERFACE --------------------
        /**
         * @return mixed
         */
        public function oauth2Autentication(){
            return $this->logOnWithPassword($this->username, $this->password);
        }

        /**
         * @param $group_id
         * @param $params
         * @return mixed
         */
        public function getSubscribtionsToGroup($group_id, $params = []){
            $this->oauth2Autentication();
            $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/Group/$group_id/Recipients"], $params),'https');
            $result = $this->callMethod($url, "GET", null, "JSON");
            return json_decode($result);
        }

        /**
         * @param $group_id
         * @param $data
         * @return mixed
         *
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
         */
        public function subscribeToGroup($group_id, $data){
            $this->oauth2Autentication();
            $url = $this->getConsoleEndpoint() . "/Console/Group/$group_id/Recipients";
            $encode_data =  json_encode($data);

            return json_decode($this->callMethod($url, "POST", $encode_data, "JSON"));
        }


        /**
         * @return mixed
         */
        public function getLists(){
            $this->oauth2Autentication();
            $url = $this->getConsoleEndpoint() . "/Console/List";
            return json_decode($this->callMethod($url, "GET", null, "JSON"));
        }

        /**
         * @return mixed
         */
        public function getSubscribersByList($id, $params = []){
            $this->oauth2Autentication();
            $url = Url::toRoute(ArrayHelper::merge([$this->getConsoleEndpoint() . "/Console/List/$id/Recipients/Subscribed"], $params),'https');
            return json_decode($this->callMethod($url, "GET", null, "JSON"));
        }

        /**
         * @return mixed
         */
        public function getGroupsByList($id){
            $this->oauth2Autentication();
            $url = $this->getConsoleEndpoint() . "/Console/List/$id/Groups";
            return json_decode($this->callMethod($url, "GET", null, "JSON"));
        }

        //----------------------------------

        /** @return string */
        public function getMailServiceName(){
            return 'mailup';
        }

        /** @return  array ['field', 'field2]*/
        public function getListFields(){
            return [
                'IdList',
                'Name',
                'Company',
                'Description'
            ];
        }

        /** @return  array ['field' => 'label']*/
        public function getListLabels(){
            return [
                'IdList' => Module::t('amosnewsletter','Id'),
                'Name' => Module::t('amosnewsletter','Name'),
                'Company'=>  Module::t('amosnewsletter','Company'),
                'Description'=> Module::t('amosnewsletter','Description')
            ];
        }

        /** @return  array ['field', 'field2]*/
        public function getGroupFields(){
            return [
                'IdGroup',
                'Name',
            ];
        }

        /** @return  array ['field' => 'label']*/
        public function getGroupLabels(){
            return [
                'IdGroup' => Module::t('amosnewsletter','Id'),
                'Name' => Module::t('amosnewsletter','Name'),
                'Notes' => Module::t('amosnewsletter','Name'),
            ];
        }

        /** @return  array ['field', 'field2]*/
        public function getSubscriberFields(){
            return [
                'IdRecipient',
                'Name',
                'Email'
            ];
        }

        /** @return  array ['field' => 'label']*/
        public function getSubscriberLabels(){
            return [
                'IdRecipient' => Module::t('amosnewsletter','Id'),
                'Name' => Module::t('amosnewsletter','Name'),
                'Email' => Module::t('amosnewsletter','Email'),

            ];
        }

        /**
         * @return array
         */
        public function getPaginationConfigs(){
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
        public function buildQueryParams($queryParams, $searchParams){
            foreach(\Yii::$app->request->get('ServiceEmail') as $param => $value){
                $queryParams['filterby'] = isset($queryParams['filterby']) ? $queryParams['filterby']."&\"$param=='$value'\"" : "\"$param=='$value'\"";
            }
            return $queryParams;
        }

        public function getSearchField(){
            return [
                'Email'
            ];
        }

    public function checkIfSubscriberExist($list_id, $email) {
        
    }

    public function getDynamicFields($params) {
        
    }

    public function subscribeToList($list_id, $params) {
        
    }

}


?>
