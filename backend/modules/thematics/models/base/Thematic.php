<?php

namespace backend\modules\thematics\models\base;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\record\ContentModel;
use open20\amos\core\user\User;
use Yii;
use yii\helpers\ArrayHelper;


class Thematic extends \preference\userprofile\models\Tag
{

    
    /**
     * @return string The model title field value
     */
    public function getTitle()
    {
        return $this->getNomeCognome();
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->getNomeCognome();
    }

//    /**
//     * @return string The model description field value
//     */
//    public function getDescription($truncate)
//    {
//        return $this->getNomeCognome();
//    }
//
//    /**
//     * @return array The columns ti show as default in GridViewWidget
//     */
//    public function getGridViewColumns()
//    {
//        return [];
//    }
//
    public function __toString()
    {
        return $this->nome;
    }

}
