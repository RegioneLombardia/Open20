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
class OgTypeProperty extends Property {

    public function varName() {
        return 'ogType';
    }

    public function label() {
        return \Yii::t('seo','page_property_og_type_label');
    }

    public function type() {
        return self::TYPE_SELECT;
    }

    public function options() {
        return [
            ['value' => 'article', 'label' => 'article: article on a website'],
            ['value' => 'book', 'label' => 'book: book or pubblication'],
            ['value' => 'place', 'label' => 'place: represents a place - such as a venue, a business, a landmark, or any other location'],
            ['value' => 'product', 'label' => 'product: this includes both virtual and physical products'],
            ['value' => 'profile', 'label' => 'profile: represents a person'],
            ['value' => 'video.other', 'label' => 'video.other: represents a generic video'],
            ['value' => 'website', 'label' => 'website'],
        ];
    }

}
