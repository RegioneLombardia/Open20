<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\interfaces
 * @category   CategoryName
 */

namespace open20\amos\utility\interfaces;

/**
 * Interface BaseContentModelInterface
 * @package open20\amos\core\interfaces
 */
interface bcDriverInterface
{
    /**
     * Execute queris for calculate bc by user and macro area
     */
    public function calculateBulletCounters();
    
    /**
     * Update bc table by user and relative widget icon
     * Find into amos_widgets the relative id
     * 
     * @param type $widget widget icon name
     */
    public function updateBulletCounters($widget = null, $namespace = null);
    
}
