<?php

namespace open20\design\components\bootstrapitalia;
use open20\design\components\bootstrapitalia\interfaces\WizardStepsInterface;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\BaseHtml;
use yii\helpers\Html;

/**
 * 
 */
class WizardSteps extends Widget
{

    /**
     *
     * @var integer
     */
    public $stepNumber = 1;

    public $isLink = false;

    private $classActive = 'active-step';

    private $classCurrent = 'current-step';

    public $listStep = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {

        return $this->render('wizard_steps', [
            'stepNumber' => $this->getStepNumber(),
        ]);
    }

    public function setStepNumber($n){
        $this->stepNumber = (!empty($n))?: '0';
    }

    public function getStepNumber(){
        return $this->stepNumber;
    }

    public function setIsLink($value){
        $this->isLink = $value;
    }

    public function getIsLink(){
        return $this->isLink;
    }

    public function getClassActive(){
        return $this->classActive;
    }

    public function getClassCurrent(){
        return $this->classCurrent;
    }

}