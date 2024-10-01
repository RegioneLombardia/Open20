<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

if (\PHP_VERSION_ID < 80000) {
    interface Stringable
    {
        /**
         * @return string
         */
        public function __toString();
    }
}
