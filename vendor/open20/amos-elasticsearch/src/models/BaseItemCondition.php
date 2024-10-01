<?php
namespace open20\elasticsearch\models;


abstract class BaseItemCondition extends \yii\base\BaseObject implements InterfaceItemCondition
{    
    private $analyzer = "";
    private $fields = [];
    
    public function getAnalyzer() 
    {
        return $this->analyzer;
    }

    public function setAnalyzer($analyzer)
    {
        $this->analyzer = $analyzer;
    }
    
    public function getFields() 
    {
        return $this->fields;
    }

    public function setFields($fields) 
    {
        $this->fields = $fields;
    }


    protected function escapeElasticSearchReservedChars($string) 
    {
        $regex = "/[\\+\\-\\=\\&\\|\\!\\(\\)\\{\\}\\[\\]\\^\\\"\\~\\<\\>\\:\\\\\\/]/";
        $string = preg_replace_callback ($regex, 
            function ($matches) { 
                return "\\" . $matches[0]; 
            }, $string); 
        return $string;
    }
    
}
