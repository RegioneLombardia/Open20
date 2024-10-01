<?php
namespace open20\amos\events\helpers\google_api;

use \Google_Model;

class Google_Service_Walletobjects_SmartTapMerchantData extends \Google_Collection
{
    protected $collection_key = 'authenticationKeys';
    protected $internal_gapi_mappings = array(
    );
    protected $authenticationKeysType = 'Google_Service_Walletobjects_AuthenticationKey';
    protected $authenticationKeysDataType = 'array';
    public $smartTapMerchantId;


    public function setAuthenticationKeys($authenticationKeys)
    {
        $this->authenticationKeys = $authenticationKeys;
    }
    public function getAuthenticationKeys()
    {
        return $this->authenticationKeys;
    }
    public function setSmartTapMerchantId($smartTapMerchantId)
    {
        $this->smartTapMerchantId = $smartTapMerchantId;
    }
    public function getSmartTapMerchantId()
    {
        return $this->smartTapMerchantId;
    }
}
