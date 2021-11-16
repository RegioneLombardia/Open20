<?php
/**
 */

namespace yii\debug\components\search\matchers;

/**
 * Checks if the given value is greater than the base one.
 *
 * @since 2.0
 */
class GreaterThan extends Base
{
    /**
     * {@inheritdoc}
     */
    public function match($value)
    {
        return ($value > $this->baseValue);
    }
}
