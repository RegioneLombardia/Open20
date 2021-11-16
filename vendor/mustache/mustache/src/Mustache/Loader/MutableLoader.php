<?php

/*
 *
 * (c) 2010-2017 Justin Hileman
 *
 */

/**
 * Mustache Template mutable Loader interface.
 */
interface Mustache_Loader_MutableLoader
{
    /**
     * Set an associative array of Template sources for this loader.
     *
     * @param array $templates
     */
    public function setTemplates(array $templates);

    /**
     * Set a Template source by name.
     *
     * @param string $name
     * @param string $template Mustache Template source
     */
    public function setTemplate($name, $template);
}
