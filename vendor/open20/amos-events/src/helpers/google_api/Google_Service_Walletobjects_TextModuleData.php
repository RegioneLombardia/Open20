<?php
namespace open20\amos\events\helpers\google_api;

use open20\amos\events\helpers\google_api\Google_Service_Walletobjects_LocalizedString;
use \Google_Model;

class Google_Service_Walletobjects_TextModuleData extends Google_Model
{
    protected $internal_gapi_mappings = array(
    );
    public $body;
    public $header;
    public $id;
    protected $localizedBodyType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedBodyDataType = '';
    protected $localizedHeaderType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedHeaderDataType = '';


    public function setBody($body)
    {
        $this->body = $body;
    }
    public function getBody()
    {
        return $this->body;
    }
    public function setHeader($header)
    {
        $this->header = $header;
    }
    public function getHeader()
    {
        return $this->header;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setLocalizedBody(Google_Service_Walletobjects_LocalizedString $localizedBody)
    {
        $this->localizedBody = $localizedBody;
    }
    public function getLocalizedBody()
    {
        return $this->localizedBody;
    }
    public function setLocalizedHeader(Google_Service_Walletobjects_LocalizedString $localizedHeader)
    {
        $this->localizedHeader = $localizedHeader;
    }
    public function getLocalizedHeader()
    {
        return $this->localizedHeader;
    }
}
