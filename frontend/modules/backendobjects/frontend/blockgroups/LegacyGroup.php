<?php
namespace app\modules\backendobjects\frontend\blockgroups;

use Yii;
use luya\cms\frontend\blockgroups\DevelopmentGroup;

class LegacyGroup extends DevelopmentGroup
{
 
    public function identifier()
    {
        return 'legacy-group';
    }
    
    public function label()
    {
        return Yii::t('backendobjects', 'legacy');
    }
    
    public function getPosition()
    {
        return 10;
    }
}