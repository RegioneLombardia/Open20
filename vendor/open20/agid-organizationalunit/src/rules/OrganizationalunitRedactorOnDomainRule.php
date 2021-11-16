<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti
 * @category   CategoryName
 */

namespace open20\agid\organizationalunit\rules;

use open20\amos\core\rules\BasicContentRule;
use  open20\agid\organizationalunit\models\AgidOrganizationalUnitContentTypeRoles;

class OrganizationalunitRedactorOnDomainRule extends BasicContentRule
{
    public $name = 'OrganizationalunitRedactorOnDomainRule';

    public function ruleLogic($user, $item, $params, $model) {

        $ids = AgidOrganizationalUnitContentTypeRoles::find()
            ->select('agid_organizational_unit_content_type_id')
            ->andWhere(['user_id' =>\Yii::$app->getUser()->id ])
            ->distinct()
            ->column();
        if($model->id == null){
            return true;
        }
        if(in_array($model->agid_organizational_unit_content_type_id,$ids) ){
            return true;
        }
        return false;
    }
}
