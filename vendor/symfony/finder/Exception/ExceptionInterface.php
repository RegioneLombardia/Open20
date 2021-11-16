<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Finder\Exception;

/**
 *
 * @deprecated since 3.3, to be removed in 4.0.
 */
interface ExceptionInterface
{
    /**
     * @return \Symfony\Component\Finder\Adapter\AdapterInterface
     */
    public function getAdapter();
}
