<?php

namespace app\modules\backendobjects\frontend\blockgroups;

use luya\cms\models\BlockGroup;
use Yii;

class SeparatoriGroup extends BlockGroup {
    
    public function identifier()
    {
        return 'separatori-group';
    }
    
    public function label()
    {
        return Yii::t('backendobjects', 'separatori');
    }
    
    public function getPosition()
    {
        return 8;
    }
}
