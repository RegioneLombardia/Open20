<?php
namespace app\modules\backendobjects\frontend\blockgroups;

use Yii;
use luya\cms\frontend\blockgroups\DevelopmentGroup;

class SviluppoGroup extends DevelopmentGroup
{
 
    public function label()
    {
        return Yii::t('backendobjects', 'development');
    }
    
    public function getPosition()
    {
        return 9;
    }
}