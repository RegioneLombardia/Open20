<?php

namespace luya\admin\openapi\specs;

/**
 * Each Spec Requires.
 *
 * @since 3.2.0
 */
interface SpecInterface
{
    public function getSummary(): string;

    public function getDescription(): string;

    public function getParameters(): array;

    public function getResponses(): array;
}
