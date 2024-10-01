<?php
namespace open20\amos\events\helpers\google_api;

use \Google_Model;

class Google_Service_Walletobjects_Review extends Google_Model
{
    protected $internal_gapi_mappings = array(
    );
    public $comments;


    public function setComments($comments)
    {
        $this->comments = $comments;
    }
    public function getComments()
    {
        return $this->comments;
    }
}
