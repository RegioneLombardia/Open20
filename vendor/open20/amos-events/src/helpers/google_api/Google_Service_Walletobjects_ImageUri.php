<?php
namespace open20\amos\events\helpers\google_api;

use \Google_Model;
use open20\amos\events\helpers\google_api\Google_Service_Walletobjects_LocalizedString;

class Google_Service_Walletobjects_ImageUri extends Google_Model
{
    protected $internal_gapi_mappings = array(
    );
    public $description;
    protected $localizedDescriptionType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedDescriptionDataType = '';
    public $uri;


    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setLocalizedDescription(Google_Service_Walletobjects_LocalizedString $localizedDescription)
    {
        $this->localizedDescription = $localizedDescription;
    }
    public function getLocalizedDescription()
    {
        return $this->localizedDescription;
    }
    public function setUri($uri)
    {
        $this->uri = $uri;
    }
    public function getUri()
    {
        return $this->uri;
    }
}
