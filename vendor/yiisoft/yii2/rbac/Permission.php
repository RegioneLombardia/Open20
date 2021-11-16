<?php
/**
 */

namespace yii\rbac;

/**
 * For more details and usage information on Permission, see the [guide article on security authorization](guide:security-authorization).
 *
 * @since 2.0
 */
class Permission extends Item
{
    /**
     * {@inheritdoc}
     */
    public $type = self::TYPE_PERMISSION;
}
