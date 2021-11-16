<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\upload
 * @category   CategoryName
 */

namespace open20\amos\upload\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class UploadWidget extends Widget
{
    public $message;
    public function init()
    {
        parent::init();
        if($this->message === null){
            $this->message = "Benvenuto nel widget";
        }
        else{
            $this->message = "Benvenuto " . $this->message;
        }
    }

    public function run() 
    {
        
        return $this->render('upload');
    }
}