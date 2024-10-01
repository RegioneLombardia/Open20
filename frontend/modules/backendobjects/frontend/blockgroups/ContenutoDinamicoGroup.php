<?php

namespace app\modules\backendobjects\frontend\blockgroups;

use luya\cms\models\BlockGroup;
use Yii;

class ContenutoDinamicoGroup extends BlockGroup {
    
    public function identifier()
    {
        return 'contenuto-dinamico-group';
    }
    
    public function label()
    {
        return Yii::t('backendobjects', 'contenuto_dinamico');
    }
    
    public function getPosition()
    {
        return 5;
    }
}
