<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models
 * @category   CategoryName
 */

namespace open20\amos\admin\models;

use open20\amos\admin\models\base\UserContact as BaseUserContact;

/**
 * Class UserContact
 * @package open20\amos\admin\models
 */
class UserContact extends BaseUserContact
{

    /**
     * Constants for user contant statuses
     */
    const STATUS_INVITED = "INVITED";
    const STATUS_ACCEPTED = "ACCEPTED";
    const STATUS_REFUSED = "REFUSED";

   public function getInvitingUserProfile($userId = null){
       if(is_null($userId)){
           $userId = \Yii::$app->user->id;
       }
       if($this->user_id == $userId) {
           return $this->contactUserProfile;
       }
       return $this->userProfile;
   }

}
