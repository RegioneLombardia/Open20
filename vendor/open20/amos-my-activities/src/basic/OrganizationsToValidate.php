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

use open20\amos\admin\models\UserProfile;
use yii\helpers\Url;

/**
 * Class OrganizationsToValidate
 * @package open20\amos\myactivities\basic
 */
class OrganizationsToValidate extends \openinnovation\organizations\models\search\OrganizationsSearch implements MyActivitiesModelsInterface
{
    /**
     * @return string
     */
    public function getSearchString()
    {
        return $this->name;
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
        $userProfile = UserProfile::find()->andWhere(['user_id' => $this->created_by])->one();
        if (!empty($userProfile)) {
            return $userProfile->getNomeCognome();
        }
        
        return '';
    }

    /**
     * @return \openinnovation\organizations\models\Organizations
     */
    public function getWrappedObject()
    {
        return \openinnovation\organizations\models\Organizations::findOne($this->id);
    }

    /**
     * 
     * @return string
     */
    public function getViewUrl()
    {
        return 'organizations/organizations/view';
    }

    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        return Url::toRoute(["/" . $this->getViewUrl(), "id" => $this->id]);
    }
}
