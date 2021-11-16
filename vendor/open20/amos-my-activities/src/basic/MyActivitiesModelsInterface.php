<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\basic
 * @category   CategoryName
 */

namespace open20\amos\myactivities\basic;

/**
 * Interface MyActivitiesModelsInterface
 * @package open20\amos\myactivities\basic
 */
interface MyActivitiesModelsInterface
{
    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @return string
     */
    public function getCreatorNameSurname();

    /**
     * @return string
     */
    public function getSearchString();

    /**
     * @return \open20\amos\core\record\Record
     */
    public function getWrappedObject();
}
