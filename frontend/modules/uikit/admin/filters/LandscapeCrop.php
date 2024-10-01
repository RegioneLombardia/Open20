<?php

namespace  app\modules\uikit\admin\filters;
use luya\admin\base\Filter;

/**
 * Large crop filter : width: 1920px, height: 1440px
 *
 */
class LandscapeCrop extends Filter
{
    public static function identifier()
    {
        return 'uk-landscape-crop';
    }

    public function name()
    {
        return "Landscape Crop (1920xnull)";
    }

    public function chain()
    {
        return [
            [self::EFFECT_THUMBNAIL, [
                'width' => 1920,
                'height' => null
            ]],
        ];
    }
}
