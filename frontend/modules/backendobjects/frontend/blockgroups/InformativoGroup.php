<?php

namespace app\modules\backendobjects\frontend\blockgroups;

use luya\cms\models\BlockGroup;
use Yii;

class InformativoGroup extends BlockGroup {
    
    public function identifier()
    {
        return 'informativo-group';
    }
    
    public function label()
    {
        return Yii::t('backendobjects', 'informativo');
    }
    
    public function getPosition()
    {
        return 6;
    }
}
