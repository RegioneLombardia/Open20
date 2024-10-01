<?php
namespace open20\amos\events\helpers\google_api;

use open20\amos\events\helpers\google_api\Google_Service_Walletobjects_TranslatedString;
use \Google_Model;

class Google_Service_Walletobjects_LocalizedString extends \Google_Collection
{
    protected $collection_key = 'translatedValues';
    protected $internal_gapi_mappings = array(
    );
    protected $defaultValueType = 'Google_Service_Walletobjects_TranslatedString';
    protected $defaultValueDataType = '';
    public $kind;
    protected $translatedValuesType = 'Google_Service_Walletobjects_TranslatedString';
    protected $translatedValuesDataType = 'array';


    public function setDefaultValue(Google_Service_Walletobjects_TranslatedString $defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
    public function setKind($kind)
    {
        $this->kind = $kind;
    }
    public function getKind()
    {
        return $this->kind;
    }
    public function setTranslatedValues($translatedValues)
    {
        $this->translatedValues = $translatedValues;
    }
    public function getTranslatedValues()
    {
        return $this->translatedValues;
    }
}
