<?php

namespace preference\userprofile\exceptions;

use Exception;
use Yii;

/**
 * Undocumented class
 */
class NotificationEmailException extends Exception
{

    public function __construct($message = null)
    {
        $this->message = is_null($message)? Yii::t('preferenceuser', 'Impossibile inviare la comunicazione via email'): $message;
    }

}