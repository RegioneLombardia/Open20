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
class MetaRobotsProperty extends Property {

    public function varName() {
        return 'metaRobots';
    }

    public function label() {
        return \Yii::t('seo','page_property_meta_robots_label');
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
