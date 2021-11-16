<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\interfaces
 * @category   CategoryName
 */

namespace open20\amos\privileges\interfaces;

/**
 * Interface CategoriesRolesInterface
 * @package open20\amos\privileges\interfaces
 */
interface CategoriesRolesInterface
{
    /**
     * This method is the getter for model name to permissions.
     * 
     */    
    
    public static function getModelCategoryRole();
    public static function getCategoryArrayRole();
    public static function getCategoryArrayRoleAssignedToUser($userId);

}
