<?php
namespace open20\amos\events\helpers\google_api;

use \Google_Model;
use open20\amos\events\helpers\google_api\Google_Service_Walletobjects_ImageUri;

class Google_Service_Walletobjects_Image extends Google_Model
{
    protected $internal_gapi_mappings = array(
    );
    public $kind;
    protected $sourceUriType = 'Google_Service_Walletobjects_ImageUri';
    protected $sourceUriDataType = '';


    public function setKind($kind)
    {
        $this->kind = $kind;
    }
    public function getKind()
    {
        return $this->kind;
    }
    public function setSourceUri(Google_Service_Walletobjects_ImageUri $sourceUri)
    {
        $this->sourceUri = $sourceUri;
    }
    public function getSourceUri()
    {
        return $this->sourceUri;
    }
}
