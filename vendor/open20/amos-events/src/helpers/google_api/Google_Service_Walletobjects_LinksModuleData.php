<?php
namespace open20\amos\events\helpers\google_api;

use Google_Model;
use Google_Collection;

class Google_Service_Walletobjects_LinksModuleData extends Google_Collection
{
    protected $collection_key = 'uris';
    protected $internal_gapi_mappings = array(
    );
    protected $urisType = 'Google_Service_Walletobjects_Uri';
    protected $urisDataType = 'array';


    public function setUris($uris)
    {
        $this->uris = $uris;
    }
    public function getUris()
    {
        return $this->uris;
    }
}
