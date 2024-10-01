<?php
namespace open20\amos\events\helpers\google_api;

use open20\amos\events\helpers\google_api\Google_Service_Walletobjects_FieldSelector;
use \Google_Model;

class Google_Service_Walletobjects_TemplateItem extends Google_Model
{
    protected $internal_gapi_mappings = array(
    );
    protected $firstValueType = 'Google_Service_Walletobjects_FieldSelector';
    protected $firstValueDataType = '';
    public $predefinedItem;
    protected $secondValueType = 'Google_Service_Walletobjects_FieldSelector';
    protected $secondValueDataType = '';


    public function setFirstValue(Google_Service_Walletobjects_FieldSelector $firstValue)
    {
        $this->firstValue = $firstValue;
    }
    public function getFirstValue()
    {
        return $this->firstValue;
    }
    public function setPredefinedItem($predefinedItem)
    {
        $this->predefinedItem = $predefinedItem;
    }
    public function getPredefinedItem()
    {
        return $this->predefinedItem;
    }
    public function setSecondValue(Google_Service_Walletobjects_FieldSelector $secondValue)
    {
        $this->secondValue = $secondValue;
    }
    public function getSecondValue()
    {
        return $this->secondValue;
    }
}
