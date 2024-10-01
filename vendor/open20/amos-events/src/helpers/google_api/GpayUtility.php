<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 07/10/2020
 * Time: 15:52
 */

namespace open20\amos\events\helpers\google_api;


use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;

class GpayUtility
{
    /**
     * @param $id
     * @return bool
     */
    public static function createUpdateTicketClassGpay($id)
    {
        $event = Event::findOne($id);
        $services = new Services(['event_id' => $id]);

        $classId = $services->getTicketClassId($event);
        $objectId = $services->generateTicketObjectId($id);
        $skinnyJwt = $services->createUpdateSkinnyJwt($classId, $objectId);
//        pr($skinnyJwt);
//        die;
        if($skinnyJwt != null){
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public static function updateTicketObjectGpay($id, $user_id)
    {
        $event = Event::findOne($id);
        $verticalType = VerticalType::EVENTTICKET;
        $services = new Services(['event_id' => $id]);
        $invitation = EventInvitation::findOne(
            [
                'event_id' => $id,
                'user_id' => $user_id
            ]
        );

        $classId = $services->getTicketClassId($event);
        $objectId = $services->getTicketObjectId($invitation);
        $skinnyJwt = $services->updateCreateTicketObject($verticalType, $classId, $objectId, $user_id);

        if($skinnyJwt != null){
            return true;
        }
        return false;
    }


}