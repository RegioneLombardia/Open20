<?php
namespace app\modules\backendobjects\frontend\blockgroups;

use Yii;
use luya\cms\frontend\blockgroups\LayoutGroup;

class ContenitoreGroup extends LayoutGroup
{
 
    public function label()
    {
        return Yii::t('backendobjects', 'contenitore');
    }
    
    public function getPosition()
    {
        return 13;
    }
}