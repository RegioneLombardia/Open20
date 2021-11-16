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
 * Interface MyActivitiesListInterface
 * @package open20\amos\myactivities\basic
 */
interface MyActivitiesListInterface
{
    public function getMyActivitiesList();

    public function setMyActivitiesList($myActivitiesList);

    public function addModelSet($listModel);
}
