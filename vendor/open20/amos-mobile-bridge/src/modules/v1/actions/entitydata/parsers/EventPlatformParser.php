<?php
namespace open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers;

use open20\amos\admin\models\UserProfile;
use open20\amos\events\models\Event;
use Yii;
use yii\helpers\StringHelper;

class EventPlatformParser extends BaseParser
{

    /**
     *
     * @param Event $item
     * @return array
     */
    public static function parseItem($item)
    {
        //The base class name
        $baseClassName = StringHelper::basename(Event::className());

        //Read permission name
        $readPremission = strtoupper($baseClassName . '_READ');

        //Edit permission name
        $editPremission = strtoupper($baseClassName . '_UPDATE');

        //Can user view element
        //$canView = Yii::$app->user->can($readPremission, ['model' => $item]);

        //if ($canView) {

            $eventLocation = $item->eventLocation;
            $eventEntrance = $item->eventLocationEntrance;
            $eventPlace    = $eventLocation->eventPlaces;
            //Define temp item
            $newItem = [];

            //Need id column
            $newItem['id'] = $item->id;

            //Get the list of description fields
            $newItem['representingColumn'] = $item->representingColumn();

            //Creator profile
            $owner = UserProfile::findOne(['id' => $item->created_by]);

            //Image
            $image = $item->eventLogo;
            $imageUrl = $item->getMainImageEvent();
            $url = $imageUrl;

            if(strpos($imageUrl, 'https') === false){
//                pr('dentro');
                $url = Yii::$app->getUrlManager()->createAbsoluteUrl($imageUrl);
            }

            //Fill fields from item usable in app
            $newItem['fields'] = [
                'begin_date_hour' => $item->begin_date_hour,
                'end_date_hour' => $item->end_date_hour,
                'title' => self::flushHtml($item->title),
                'description' => self::flushHtml($item->description),
                'summary' => self::flushHtml($item->summary),
                'event_location' => $eventLocation->name,
                'event_entrance' => $eventEntrance->name,
                'event_address' => $eventPlace->address,
                'event_address_house_number' => $eventPlace->street_number,
                'event_address_cap' => $eventPlace->postal_code,
                'city' => $eventPlace->city,
                'province' => $eventPlace->province,
                'country' => $eventPlace->country,
                'created_at' => $item->created_at,
                'created_by' => $item->created_by,
                'comments_enabled' => true,
                'seats_management' => $item->seats_management,
                'has_tickets'  => $item->has_tickets,
                'user_has_ticket' => \open20\amos\mobile\bridge\modules\v1\utility\EventUtility::EventHasTicket($item->id),
                'has_qr_code' => $item->has_qr_code,
                'owner' => [
                    'nome' => $owner->nome,
                    'cognome' => $owner->cognome,
                    'presentazione_breve' => $owner->presentazione_breve,
                    'avatarUrl' => $owner->avatarWebUrl,
                ],
                'eventImageUrl' => $url? $url : null,
            ];
            $newItem['likeMe'] = self::isLikeMe($item);
            $newItem['countLikeMe'] = self::getCountLike($item);
            //Remove id as is not needed
            unset($newItem['fields']['id']);

            //Can edit
            $newItem['canEdit'] = Yii::$app->user->can($editPremission, ['model' => $item]);

            return $newItem;
        //}

        //return [];
    }
}
