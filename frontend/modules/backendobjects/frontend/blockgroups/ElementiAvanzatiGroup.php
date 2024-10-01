<?php
namespace app\modules\backendobjects\frontend\blockgroups;

use Yii;
use luya\cms\base\BlockGroup;

class ElementiAvanzatiGroup extends BlockGroup
{
    public function identifier()
    {
        return 'elementi-avanzati';
    }
    
    public function label()
    {
        return Yii::t('backendobjects', 'elementi_avanzati');
    }
    
    public function getPosition()
    {
        return 11;
    }
}