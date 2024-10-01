<?php

namespace app\modules\backendobjects\frontend\blockgroups;

use luya\cms\models\BlockGroup;
use Yii;

class LayoutInPaginaGroup extends BlockGroup {
    
    public function identifier()
    {
        return 'layout-in-pagina-group';
    }
    
    public function label()
    {
        return Yii::t('backendobjects', 'layout_in_page');
    }
    
    public function getPosition()
    {
        return 1;
    }
}
