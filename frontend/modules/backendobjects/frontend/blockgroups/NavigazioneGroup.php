<?php

namespace app\modules\backendobjects\frontend\blockgroups;

use luya\cms\models\BlockGroup;
use Yii;

class NavigazioneGroup extends BlockGroup {
    
    public function identifier()
    {
        return 'navigazione-group';
    }
    
    public function label()
    {
        return Yii::t('backendobjects', 'navigazione');
    }
    
    public function getPosition()
    {
        return 2;
    }
}
