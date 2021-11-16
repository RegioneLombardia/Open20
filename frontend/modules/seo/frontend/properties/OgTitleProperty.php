<?php

/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\seo\frontend\properties;

use luya\admin\base\Property;

/**
 * Description of MetaTitleProperty
 *
 */
class OgTitleProperty extends Property {
    public function varName()
    {
        return 'ogTitle';
    }    
    
    public function label()
    {
        return \Yii::t('seo','page_property_og_title_label');
    }
    
    public function type()
    {
        return self::TYPE_TEXT;
    }
}
