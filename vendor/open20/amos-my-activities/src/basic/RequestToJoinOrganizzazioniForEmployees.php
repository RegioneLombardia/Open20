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
 * Class RequestToJoinOrganizzazioniForEmployees
 * @package open20\amos\myactivities\basic
 */
class RequestToJoinOrganizzazioniForEmployees extends \open20\amos\organizzazioni\models\ProfiloUserMm implements MyActivitiesModelsInterface
{
    /**
     * @return string
     */
    public function getSearchString()
    {
        $userProfile = $this->createdUserProfile;
        if (is_null($userProfile)) {
            return '';
        }
        return $userProfile->getNomeCognome();
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
     */
    public function getCreatorNameSurname()
    {
        $createdUserProfile = $this->createdUserProfile;
        return (!is_null($createdUserProfile) ? $createdUserProfile->getNomeCognome() : '');
    }

    /**
     * @return \open20\amos\organizzazioni\models\ProfiloUserMm
     */
    public function getWrappedObject()
    {
        return \open20\amos\organizzazioni\models\ProfiloUserMm::findOne($this->id);
    }
}
