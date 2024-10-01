<?php
namespace app\modules\backendobjects\frontend\blockgroups;

use Yii;
use luya\cms\models\BlockGroup;

class DisabledGroup extends BlockGroup
{
 
    public function label()
    {
        return Yii::t('backendobjects', 'disabilitato');
    }
    
    public function identifier()
    {
        return 'disabled';
    }
    
    
    public function getPosition()
    {
        return 100;
    }
}