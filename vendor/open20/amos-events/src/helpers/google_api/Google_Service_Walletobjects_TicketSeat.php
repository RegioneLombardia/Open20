<?php
namespace open20\amos\events\helpers\google_api;

use open20\amos\events\helpers\google_api\Google_Service_Walletobjects_LocalizedString;
use \Google_Model;

class Google_Service_Walletobjects_TicketSeat extends Google_Model
{
    protected $internal_gapi_mappings = array(
    );
    public $coach;
    protected $customFareClassType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customFareClassDataType = '';
    public $fareClass;
    public $seat;
    protected $seatAssignmentType = 'Google_Service_Walletobjects_LocalizedString';
    protected $seatAssignmentDataType = '';


    public function setCoach($coach)
    {
        $this->coach = $coach;
    }
    public function getCoach()
    {
        return $this->coach;
    }
    public function setCustomFareClass(Google_Service_Walletobjects_LocalizedString $customFareClass)
    {
        $this->customFareClass = $customFareClass;
    }
    public function getCustomFareClass()
    {
        return $this->customFareClass;
    }
    public function setFareClass($fareClass)
    {
        $this->fareClass = $fareClass;
    }
    public function getFareClass()
    {
        return $this->fareClass;
    }
    public function setSeat($seat)
    {
        $this->seat = $seat;
    }
    public function getSeat()
    {
        return $this->seat;
    }
    public function setSeatAssignment(Google_Service_Walletobjects_LocalizedString $seatAssignment)
    {
        $this->seatAssignment = $seatAssignment;
    }
    public function getSeatAssignment()
    {
        return $this->seatAssignment;
    }
}
