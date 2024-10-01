<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\interfaces
 * @category   CategoryName
 */

namespace open20\amos\layout\interfaces;

/**
 * Interface AddHeaderNavItemsInterface
 * @package open20\amos\layout\interfaces
 */
interface AddHeaderNavItemsInterface
{
    /**
     * This method add items to the beginning of the header nav.
     * @param array $items
     * @return array
     */
    public function addItemsToBegin($items);

    /**
     * This method add items to the end of the header nav.
     * @param array $items
     * @return array
     */
    public function addItemsToEnd($items);
    
    /**
     * This method add items to the beginning of the bi-header nav.
     * @return string
     */
    public function addBiItemsToBegin();

    /**
     * This method add items to the end of the bi-header nav.
     * @return string
     */
    public function addBiItemsToEnd();
}
