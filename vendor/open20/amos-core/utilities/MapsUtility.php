<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\utilities
 * @category   CategoryName
 */

namespace open20\amos\core\utilities;

use open20\amos\core\module\BaseAmosModule;
use Yii;
use yii\base\BaseObject;
use yii\helpers\Json;

/**
 * Class MapsUtility
 * @package open20\amos\core\utilities
 */
class MapsUtility extends BaseObject {

    /**
     * Get latitude and longitude of a place.
     * @param string $position - Place to search coordinates
     * @return array $origin - empty array if coordinates not found, otherwise array with structure
     * $origin = [
     *      'lat' => '41.1234',  // the Latitude of $position
     *      'lng' => '9.657'    // the longitude of $position
     * ]
     */
    public static function getMapPosition($position = '') {
        if (!$position) {
            $position = 'Roma';
        }
        $origin = [];

        $place_id = \open20\amos\core\components\PlacesComponents::getGoogleResponseByGeocodeString($position, true);

        if (!empty($place_id)) {
            $obj = \open20\amos\core\components\PlacesComponents::getPlace($place_id);
            if (!empty($obj)) {
                $origin['lat'] = $obj->latitude;
                $origin['lng'] = $obj->longitude;
            }
        }


        if (empty($origin)) {
            $pos = strpos($position, ',');
            if ($pos) {
                $position = substr($position, $pos);
                return self::getMapPosition($position);
            } else {
                return self::getMapPosition();
            }
        }
        return $origin;
    }

    /**
     * Get latitude and longitude of a place by place id.
     * @param string $placeId - Place to search coordinates
     * @return array $origin - empty array if coordinates not found, otherwise array with structure
     * $origin = [
     *      'lat' => '41.1234',  // the Latitude of $position
     *      'lng' => '9.657'    // the longitude of $position
     * ]
     */
    public static function getMapPositionByPlaceId($placeId = '') {
        if (!$placeId) {
            return [];
        }

        $obj = \open20\amos\core\components\PlacesComponents::getPlace($placeId);
        if (empty($obj)) {
            $place_id = \open20\amos\core\components\PlacesComponents::getGoogleResponseByPlaceId($placeId, true);

            $origin = [];

            if (!empty($place_id)) {
                $obj = \open20\amos\core\components\PlacesComponents::getPlace($place_id);
            }
        }
        if (!empty($obj)) {
            $origin['lat'] = $obj->latitude;
            $origin['lng'] = $obj->longitude;
        }
        return $origin;
    }
}
