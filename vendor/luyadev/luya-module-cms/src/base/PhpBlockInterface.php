<?php

namespace luya\cms\base;

/**
 * PHP Cms Block interface
 *
 * @since 1.0.0
 */
interface PhpBlockInterface
{
    public function frontend();
    
    public function admin();
}
