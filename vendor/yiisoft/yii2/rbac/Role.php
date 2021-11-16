<?php
/**
 */

namespace yii\rbac;

/**
 * For more details and usage information on Role, see the [guide article on security authorization](guide:security-authorization).
 *
 * @since 2.0
 */
class Role extends Item
{
    /**
     * {@inheritdoc}
     */
    public $type = self::TYPE_ROLE;
}
