<?php
/**
 */

namespace yii\debug\components\search\matchers;

/**
 * Checks if the given value is greater than or equal the base one.
 *
 * @since 2.0.7
 */
class GreaterThanOrEqual extends Base
{
    /**
     * {@inheritdoc}
     */
    public function match($value)
    {
        return $value >= $this->baseValue;
    }
}
