<?php
namespace open20\amos\events\helpers\google_api;

use open20\amos\events\helpers\google_api\Google_Service_Walletobjects_TranslatedString;
use \Google_Model;

class Google_Service_Walletobjects_Message extends \Google_Collection
{
    public $id;
    public $header;
    public $body;
    public $kind = 'walletobjects#walletObjectMessage';
    public $displayInterval; // TimeInterval
    public $messageType = 'text'; // MessageType
    public $localizedHeader; //LocalizedString
    public $localizedBody; // LocalizedString



}
