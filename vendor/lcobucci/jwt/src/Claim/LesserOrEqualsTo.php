<?php
/**
 *
 */

namespace Lcobucci\JWT\Claim;

use Lcobucci\JWT\Claim;
use Lcobucci\JWT\ValidationData;

/**
 * Validatable claim that checks if value is lesser or equals to the given data
 *
 * @since 2.0.0
 */
class LesserOrEqualsTo extends Basic implements Claim, Validatable
{
    /**
     * {@inheritdoc}
     */
    public function validate(ValidationData $data)
    {
        if ($data->has($this->getName())) {
            return $this->getValue() <= $data->get($this->getName());
        }

        return true;
    }
}
