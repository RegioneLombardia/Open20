<?php
/**
 */

namespace yii\db;

/**
 * DefaultValueConstraint represents the metadata of a table `DEFAULT` constraint.
 *
 * @since 2.0.13
 */
class DefaultValueConstraint extends Constraint
{
    /**
     * @var mixed default value as returned by the DBMS.
     */
    public $value;
}
