<?php
namespace open20\design\models;

use open20\design\components\bootstrapitalia\interfaces\CheckBoxListTopicsIconInterface;
use yii\base\Model;

class TestModelCheckBox extends Model
{

    public $attribute;

    public function getAttribute()
    {
        return $this->attribute;
    }

}
