<?php
/**
 */

namespace yii\db\conditions;

/**
 * Condition that connects two or more SQL expressions with the `AND` operator.
 *
 * @since 2.0.14
 */
class AndCondition extends ConjunctionCondition
{
    /**
     * Returns the operator that is represented by this condition class, e.g. `AND`, `OR`.
     *
     * @return string
     */
    public function getOperator()
    {
        return 'AND';
    }
}
