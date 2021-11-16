<?php
/**
 */

namespace yii\db;

/**
 * CheckConstraint represents the metadata of a table `CHECK` constraint.
 *
 * @since 2.0.13
 */
class CheckConstraint extends Constraint
{
    /**
     * @var string the SQL of the `CHECK` constraint.
     */
    public $expression;
}
