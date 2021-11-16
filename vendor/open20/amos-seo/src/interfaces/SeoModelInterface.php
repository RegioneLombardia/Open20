<?php

namespace open20\amos\seo\interfaces;


interface SeoModelInterface {
    /**
     * Returns the HTML with the JSON-LD tag
     */
    public function getSchema();
    
}