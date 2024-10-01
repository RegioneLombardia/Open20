<?php

namespace app\modules\backendobjects\frontend\blockgroups;

use luya\cms\models\BlockGroup;
use Yii;

class ContenutoGroup extends BlockGroup {
    
    public function identifier()
    {
        return 'contenuto-group';
    }
    
    public function label()
    {
        return Yii::t('backendobjects', 'contenuto');
    }
    
    public function getPosition()
    {
        return 3;
    }
}
