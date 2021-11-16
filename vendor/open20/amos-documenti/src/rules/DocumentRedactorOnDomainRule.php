<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti
 * @category   CategoryName
 */

namespace open20\amos\documenti\rules;

use open20\amos\core\rules\BasicContentRule;
use open20\amos\documenti\models\DocumentiAgidTypeRoles;

class DocumentRedactorOnDomainRule extends BasicContentRule
{
    public $name = 'DocumentRedactorOnDomainRule';

    public function ruleLogic($user, $item, $params, $model) {

        $ids = DocumentiAgidTypeRoles::find()
            ->select('documenti_agid_type_id')
            ->andWhere(['user_id' =>\Yii::$app->getUser()->id ])
            ->distinct()
            ->column();

        if(in_array($model->documenti_agid_type_id,$ids) ){
            return true;
        }
        return false;
    }
}
