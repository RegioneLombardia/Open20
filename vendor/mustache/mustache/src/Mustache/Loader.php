<?php

/*
 *
 * (c) 2010-2017 Justin Hileman
 *
 */

/**
 * Mustache Template Loader interface.
 */
interface Mustache_Loader
{
    /**
     * Load a Template by name.
     *
     * @throws Mustache_Exception_UnknownTemplateException If a template file is not found
     *
     * @param string $name
     *
     * @return string|Mustache_Source Mustache Template source
     */
    public function load($name);
}
