<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\models
 * @category   CategoryName
 */

namespace open20\amos\events\models;

use open20\amos\core\interfaces\CrudModelInterface;
use open20\amos\events\AmosEvents;
use yii\helpers\Url;

/**
 * Class EventRoom
 * This is the model class for table "event_room".
 * @package open20\amos\events\models
 */
class EventRoom extends \open20\amos\events\models\base\EventRoom implements CrudModelInterface
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'room_name'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getModelModuleName()
    {
        return AmosEvents::getModuleName();
    }

    /**
     * @inheritdoc
     */
    public function getModelControllerName()
    {
        return 'event-room';
    }

    /**
     * Returns the full url to the action with the model id.
     * @param $url
     * @return null|string
     */
    private function getFullUrl($url)
    {
        if (!empty($url)) {
            return Url::toRoute(["/" . $url, "id" => $this->id]);
        }
        return null;
    }

    /**
     * @return string
     */
    private function getBaseUrl()
    {
        return $this->getModelModuleName() . '/' . $this->getModelControllerName() . '/';
    }

    /**
     * @inheritdoc
     */
    public function getCreateUrl()
    {
        return $this->getBaseUrl() . 'create';
    }

    /**
     * @inheritdoc
     */
    public function getFullCreateUrl()
    {
        return $this->getCreateUrl();
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return $this->getBaseUrl() . 'view';
    }

    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        return $this->getFullUrl($this->getViewUrl());
    }

    /**
     * @inheritdoc
     */
    public function getUpdateUrl()
    {
        return $this->getBaseUrl() . 'update';
    }

    /**
     * @inheritdoc
     */
    public function getFullUpdateUrl()
    {
        return $this->getFullUrl($this->getUpdateUrl());
    }

    /**
     * @inheritdoc
     */
    public function getDeleteUrl()
    {
        return $this->getBaseUrl() . 'delete';
    }

    /**
     * @inheritdoc
     */
    public function getFullDeleteUrl()
    {
        return $this->getFullUrl($this->getDeleteUrl());
    }
}
