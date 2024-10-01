<?php
namespace open20\amos\events\helpers\google_api;

use open20\amos\events\helpers\google_api\Google_Service_Walletobjects_LocalizedString;
use open20\amos\events\helpers\google_api\Google_Service_Walletobjects_Money;
use \Google_Model;

class Google_Service_Walletobjects_TicketCost extends Google_Model
{
    protected $internal_gapi_mappings = array(
    );
    protected $discountMessageType = 'Google_Service_Walletobjects_LocalizedString';
    protected $discountMessageDataType = '';
    protected $faceValueType = 'Google_Service_Walletobjects_Money';
    protected $faceValueDataType = '';
    protected $purchasePriceType = 'Google_Service_Walletobjects_Money';
    protected $purchasePriceDataType = '';


    public function setDiscountMessage(Google_Service_Walletobjects_LocalizedString $discountMessage)
    {
        $this->discountMessage = $discountMessage;
    }
    public function getDiscountMessage()
    {
        return $this->discountMessage;
    }
    public function setFaceValue(Google_Service_Walletobjects_Money $faceValue)
    {
        $this->faceValue = $faceValue;
    }
    public function getFaceValue()
    {
        return $this->faceValue;
    }
    public function setPurchasePrice(Google_Service_Walletobjects_Money $purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;
    }
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }
}
