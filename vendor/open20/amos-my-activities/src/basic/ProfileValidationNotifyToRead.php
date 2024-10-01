<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\basic
 * @category   CategoryName
 */

namespace open20\amos\myactivities\basic;

use open20\amos\admin\models\UserProfile;

/**
 * Class ProfileValidationNotifyToRead
 * @package open20\amos\myactivities\basic
 */
class ProfileValidationNotifyToRead extends \open20\amos\admin\models\UserProfileValidationNotify implements MyActivitiesModelsInterface
{
    /**
     * @return string
     */
    public function getSearchString()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getCreatorNameSurname()
    {
        /** @var UserProfile $userProfile */
        $userProfile = $this->createdUserProfile;
        if (!empty($userProfile)) {
            return $userProfile->getNomeCognome();
        }
        return '';
    }

    /**
     * @return \open20\amos\report\models\Report
     */
    public function getWrappedObject()
    {
        return \open20\amos\admin\models\UserProfileValidationNotify::findOne($this->id);
    }
}
