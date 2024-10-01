<?php
namespace app\modules\backendobjects\frontend\blockgroups;

use Yii;
use luya\cms\frontend\blockgroups\MainGroup;

class ElementiBaseGroup extends MainGroup
{
 
    public function label()
    {
        return Yii::t('backendobjects', 'elementi_base');
    }
    
    public function getPosition()
    {
        return 8;
    }
}