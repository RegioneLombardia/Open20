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
class OgDescriptionProperty extends Property {
    public function varName()
    {
        return 'ogDescription';
    }    
    
    public function label()
    {
        return \Yii::t('seo','page_property_og_description_label');
    }
    
    public function type()
    {
        return self::TYPE_TEXT;
    }
}
