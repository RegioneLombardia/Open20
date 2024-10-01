<?php

namespace app\modules\backendobjects\frontend\blockgroups;

use luya\cms\models\BlockGroup;
use Yii;

class SocialGroup extends BlockGroup {
    
    public function identifier()
    {
        return 'social-group';
    }
    
    public function label()
    {
        return Yii::t('backendobjects', 'social');
    }
    
    public function getPosition()
    {
        return 7;
    }
}
