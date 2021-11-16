<?php
namespace open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers;

use open20\amos\admin\models\UserProfile;
use open20\amos\events\models\Event;
use Yii;
use yii\helpers\StringHelper;

class EventParser extends BaseParser
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
        $canView = Yii::$app->user->can($readPremission, ['model' => $item]);

        if ($canView) {
            //Define temp item
            $newItem = [];

            //Need id column
            $newItem['id'] = $item->id;

            //Get the list of description fields
            $newItem['representingColumn'] = $item->representingColumn();

            //Creator profile
            $owner = UserProfile::find()->andWhere(['user_id' => $item->created_by])->one();

            //Image
            $image = $item->eventLogo;

            //Fill fields from item usable in app
            $newItem['fields'] = [
                'begin_date_hour' => $item->begin_date_hour,
                'end_date_hour' => $item->end_date_hour,
                'title' => BaseParser::flushHtml($item->title),
                'description' => BaseParser::flushHtml($item->description),
                'summary' => BaseParser::flushHtml($item->summary),
                'event_location' => $item->event_location,
                'event_address' => $item->event_address,
                'event_address_house_number' => $item->event_address_house_number,
                'event_address_cap' => $item->event_address_cap,
                'city' => $item->cityLocation->nome,
                'province' => $item->provinceLocation->nome,
                'country' => $item->countryLocation->nome,
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
                'eventImageUrl' => $image ? Yii::$app->getUrlManager()->createAbsoluteUrl($image->getWebUrl()) : null,
            ];
            $newItem['likeMe'] = self::isLikeMe($item);
            $newItem['countLikeMe'] = self::getCountLike($item);
            //Remove id as is not needed
            unset($newItem['fields']['id']);

            //Can edit
            $newItem['canEdit'] = Yii::$app->user->can($editPremission, ['model' => $item]);

            return $newItem;
        }

        return [];
    }
}
