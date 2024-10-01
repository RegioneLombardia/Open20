<?php
namespace open20\amos\events\helpers\google_api;

use Google_Collection;

class Google_Service_Walletobjects_EventTicketObject extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array(
    );
    protected $appLinkDataType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_AppLinkData';
    protected $appLinkDataDataType = '';
    protected $barcodeType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_Barcode';
    protected $barcodeDataType = '';
    public $classId;
    protected $classReferenceType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_EventTicketClass';
    protected $classReferenceDataType = '';
    public $disableExpirationNotification;
    protected $faceValueType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_Money';
    protected $faceValueDataType = '';
    protected $groupingInfoType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_GroupingInfo';
    protected $groupingInfoDataType = '';
    public $hasLinkedDevice;
    public $hasUsers;
    public $id;
    protected $imageModulesDataType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $kind;
    public $linkedOfferIds;
    protected $linksModuleDataType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $locationsType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $messagesType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    protected $reservationInfoType = 'Google_Service_Walletobjects_EventReservationInfo';
    protected $reservationInfoDataType = '';
    protected $seatInfoType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_EventSeat';
    protected $seatInfoDataType = '';
    public $smartTapRedemptionValue;
    public $state;
    protected $textModulesDataType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    public $ticketHolderName;
    public $ticketNumber;
    protected $ticketTypeType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_LocalizedString';
    protected $ticketTypeDataType = '';
    protected $validTimeIntervalType = 'open20\\amos\\events\\helpers\\google_api\\Google_Service_Walletobjects_TimeInterval';
    protected $validTimeIntervalDataType = '';
    public $version;


    public function setAppLinkData(Google_Service_Walletobjects_AppLinkData $appLinkData)
    {
        $this->appLinkData = $appLinkData;
    }
    public function getAppLinkData()
    {
        return $this->appLinkData;
    }
    public function setBarcode(Google_Service_Walletobjects_Barcode $barcode)
    {
        $this->barcode = $barcode;
    }
    public function getBarcode()
    {
        return $this->barcode;
    }
    public function setClassId($classId)
    {
        $this->classId = $classId;
    }
    public function getClassId()
    {
        return $this->classId;
    }
    public function setClassReference(Google_Service_Walletobjects_EventTicketClass $classReference)
    {
        $this->classReference = $classReference;
    }
    public function getClassReference()
    {
        return $this->classReference;
    }
    public function setDisableExpirationNotification($disableExpirationNotification)
    {
        $this->disableExpirationNotification = $disableExpirationNotification;
    }
    public function getDisableExpirationNotification()
    {
        return $this->disableExpirationNotification;
    }
    public function setFaceValue(Google_Service_Walletobjects_Money $faceValue)
    {
        $this->faceValue = $faceValue;
    }
    public function getFaceValue()
    {
        return $this->faceValue;
    }
    public function setGroupingInfo(Google_Service_Walletobjects_GroupingInfo $groupingInfo)
    {
        $this->groupingInfo = $groupingInfo;
    }
    public function getGroupingInfo()
    {
        return $this->groupingInfo;
    }
    public function setHasLinkedDevice($hasLinkedDevice)
    {
        $this->hasLinkedDevice = $hasLinkedDevice;
    }
    public function getHasLinkedDevice()
    {
        return $this->hasLinkedDevice;
    }
    public function setHasUsers($hasUsers)
    {
        $this->hasUsers = $hasUsers;
    }
    public function getHasUsers()
    {
        return $this->hasUsers;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setImageModulesData($imageModulesData)
    {
        $this->imageModulesData = $imageModulesData;
    }
    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }
    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData)
    {
        $this->infoModuleData = $infoModuleData;
    }
    public function getInfoModuleData()
    {
        return $this->infoModuleData;
    }
    public function setKind($kind)
    {
        $this->kind = $kind;
    }
    public function getKind()
    {
        return $this->kind;
    }
    public function setLinkedOfferIds($linkedOfferIds)
    {
        $this->linkedOfferIds = $linkedOfferIds;
    }
    public function getLinkedOfferIds()
    {
        return $this->linkedOfferIds;
    }
    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData)
    {
        $this->linksModuleData = $linksModuleData;
    }
    public function getLinksModuleData()
    {
        return $this->linksModuleData;
    }
    public function setLocations($locations)
    {
        $this->locations = $locations;
    }
    public function getLocations()
    {
        return $this->locations;
    }
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }
    public function getMessages()
    {
        return $this->messages;
    }
    public function setReservationInfo(Google_Service_Walletobjects_EventReservationInfo $reservationInfo)
    {
        $this->reservationInfo = $reservationInfo;
    }
    public function getReservationInfo()
    {
        return $this->reservationInfo;
    }
    public function setSeatInfo(Google_Service_Walletobjects_EventSeat $seatInfo)
    {
        $this->seatInfo = $seatInfo;
    }
    public function getSeatInfo()
    {
        return $this->seatInfo;
    }
    public function setSmartTapRedemptionValue($smartTapRedemptionValue)
    {
        $this->smartTapRedemptionValue = $smartTapRedemptionValue;
    }
    public function getSmartTapRedemptionValue()
    {
        return $this->smartTapRedemptionValue;
    }
    public function setState($state)
    {
        $this->state = $state;
    }
    public function getState()
    {
        return $this->state;
    }
    public function setTextModulesData($textModulesData)
    {
        $this->textModulesData = $textModulesData;
    }
    public function getTextModulesData()
    {
        return $this->textModulesData;
    }
    public function setTicketHolderName($ticketHolderName)
    {
        $this->ticketHolderName = $ticketHolderName;
    }
    public function getTicketHolderName()
    {
        return $this->ticketHolderName;
    }
    public function setTicketNumber($ticketNumber)
    {
        $this->ticketNumber = $ticketNumber;
    }
    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }
    public function setTicketType(Google_Service_Walletobjects_LocalizedString $ticketType)
    {
        $this->ticketType = $ticketType;
    }
    public function getTicketType()
    {
        return $this->ticketType;
    }
    public function setValidTimeInterval(Google_Service_Walletobjects_TimeInterval $validTimeInterval)
    {
        $this->validTimeInterval = $validTimeInterval;
    }
    public function getValidTimeInterval()
    {
        return $this->validTimeInterval;
    }
    public function setVersion($version)
    {
        $this->version = $version;
    }
    public function getVersion()
    {
        return $this->version;
    }
}
