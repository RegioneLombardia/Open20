<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\models
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\models;

use open20\amos\organizzazioni\Module;

/**
 * Class ProfiloGroupsMm
 * This is the model class for table "profilo_groups_mm".
 * @package open20\amos\organizzazioni\models
 */
class ProfiloGroupsMm extends \open20\amos\organizzazioni\models\base\ProfiloGroupsMm
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'profilo_group_id',
            'profilo_id'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getModelModuleName()
    {
        return Module::getModuleName();
    }
    
    /**
     * @inheritdoc
     */
    public function getModelControllerName()
    {
        return 'profilo-groups';
    }
}
