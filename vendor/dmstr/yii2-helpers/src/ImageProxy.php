<?php
/**
 *
 */

namespace dmstr\helpers;


/**
 * Class ImageProxy
 * @package dmstr\helpers
 */
class ImageProxy
{

    public static function getFile($imageSource, $preset = null)
    {
        // check if settings components is set
        if (isset(\Yii::$app->settings)) {
            // preset example, when using imageproxy, https://github.com/willnorris/imageproxy#examples
            return \Yii::$app->settings->get('imgBaseUrl', 'app.frontend') .
                $preset .
                \Yii::$app->settings->get('imgHostPrefix', 'app.frontend') .
                $imageSource .
                \Yii::$app->settings->get('imgHostSuffix', 'app.frontend');
        }
        return $imageSource;
    }
}