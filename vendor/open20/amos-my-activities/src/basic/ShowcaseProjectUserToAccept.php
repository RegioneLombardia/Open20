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
use open20\amos\showcaseprojects\models\search\ShowcaseProjectSearch;
use open20\amos\showcaseprojects\models\search\ShowcaseProjectUserMmSearch;
use open20\amos\showcaseprojects\models\ShowcaseProject;
use yii\helpers\Url;

/**
 * Class ShowcaseProjectUserToAccept
 * @package open20\amos\myactivities\basic
 */
class ShowcaseProjectUserToAccept extends ShowcaseProjectUserMmSearch implements MyActivitiesModelsInterface
{
    /**
     * @return string
     */
    public function getSearchString()
    {
        return $this->showcaseProject->title;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        pr($this->created_at);die();
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
        /** @var UserProfile $userProfile*/
        $userProfile = UserProfile::find()->andWhere(['user_id' => $this->created_by])->one();
        if (!empty($userProfile)) {
            return $userProfile->getNomeCognome();
        }
        return '';
    }

    /**
     * @return ShowcaseProject
     */
    public function getWrappedObject()
    {
        return ShowcaseProject::findOne($this->showcase_project_id);
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getViewUrl()
    {
        return '/showcaseprojects/showcase-project/view';
    }

    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        return Url::toRoute(["/" . $this->getViewUrl(), "id" => $this->showcase_project_id]);
    }
}
