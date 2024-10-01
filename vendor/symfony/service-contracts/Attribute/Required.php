<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Contracts\Service\Attribute;

/**
 * A required dependency.
 *
 * This attribute indicates that a property holds a required dependency. The annotated property or method should be
 * considered during the instantiation process of the containing class.
 *
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class Required
{
}
