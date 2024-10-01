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
use open20\amos\documenti\models\Documenti;
use yii\helpers\Url;

/**
 * Class DocumentToValidate
 * @package open20\amos\myactivities\basic
 */
class DocumentToValidate extends \open20\amos\documenti\models\search\DocumentiSearch implements MyActivitiesModelsInterface
{

    /**
     * @return string
     */
    public function getSearchString()
    {
        return $this->titolo;
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
     * @return Documenti
     */
    public function getWrappedObject()
    {
        return Documenti::findOne($this->id);
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        if($this->is_folder){
            return 'documenti/documenti/to-validate-documents';
        }
        return 'documenti/documenti/view';
    }

    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        if($this->is_folder){
            return Url::toRoute(["/" . $this->getViewUrl(), "parentId" => $this->id]);
        }
        return Url::toRoute(["/" . $this->getViewUrl(), "id" => $this->id]);
    }
}
