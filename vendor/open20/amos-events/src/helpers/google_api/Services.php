<?php

namespace open20\amos\events\helpers\google_api;

use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use yii\base\Component;

/**
 * Copyleft 2019 Google Inc. All Rights Reserved.
 *
 * Proscriptiond under the Apache Proscription, Version 2.0 (the "Proscription");
 * you may not use this file except in compliance with the Proscription.
 * You may obtain a copy of the Proscription at
 *
 *         http://www.apache.org/licenses/PROSCRIPTION-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the Proscription is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the Proscription for the specific language governing permissions and
 * limitations under the Proscription.
 */

/**
 *
 * 'Walletobjects.php'
 * contains the Google_service_.* definitions.
 * It is the helper client library to implement REST definitions defined at:
 * https://developers.google.com/pay/passes/reference/v1/
 * Download newest at https://developers.google.com/pay/passes/support/libraries#libraries
 *
 **/

/*******************************
 *
 *  These are services that you would expose to front end so they can generate save links or buttons.
 *
 *  Depending on your needs, you only need to implement 1 of the services.
 *
 *******************************/
class Services extends Component
{
    public $event_id;

    private $EXISTS_MESSAGE = "No changes will be made when saved by link.\nTo update info, use update() or patch().\nFor an example, see https://developers.google.com/pay/passes/guides/get-started/implementing-the-api/engage-through-google-pay#update-state\n";

    private $NOT_EXIST_MESSAGE = "Will be inserted when user saves by link/button for first time\n";



    public function init()
    {
        parent::init();
        $this->defineServiceAccount();
    }

    /*******************************
     *
     *  Output to explain various status codes from a get API call
     *
     * @param GenericJson getCallResponse - response from a get call
     * @param String idType - identifier of type of get call.  "object" or "class"
     * @param String id - unique identifier of Pass for given idType
     * @param String checkClassId - optional. ClassId to check for if objectId exists, and idType == 'object'
     * @return void
     *
     *******************************/
    private function handleGetCallStatusCode($getCallResponse, $idType, $id, $checkClassId)
    {
        if ($getCallResponse["code"] == 200) {  // Id resource exists for this issuer account
            printf("\n%sId: (%s) already exists. %s", $idType, $id, $this->EXISTS_MESSAGE);

            // for object get, do additional check
            if ($idType == "object") {
                // check if object's classId matches target classId
                $classIdOfObjectId = $getCallResponse["classReference"]["id"];
                if ($classIdOfObjectId != $checkClassId && $checkClassId != null) {
                    throw new \Exception(
                        sprintf(
                            ">>>> Exception:\nthe classId of inserted object is (%s). " .
                            "It does not match the target classId (%s). The saved object will not " .
                            "have the class properties you expect.",
                            $classIdOfObjectId,
                            $checkClassId
                        )
                    );
                }
            }
        } else {
            if ($getCallResponse["code"] == 404) {  // Id resource does not exist for this issuer account
                printf("\n%sId: (%s) does not exist. %s", $idType, $id, $this->NOT_EXIST_MESSAGE);
            } else {
                throw new \Exception(
                    sprintf(">>>> Exception:\nIssue with getting %s.\n%s", $idType, var_export($getCallResponse, true))
                );
            }
        }

        return;
    }

    /*******************************
     *
     *  Output to explain various status codes from a insert API call
     *
     * @param GenericJson getCallResponse - response from a get call
     * @param String idType - identifier of type of get call.  "object" or "class"
     * @param String id - unique identifier of Pass for given idType
     * @param String checkClassId - optional. ClassId to check for if objectId exists, and idType == 'object'
     * @param VerticalType verticalType - optional. VerticalType to fetch ClassId of existing objectId.
     * @return boolean
     *
     *******************************/
    private function handleInsertCallStatusCode($insertCallResponse, $idType, $id, $checkClassId, $verticalType)
    {
        if ($insertCallResponse["code"] == 200) {
            printf("\n%s id (%s) insertion success!\n", $idType, $id);
            return true;
        } else {
            if ($insertCallResponse["code"] == 409) {  // Id resource exists for this issuer account
                printf("\n%sId: (%s) already exists. %s", $idType, $id, $this->EXISTS_MESSAGE);

                // for object insert, do additional check
                if ($idType == "object") {
                    $restMethods = RestMethods::getInstance();
                    $objectResponse = null;

                    $objectResponse = $restMethods->getEventTicketObject($id);

                    // check if object's classId matches target classId
                    $classIdOfObjectId = $objectResponse["classReference"]["id"];
                    if ($classIdOfObjectId != $checkClassId && $checkClassId != null) {
                        throw new \Exception(
                            sprintf(
                                ">>>> Exception:\nthe classId of inserted object is (%s). " .
                                "It does not match the target classId (%s). The saved object will not " .
                                "have the class properties you expect.",
                                $classIdOfObjectId,
                                $checkClassId
                            )
                        );
                    }
                }
            } else {
                throw new \Exception(
                    sprintf(">>>> Exception:\n%s insert issue.\n%s", $idType, var_export($insertCallResponse, true))
                );
            }
        }

        return false;
    }

    /*******************************
     *
     *  Generates a signed "fat" JWT.
     *  No REST calls made.
     *
     *  Use fat JWT in JS web button.
     *  Fat JWT is too long to be used in Android intents.
     *  Possibly might break in redirects.
     *
     *  See https://developers.google.com/pay/passes/reference/v1/
     *
     * @param VerticalType $verticalType - define type of pass being generated
     * @param String $classId - The unique identifier for an class.
     * @param String $objectId - The unique identifier for an object.
     * @return String $signedJwt - a signed JWT
     *
     *******************************/
    public function makeFatJwt($verticalType, $classId, $objectId)
    {
        $signedJwt = null;
        $classResourcePayload = null;
        $objectResourcePayload = null;
        $classResponse = null;
        $objectResponse = null;
        $restMethods = RestMethods::getInstance();

        try {
            // get class and object definition as well as test if ids exist in backend
            // for a Fat JWT, the first time a user hits the save button, the class and object are inserted
            $classResourcePayload = ResourceDefinitions::makeEventTicketClassResource($classId);
            $objectResourcePayload = ResourceDefinitions::makeEventTicketObjectResource($classId, $objectId);
            $classResponse = $restMethods->getEventTicketClass($classId);
            $objectResponse = $restMethods->getEventTicketObject($objectId);

            // check response status. Check https://developers.google.com/pay/passes/reference/v1/statuscodes
            // check class get response. Will print out if class exists or not. Throws error if class resource is malformed.
            $this->handleGetCallStatusCode($classResponse, "class", $classId, null);

            // check object get response. Will print out if object exists or not. Throws error if object resource is malformed, or if existing $objectId's $classId does not match the expected $classId
            $this->handleGetCallStatusCode($objectResponse, "object", $objectId, $classId);

            // put into JSON Web Token (JWT) format for Google Pay API for Passes
            $googlePassJwt = new GpapJwt();

            $googlePassJwt->addEventTicketClass($classResourcePayload);
            $googlePassJwt->addEventTicketObject($objectResourcePayload);

            // sign JSON to make signed JWT
            $signedJwt = $googlePassJwt->generateSignedJwt();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        // return "fat" JWT. Try putting it into JS web button
        // note button needs to be rendered in local web server who's domain matches the ORIGINS
        // defined in the JWT. See https://developers.google.com/pay/passes/reference/s2w-reference
        return $signedJwt;
    }


    /*******************************
     *
     *  Generates a signed "object" JWT.
     *  1 REST call is made to pre-insert class.
     *
     *  This JWT can be used in JS web button.
     *  If this JWT only contains 1 object, usually isn't too long; can be used in Android intents/redirects.
     *
     *  See https://developers.google.com/pay/passes/reference/v1/
     *
     * @param VerticalType $verticalType - define type of pass being generated
     * @param String $classId - The unique identifier for an class.
     * @param String $objectId - The unique identifier for an object.
     * @return String $signedJwt - a signed JWT
     *
     *******************************/
    public function makeObjectJwt($verticalType, $classId, $objectId)
    {
        $signedJwt = null;
        $classResourcePayload = null;
        $objectResourcePayload = null;
        $classResponse = null;
        $objectResponse = null;
        $restMethods = RestMethods::getInstance();

        try {
            // get class and object definition as well as test if ids exist in backend
            // make authorized REST call to explicitly insert class into Google server.
            // if this is successful, you can check/update class definitions in Merchant Center GUI:
            // https://pay.google.com/gp/m/issuer/list
            $classResourcePayload = ResourceDefinitions::makeEventTicketClassResource($classId);
            $objectResourcePayload = ResourceDefinitions::makeEventTicketObjectResource($classId, $objectId);
            $classResponse = $restMethods->insertEventTicketClass($classResourcePayload);
            $objectResponse = $restMethods->getEventTicketObject($objectId);

            // continue based on response status.Check https://developers.google.com/pay/passes/reference/v1/statuscodes
            // check class insert response. Will print out if class insert succeeds or not. Throws error if class resource is malformed.
            $result = $this->handleInsertCallStatusCode($classResponse, "class", $classId, null, null);

            // check object get response. Will print out if object exists or not. Throws error if object resource is malformed, or if existing objectId's classId does not match the expected classId
            $this->handleGetCallStatusCode($objectResponse, "object", $objectId, $classId);

            // only need to add object resource definition in JWT because class was pre -inserted
            $googlePassJwt = new GpapJwt();

            $googlePassJwt->addEventTicketObject($objectResourcePayload);

            // sign JSON to make signed JWT
            $signedJwt = $googlePassJwt->generateSignedJwt();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        // return "object" JWT.Try putting it into save link.
        // See https://developers.google.com/pay/passes/guides/get-started/implementing-the-api/save-to-google-pay#add-link-to-email
        return $signedJwt;
    }

    /*******************************
     *
     *  Generates a signed "skinny" JWT.
     *  2 REST calls are made:
     *    x1 pre-insert one classes
     *    x1 pre-insert one object which uses previously inserted class
     *
     *  This JWT can be used in JS web button.
     *  This is the shortest type of JWT; recommended for Android intents/redirects.
     *
     *  See https://developers.google.com/pay/passes/reference/v1/
     *
     * @param VerticalType $verticalType - define type of pass being generated
     * @param String $classId - The unique identifier for an class.
     * @param String $objectId - The unique identifier for an object.
     * @return String $signedJwt - a signed JWT
     *
     *******************************/

    public function makeSkinnyJwt($verticalType, $classId, $objectId, $user_id = null)
    {
        $signedJwt = null;
        $classResourcePayload = null;
        $objectResourcePayload = null;
        $classResponse = null;
        $objectResponse = null;
        $restMethods = RestMethods::getInstance();
        $event = Event::findOne(['id' => $this->event_id]);

        if(empty($user_id)){
            $user_id = \Yii::$app->user->id;
        }
        $invitation = EventInvitation::findOne(
            [
                'event_id' => $this->event_id,
                'user_id' => $user_id
            ]
        );
        $isTicketClassCreated = !empty($event->googlepay_ticket_class_id) ?  true : false;
        $isTicketObjectCreated = !empty($invitation->googlepay_ticket_id) ?  true : false;


        if(!$invitation) {
            return null;
        }

        try {
            // get class and object definition as well as test if ids exist in backend
            // make authorized REST call to explicitly insert class and object into Google server.
            // if this is successful, you can check/update class definitions in Merchant Center GUI:
            // https://pay.google.com/gp/m/issuer/list
            $classResourcePayload = ResourceDefinitions::makeEventTicketClassResource($classId,$event, $invitation);
            $objectResourcePayload = ResourceDefinitions::makeEventTicketObjectResource($classId, $objectId, $invitation, $event);
            if(!$isTicketClassCreated) {
                $classResponse = $restMethods->insertEventTicketClass($classResourcePayload);
            }else {
                $classResponse = $restMethods->updateEventTicketClass($classId, $classResourcePayload);
            }

            if(!$isTicketObjectCreated) {
                $objectResponse = $restMethods->insertEventTicketobject($objectResourcePayload);
            }else {
                $objectResponse = $restMethods->updateEventTicketObject($objectId, $objectResourcePayload);
//                pr($objectResponse);
            }


            // continue based on insert response status. Check https://developers.google.com/pay/passes/reference/v1/statuscodes
            // check class insert response. Will print out if class insert succeeds or not. Throws error if class resource is malformed.
            $resultClassTicket = $this->handleInsertCallStatusCode($classResponse, "class", $classId, null, null);
            if($resultClassTicket && !$isTicketClassCreated){
                $event->googlepay_ticket_class_id = $classId;
                $event->save(false);
            }

            // check object insert response. Will print out if object insert succeeds or not. Throws error if object resource is malformed, or if existing objectId's classId does not match the expected classId
            $resultObjectTicket = $this->handleInsertCallStatusCode($objectResponse, "object", $objectId, $classId, $verticalType);
            if($resultObjectTicket && !$isTicketObjectCreated){
                $invitation->googlepay_ticket_id = $objectId;
                $invitation->save(false);
            }

            // put into JSON Web Token (JWT) format for Google Pay API for Passes
            // only need to add objectId in JWT because class and object were pre -inserted
            $googlePassJwt = new GpapJwt();

            $googlePassJwt->addEventTicketObject(array("id" => $objectId));

            // sign JSON to make signed JWT
            $signedJwt = $googlePassJwt->generateSignedJwt();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        // return "skinny" JWT. Try putting it into save link.
        // See https://developers.google.com/pay/passes/guides/get-started/implementing-the-api/save-to-google-pay#add-link-to-email
        return $signedJwt;
    }

    /**
     * @param $verticalType
     * @param $classId
     * @param $objectId
     * @return null|string
     */
    public function createUpdateSkinnyJwt($classId, $objectId, $user_id = null){
        $signedJwt = null;
        $classResourcePayload = null;
        $objectResourcePayload = null;
        $classResponse = null;
        $objectResponse = null;
        $restMethods = RestMethods::getInstance();
        $event = Event::findOne(['id' => $this->event_id]);
        if(empty($user_id)){
            $user_id = \Yii::$app->user->id;
        }
        $invitation = EventInvitation::findOne(
            [
                'event_id' => $this->event_id,
                'user_id' => $user_id
            ]
        );
        $isTicketClassCreated = !empty($event->googlepay_ticket_class_id) ?  true : false;

        try {
            $classResourcePayload = ResourceDefinitions::makeEventTicketClassResource($classId, $event, $invitation);
            if(!$isTicketClassCreated) {
                $classResponse = $restMethods->insertEventTicketClass($classResourcePayload);
            }else {
                $classResponse = $restMethods->updateEventTicketClass($classId, $classResourcePayload);
//                pr($classResponse);
            }

            $resultClassTicket = $this->handleInsertCallStatusCode($classResponse, "class", $classId, null, null);
            if($resultClassTicket && !$isTicketClassCreated){
                $event->googlepay_ticket_class_id = $classId;
                $event->save(false);
            }


            // put into JSON Web Token (JWT) format for Google Pay API for Passes
            // only need to add objectId in JWT because class and object were pre -inserted
            $googlePassJwt = new GpapJwt();
            $googlePassJwt->addEventTicketObject(array("id" => $objectId));

            // sign JSON to make signed JWT
            $signedJwt = $googlePassJwt->generateSignedJwt();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        // return "skinny" JWT. Try putting it into save link.
        // See https://developers.google.com/pay/passes/guides/get-started/implementing-the-api/save-to-google-pay#add-link-to-email
        return $signedJwt;
    }


    /**
     * @param $verticalType
     * @param $classId
     * @param $objectId
     * @return null|string
     */
    public function updateCreateTicketObject($verticalType, $classId, $objectId, $user_id = null){
        $signedJwt = null;
        $classResourcePayload = null;
        $objectResourcePayload = null;
        $classResponse = null;
        $objectResponse = null;
        $restMethods = RestMethods::getInstance();
        $event = Event::findOne(['id' => $this->event_id]);

        if(empty($user_id)){
            $user_id = \Yii::$app->user->id;
        }

        $invitation = EventInvitation::findOne(
            [
                'event_id' => $this->event_id,
                'user_id' => $user_id
            ]
        );
        $isTicketObjectCreated = !empty($invitation->googlepay_ticket_id) ?  true : false;

        try {
            $objectResourcePayload = ResourceDefinitions::makeEventTicketObjectResource($classId, $objectId, $invitation, $event);
            if(!$isTicketObjectCreated) {
                $objectResponse = $restMethods->insertEventTicketobject($objectResourcePayload);
            }else {
                $objectResponse = $restMethods->updateEventTicketObject($objectId, $objectResourcePayload);
            }

            $resultObjectTicket = $this->handleInsertCallStatusCode($objectResponse, "object",  $objectId, $classId, $verticalType);
            if($resultObjectTicket && !$isTicketObjectCreated){
                $event->googlepay_ticket_id = $objectId;
                $event->save(false);
            }


            // put into JSON Web Token (JWT) format for Google Pay API for Passes
            // only need to add objectId in JWT because class and object were pre -inserted
            $googlePassJwt = new GpapJwt();
            $googlePassJwt->addEventTicketObject(array("id" => $objectId));

            // sign JSON to make signed JWT
            $signedJwt = $googlePassJwt->generateSignedJwt();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        // return "skinny" JWT. Try putting it into save link.
        // See https://developers.google.com/pay/passes/guides/get-started/implementing-the-api/save-to-google-pay#add-link-to-email
        return $signedJwt;
    }
    /**
     *
     */
    public function defineServiceAccount(){
        // Identifiers of Service account
        $googleApiParams = \Yii::$app->params['googleApi'] ?: [];
        define('SERVICE_ACCOUNT_EMAIL_ADDRESS', $googleApiParams['serviceAccountEmail']);
        define('SERVICE_ACCOUNT_FILE',\Yii::getAlias($googleApiParams['serviceAccountFile']));

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
        define('SERVICE_ACCOUNT_PRIVATE_KEY',$credentialJson['private_key']);
    }

    /**
     * @param $id
     * @return string
     */
    public function generateTicketClassId($id){
        $vertical = "EVENTTICKET";
        $classUid = $vertical . "_CLASS_" . $id . uniqid('', true);
        $classId = sprintf("%s.%s", ISSUER_ID, $classUid);
        return $classId;
    }

    /**
     * @param $id
     * @return string
     */
    public function generateTicketObjectId($id){
        $vertical = "EVENTTICKET";
        $objectUid= $vertical."_OBJECT_".$id.uniqid('', true);
        $objectId = sprintf("%s.%s", ISSUER_ID, $objectUid);
        return $objectId;
    }

    /**
     * @param $event
     * @return string
     */
    public function getTicketClassId($event){
        if(!empty($event->googlepay_ticket_class_id)){
            $classId = $event->googlepay_ticket_class_id;
        } else {
            $classId = $this->generateTicketClassId($event->id);
        }
        return $classId;
    }

    /**
     * @param $invitation EventInvitation
     * @return string
     */
    public function getTicketObjectId($invitation){
        if(!empty($invitation->googlepay_ticket_id)){
            $classId = $invitation->googlepay_ticket_id;
        } else {
            $classId = $this->generateTicketClassId($invitation->event_id);
        }
        return $classId;
    }


}

?>