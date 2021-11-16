<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\components
 * @category   CategoryName
 */

namespace open20\amos\comments\components;

use yii\base\Event;

/**
 * Interface CommentComponentInterface
 * @package open20\amos\comments\components
 */
interface CommentComponentInterface
{
    /**
     * Method that enable the comments on a specific model.
     * @param \yii\base\Event $event
     */
    public function showComments(Event $event);
}
