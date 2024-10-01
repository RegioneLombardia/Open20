<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\models;

use open20\amos\core\models\ModelsClassname;


/**
 * This is the model class for table "tag".
 */
class EntitysTagsMm
    extends \open20\amos\tag\models\base\BaseEntitysTagsMm
{

    /**
     * 
     * @param type $insert
     */
    public function beforeSave($insert) {
        $ret = parent::beforeSave($insert);
        if($insert && empty($this->models_classname_id))
        {
            $modelsClassname = ModelsClassname::find()->andWhere(['classname' => $this->classname])->one();
            if(!is_null($modelsClassname))
            {
                $this->models_classname_id = $modelsClassname->id;
            }
        }
        return $ret;
    }
}
