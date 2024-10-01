<?php

namespace app\modules\backendobjects\frontend\blockgroups;

use luya\cms\models\BlockGroup;
use Yii;

class FooterGroup extends BlockGroup {
    
    public function identifier()
    {
        return 'footer-group';
    }
    
    public function label()
    {
        return Yii::t('backendobjects', 'footer');
    }
    
    public function getPosition()
    {
        return 12;
    }
}
