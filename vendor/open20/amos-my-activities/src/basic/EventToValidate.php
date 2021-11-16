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

use open20\amos\events\models\Event;
use open20\amos\events\models\search\EventSearch;

/**
 * Class EventToValidate
 * @package open20\amos\myactivities\basic
 */
class EventToValidate extends EventSearch implements MyActivitiesModelsInterface
{
    /**
     * @return string
     */
    public function getSearchString()
    {
        return $this->getTitle();
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
        return (!is_null($this->createdUserProfile) ? $this->createdUserProfile->getNomeCognome() : '');
    }

    /**
     * @return Event
     */
    public function getWrappedObject()
    {
        return Event::findOne($this->id);
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return 'events/event/view';
    }
}
