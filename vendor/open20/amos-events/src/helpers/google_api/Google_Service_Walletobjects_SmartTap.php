<?php
namespace open20\amos\events\helpers\google_api;

use \Google_Model;

class Google_Service_Walletobjects_SmartTap extends \Google_Collection
{
    protected $collection_key = 'infos';
    protected $internal_gapi_mappings = array(
    );
    public $id;
    protected $infosType = 'Google_Service_Walletobjects_IssuerToUserInfo';
    protected $infosDataType = 'array';
    public $kind;
    public $merchantId;


    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setInfos($infos)
    {
        $this->infos = $infos;
    }
    public function getInfos()
    {
        return $this->infos;
    }
    public function setKind($kind)
    {
        $this->kind = $kind;
    }
    public function getKind()
    {
        return $this->kind;
    }
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }
    public function getMerchantId()
    {
        return $this->merchantId;
    }
}
