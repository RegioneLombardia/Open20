<?php

namespace app\modules\backendobjects\frontend\blockgroups;

use luya\cms\models\BlockGroup;
use Yii;

class ListatoContenutiGroup extends BlockGroup {
    
    public function identifier()
    {
        return 'listato-contenuti-group';
    }
    
    public function label()
    {
        return Yii::t('backendobjects', 'listato_contenuti');
    }
    
    public function getPosition()
    {
        return 4;
    }
}
