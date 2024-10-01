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
use openinnovation\innovative\solutions\models\search\InnovativeSolutionSearch;
use openinnovation\innovative\solutions\models\InnovativeSolution;

use openinnovation\landing\models\Territory;

/**
 * Class TerritoryToValidate
 * @package open20\amos\myactivities\basic
 */
class innovativeSolutionToValidate extends InnovativeSolutionSearch implements MyActivitiesModelsInterface
{
    /**
     * @return string
     */
    public function getSearchString()
    {
        return $this->title;
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
     * @return Territory
     */
    public function getWrappedObject()
    {
        return InnovativeSolution::findOne($this->id);
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return 'innovativesolutions/innovative-solution/view';
    }
}
