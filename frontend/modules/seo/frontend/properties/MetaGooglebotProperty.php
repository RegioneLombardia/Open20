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
class MetaGooglebotProperty extends Property {

    public function varName() {
        return 'metaGooglebot';
    }

    public function label() {
        return \Yii::t('app','page_property_meta_googlebot_label');
    }

    public function type() {
        return self::TYPE_CHECKBOX_ARRAY;
    }
    
    public function options() {
        return [
            'items' => [
                ['value' => 'noindex', 'label' => 'noindex'],
                ['value' => 'nofollow', 'label' => 'nofollow'],
                ['value' => 'nosnippet', 'label' => 'nosnippet'],
                ['value' => 'noarchive', 'label' => 'noarchive'],
                ['value' => 'noimageindex', 'label' => 'noimageindex'],
            ]
        ];
    }

}
