<?php

namespace dmstr\helpers;

/*
 *
 */

/**
 * Class Html.
 *
 */
class Html extends \yii\helpers\Html
{
    /**
     * Use case:
     * \dmstr\helpers\Html::a("test link", ['create']).
     *
     * @param string $text
     * @param null   $url
     * @param array  $options
     *
     * @return string|null
     */
    public static function a($text, $url = null, $options = [])
    {
        return RouteAccess::can($url, function () use ($text, $url, $options) {
            return parent::a($text, $url, $options);
        }, function () {
            return;
        }, [
            $text,
            $options,
        ]);
    }
}
