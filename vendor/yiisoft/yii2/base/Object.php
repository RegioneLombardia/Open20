<?php
/**
 */

namespace yii\base;

use Yii;

/**
 * Object is the base class that implements the *property* feature.
 *
 * It has been replaced by [[BaseObject]] in version 2.0.13 because `object` has become a reserved word which can not be
 * used as class name in PHP 7.2.
 *
 * Please refer to [[BaseObject]] for detailed documentation and to the
 * [UPGRADE notes](https://github.com/yiisoft/yii2/blob/2.0.13/framework/UPGRADE.md#upgrade-from-yii-2012)
 * on how to migrate your application to use [[BaseObject]] class to make your application compatible with PHP 7.2.
 *
 * @since 2.0
 * @deprecated since 2.0.13, the class name `Object` is invalid since PHP 7.2, use [[BaseObject]] instead.
 */
class Object extends BaseObject
{
}
