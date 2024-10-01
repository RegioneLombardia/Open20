<?php
namespace open20\amos\events\helpers\google_api;

use open20\amos\core\forms\editors\DateTime;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use \yii\helpers\Url;

/**
 * Copyleft 2019 Google Inc. All Rights Reserved.
 *
 * Proscriptiond under the Apache Proscription, Version 2.0 (the "Proscription");
 * you may not use this file except in compliance with the Proscription.
 * You may obtain a copy of the Proscription at
 *
 *     http://www.apache.org/licenses/PROSCRIPTION-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the Proscription is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the Proscription for the specific language governing permissions and
 * limitations under the Proscription.
 */

/**
 *
 * require_once 'Walletobjects.php'
 * contains the Google_service_.* definitions.
 * Is is the helper client library to implement REST definitions defined at:
 * https://developers.google.com/pay/passes/reference/v1/
 * Download newest at https://developers.google.com/pay/passes/support/libraries#libraries
 *
 **/
class ResourceDefinitions
{
    /******************************
     *
     *  Define an EventTicket Class
     *
     *  See https://developers.google.com/pay/passes/reference/v1/eventticketclass
     *
     * @param String $classId - The unique identifier for a class
     * @param EventInvitation $invitation
     * @return Google_Service_Walletobjects_EventTicketClass $payload - object representing EventTicketClass resource
     *
     *******************************/
    public static function makeEventTicketClassResource($classId, $event, $invitation)
    {
        $moduleEvents =\Yii::$app->getModule('events');
        $configTicketPasses = [];
        if($moduleEvents) {
            $configTicketPasses = $moduleEvents->ticketPasses;
        }
        // Define the resource representation of the Class
        // values should be from your DB/services; here we hardcode information
        // below defines an eventticket class. For more properties, check:
        //// https://developers.google.com/pay/passes/reference/v1/eventticketclass/insert
        //// https://developers.google.com/pay/passes/guides/pass-verticals/event-tickets/design

        // There is a client lib to help make the data structure. Newest client is on devsite:
        //// https://developers.google.com/pay/passes/support/libraries#libraries

        $localEventName = new Google_Service_Walletobjects_LocalizedString();
        $localEventNameTranslated = new Google_Service_Walletobjects_TranslatedString();
        $localEventNameTranslated->setLanguage(\Yii::$app->language);
        $localEventNameTranslated->setValue($event->title);
        $localEventName->setDefaultValue($localEventNameTranslated);

        $location = new Google_Service_Walletobjects_LatLongPoint();
        $location->setLatitude(37.424015499999996);
        $location->setLongitude(-122.09259560000001);
        $locations = array($location);

        $logoUri = new Google_Service_Walletobjects_ImageUri();
        $uri = \Yii::$app->params['platform']['backendUrl'] . '/img/img_default.jpg';
        if ($event->eventLogo) {
            $uri = \Yii::$app->params['platform']['backendUrl'] . '/' . $event->eventLogo->getWebUrl();
        }
        $logoUri->setUri($uri);
        $logoUri->setDescription($event->summary);
        $logoImage = new Google_Service_Walletobjects_Image();
        $logoImage->setSourceUri($logoUri);

        //LOGO TICKET
        $logoPiattaformaUri = new Google_Service_Walletobjects_ImageUri();
        $uri = \Yii::$app->params['platform']['backendUrl'] . \Yii::$app->params['logo'];;
        if (!empty($configTicketPasses['logo'])) {
            $uri = \Yii::$app->params['platform']['backendUrl']  . $configTicketPasses['logo'];
        }
        $logoPiattaformaUri->setUri($uri);
        $logoPiattaformaUri->setDescription($event->summary);
        $logoPiattaforma = new Google_Service_Walletobjects_Image();
        $logoPiattaforma->setSourceUri($logoPiattaformaUri);

        // LOCATION
        $localVenueName = new Google_Service_Walletobjects_LocalizedString();
        $localVenueNameTranslated = new Google_Service_Walletobjects_TranslatedString();
        $localVenueNameTranslated->setLanguage("it-IT");
        $event_location = $event->event_location ? $event->event_location : $event->getFullLocationString();
        $localVenueNameTranslated->setValue($event_location);
        $localVenueName->setDefaultValue($localVenueNameTranslated);

        // ADDRESS
        $fullLocation = $event->getFullLocationString();
        $localVenueAddress = new Google_Service_Walletobjects_LocalizedString();
        $localVenueAddressTranslated = new Google_Service_Walletobjects_TranslatedString();
        $localVenueAddressTranslated->setLanguage("it-IT");
        $localVenueAddressTranslated->setValue(($fullLocation ? $fullLocation : '-'));
        $localVenueAddress->setDefaultValue($localVenueAddressTranslated);

        //SET LOCATION AND ADDRESS
        $localEventVenue = new Google_Service_Walletobjects_EventVenue();
        $localEventVenue->setName($localVenueName);
        $localEventVenue->setAddress($localVenueAddress);

        $eventDateTime = new Google_Service_Walletobjects_EventDateTime();
        if ($event->begin_date_hour) {
            $startDate = new \DateTime($event->begin_date_hour);
            $eventDateTime->setStart($startDate->format("Y-m-d") . 'T' . $startDate->format("H:i:s.z") . 'Z');
//            $eventDateTime->setStart("2023-04-12T11:20:50.52Z");
        }
        if ($event->end_date_hour) {
            $endDate = new \DateTime($event->end_date_hour);
            $eventDateTime->setEnd($endDate->format("Y-m-d") . 'T' . $endDate->format("H:i:s.z") . 'Z');
//            $eventDateTime->setEnd("2023-04-12T16:20:50.52Z");
        }

        //LABEL GATE
        $labelGate = self::getLocalizedString("Settore");
        //LABEL SECTION
        $labelSection = self::getLocalizedString("Fila");


//        $textModulesData = new Google_Service_Walletobjects_TextModuleData();
//        $textModulesData->setBody("Baconrista events have pushed the limits since its founding.");
//        $textModulesData->setHeader("Custom Details");
//        $textModulesDatas = array($textModulesData);

//        $locationUri = new Google_Service_Walletobjects_Uri();
//        $locationUri->setUri("http://maps.google.com/");
//        $locationUri->setDescription("Nearby Locations");
//        $telephoneUri = new Google_Service_Walletobjects_Uri();
//        $telephoneUri->setUri("tel:6505555555");
//        $telephoneUri->setDescription("Call Customer Service");
//        $linksModuleData = new Google_Service_Walletobjects_LinksModuleData();
//        $linksModuleData->setUris($locationUri, $telephoneUri);

        // MEssage
        $message = new Google_Service_Walletobjects_Message();
        $message->body = "I dati del evento sono cambiati";
        $message->header = "Importante";
        $message->localizedBody = self::getLocalizedString("I dati del evento sono cambiati");
        $message->localizedHeader = self::getLocalizedString("Importante");
        $message->id = ISSUER_ID.'.'.$event->id.'_'.rand(100, 99999);

        $payload = new Google_Service_Walletobjects_EventTicketClass();
        //required properties
        $payload->setId($classId);
        $payload->setIssuerName("Openinnovation");
        $payload->setReviewStatus("underReview");
        $payload->setEventName($localEventName);
        // optional.  Check design and reference api to decide what's desirable
        $payload->setLocations($locations);
        $payload->setLogo($logoPiattaforma);
        $payload->setHeroImage($logoImage);
        $payload->setVenue($localEventVenue);
        $payload->setDateTime($eventDateTime);
        $payload->setCustomGateLabel($labelGate);
        $payload->setCustomSectionLabel($labelSection);
        $payload->setHexBackgroundColor($configTicketPasses['main-color']);
//        $payload->setMessages([$message]);


//		$payload->setTextModulesData($textModulesDatas);
//		$payload->setLinksModuleData($linksModuleData);

        return $payload;
    }

    /******************************
     *
     *  Define an EventTicket Object
     *
     * See https://developers.google.com/pay/passes/reference/v1/eventticketobject
     *
     * @param String $classId - The unique identifier for a class
     * @param String $objectId - The unique identifier for an object
     * @param EventInvitation $invitation
     * @return Google_Service_Walletobjects_EventTicketObject $payload - object representing EventTicketObject resource
     *
     *******************************/
    public static function makeEventTicketObjectResource($classId, $objectId, $invitation, $event)
    {
        // Define the resource representation of the Object
        // values should be from your DB/services; here we hardcode information
        // below defines an eventticket object. For more properties, check:
        //// https://developers.google.com/pay/passes/reference/v1/eventticketobject/insert
        //// https://developers.google.com/pay/passes/guides/pass-verticals/event-tickets/design
        ///
        ///

//        $seat = $invitation->getAssignedSeat();
        /** @var  $event Event */

        $url = \yii\helpers\Url::base(true) . Url::toRoute(
                [
                    'register-participant',
                    'eid' => $invitation->event_id,
                    'pid' => (empty($invitation->user_id) ? '' : $invitation->user_id),
                    'iid' => $invitation->id
                ]);

        // There is a client lib to help make the data structure. Newest client is on devsite:
        //// https://developers.google.com/pay/passes/support/libraries#libraries
        // Define Barcode
        $barcode = new Google_Service_Walletobjects_Barcode();
        $barcode->setType("qrCode");
        $barcode->setValue($url);
        $barcode->setAlternateText("");

        $seat = null;
        if ($event->seats_management) {
            $seat = $invitation->getAssignedSeat();
            if($seat) {
                $localSeatValue = self::getLocalizedString($seat->seat);
                $localSectionValue = self::getLocalizedString($seat->row);
                $localGateValue = self::getLocalizedString($seat->sector);
                $eventSeat = new Google_Service_Walletobjects_EventSeat();
                $eventSeat->setSeat($localSeatValue);
            //  $eventSeat->setRow($localRowValue);
                $eventSeat->setSection($localSectionValue);
                $eventSeat->setGate($localGateValue);
            }
        }


        // Define eventticket object
        $payload = new Google_Service_Walletobjects_EventTicketObject();
        // required fields
        $payload->setClassId($classId);
        $payload->setId($objectId);
        $payload->setState("active");
        // optional.  Check design and reference api to decide what's desirable
        $payload->setBarcode($barcode);
        if ($event->seats_management && $seat) {
            $payload->setSeatInfo($eventSeat);
        }
        $payload->setTicketHolderName($invitation->user->userProfile->nomeCognome);
        $payload->setTicketNumber($invitation->code);

        return $payload;
    }


    /**
     * @param $string
     * @param string $language
     * @return Google_Service_Walletobjects_LocalizedString
     */
    public static function getLocalizedString($string, $language = "en-GB")
    {
        $localizedString = new Google_Service_Walletobjects_LocalizedString();
        $translatedString = new Google_Service_Walletobjects_TranslatedString();
        $translatedString->setLanguage($language);
        $translatedString->setValue($string);
        $localizedString->setTranslatedValues($translatedString);
        $localizedString->setDefaultValue($translatedString);
        return $localizedString;
    }
}


?>

<!--http://pre-prod-sql8.stage.demotestwip.it/events/wallet/save-to-google?id=18-->