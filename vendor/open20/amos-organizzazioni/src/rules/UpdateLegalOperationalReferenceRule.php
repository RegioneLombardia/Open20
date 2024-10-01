<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\rules
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\rules;

use open20\amos\core\rules\BasicContentRule;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloSedi;

/**
 * Class UpdateLegalOperationalReferenceRule
 * @package open20\amos\organizzazioni\rules
 */
class UpdateLegalOperationalReferenceRule extends BasicContentRule
{
    public $name = 'UpdateLegalOperationalReferenceRule';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var Profilo|ProfiloSedi $model */

        if (!($model instanceof Profilo) && !($model instanceof ProfiloSedi)) {
            return false;
        }

        /** @var Profilo $modelToCheck */
        $modelToCheck = $model;

        if ($model instanceof ProfiloSedi) {
            $modelToCheck = $model->profilo;
        }

        return in_array($user, [
            $modelToCheck->rappresentante_legale,
            $modelToCheck->referente_operativo
        ]);
    }
}
