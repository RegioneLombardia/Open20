<?php

namespace preference\userprofile\models;

use open20\amos\comuni\models\IstatComuni as ModelsIstatComuni;
use preference\userprofile\utility\TopicTagUtility;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Model without table
 * 
 */
class IstatComuni extends ModelsIstatComuni
{

    // public $labelConProvincia;

    /**
     * @return string
     */
    public function getLabelConProvincia()
    {
        return $this->nome . ' (' . $this->istatProvince->sigla . ')';
    }
}
