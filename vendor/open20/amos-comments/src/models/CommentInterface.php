<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\models
 * @category   CategoryName
 */

namespace open20\amos\comments\models;

/**
 * Interface CommentInterface
 * @package open20\amos\comments\models
 */
interface CommentInterface
{
    /**
     * In this method must be defined the conditions that say if the model is commentable and then return true or false.
     * @return bool
     */
    public function isCommentable();
}
