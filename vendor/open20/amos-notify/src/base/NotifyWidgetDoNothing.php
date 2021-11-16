<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\base;


class NotifyWidgetDoNothing implements NotifyWidget
{

    /**
     * @param $uid
     * @param $class_name
     * @param null $externalquery
     * @param NotificationChannels $channel
     */
    public function notificationOff($uid, $class_name, $externalquery = null, $channel)
    {

    }

    /**
     * @param $uid
     * @param $class_name
     * @param null $externalquery
     * @param NotificationChannels $channel
     */
    public function notificationOn($uid, $class_name, $externalquery = null, $channel)
    {

    }

    /**
     * @param $uid
     * @param $class_name
     * @param null $externalquery
     */
    public function countNotRead($uid, $class_name, $externalquery = null)
    {
        $result = 0;

        return $result;
    }

    /**
     * @param $model
     * @param null $uid
     * @return bool
     */
    public function modelIsRead($model, $uid = null)
    {
        $result = false;
        return $result;
    }

    /**
     * @param string $modelClassName
     * @param string $type
     * @return array
     */
    public static function manageNewChannelNotifications($modelClassName, $channel, $type)
    {
        $retval = false;
        return $retval;
    }

    /**
     * @param int $uid
     * @param string $class_name
     * @param int $contentId
     * @return bool
     */
    public function favouriteOn($uid, $class_name, $contentId)
    {
        $ok = true;
        return $ok;
    }

    /**
     * @param int $uid
     * @param string $class_name
     * @param int $contentId
     * @return bool
     */
    public function favouriteOff($uid, $class_name, $contentId)
    {
        $ok = true;
        return $ok;
    }

    /**
     * @param $model
     * @param null $uid
     * @return bool
     */
    public function isFavorite($model, $uid = null)
    {
        $result = false;
        return $result;
    }
}