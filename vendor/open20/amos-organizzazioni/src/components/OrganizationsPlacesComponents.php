<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\components
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\components;

use open20\amos\organizzazioni\models\OrganizationsPlaces;
use open20\amos\organizzazioni\Module;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

/**
 * Class OrganizationsPlacesComponents
 * @package open20\amos\organizzazioni\components
 */
class OrganizationsPlacesComponents
{
    /**
     * @param string $place_id
     * @return bool|OrganizationsPlaces
     * @throws \yii\base\InvalidConfigException
     */
    public static function getPlace($place_id)
    {
        if (!empty($place_id)) {
            /** @var OrganizationsPlaces $model */
            $model = Module::instance()->createModel('OrganizationsPlaces')->className();
            $placeObj = $model::find()
                ->where(["place_id" => $place_id])
                ->one();

            return $placeObj;
        }
        return false;
    }

    /**
     * @param string $place_id
     */
    public static function checkPlace($place_id)
    {
        /*$placeObj = OrganizationsPlaces::find()
            ->where(["place_id" => $place_id])
            ->one();*/
        $placeObj = self::getPlace($place_id);
        if (!$placeObj) {
            self::getGoogleResponseByPlaceId($place_id, true);
        }
    }

    /**
     * @param string $place_id
     * @param bool $trySave
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function getGoogleResponseByPlaceId($place_id, $trySave = false)
    {
        //get the google place key
        $googleMapsApiKey = null;
        if (isset(\Yii::$app->params['google_places_api_key'])) {
            $googleMapsApiKey = \Yii::$app->params['google_places_api_key'];
        } else {
            throw new InvalidConfigException("Missing Google PLACE API key");
        }

        $place_id = urlencode($place_id);
        $UrlGeocoder = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$place_id&key=$googleMapsApiKey";
        $ResultGeocodingJson = file_get_contents($UrlGeocoder);
        $ResultGeocoding = Json::decode($ResultGeocodingJson);

        if ($ResultGeocoding && isset($ResultGeocoding['status'])) {
            if ($ResultGeocoding['status'] == 'OK') {
                if ($trySave) {
                    self::saveOrganizationPlace($place_id, $ResultGeocoding, 'place');
                }

                return $place_id;
            }
        }

        return false;
    }

    /**
     * @param string $geocodeString
     * @param bool $trySave
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function getGoogleResponseByGeocodeString($geocodeString, $trySave = false)
    {
        //get the google place key
        $googleMapsApiKey = null;
        if (isset(\Yii::$app->params['google_places_api_key'])) {
            $googleMapsApiKey = \Yii::$app->params['google_places_api_key'];
        } else {
            throw new \yii\base\InvalidConfigException("Missing Google PLACE API key");
        }

        $GeoCoderParamsString = urlencode($geocodeString);
        $UrlGeocoder = "https://maps.googleapis.com/maps/api/geocode/json?region=it&address=$GeoCoderParamsString&key=$googleMapsApiKey";
        $ResultGeocodingJson = file_get_contents($UrlGeocoder);
        $ResultGeocoding = Json::decode($ResultGeocodingJson);
        if ($ResultGeocoding && isset($ResultGeocoding['status'])) {
            if ($ResultGeocoding['status'] == 'OK') {
                $place_id = $ResultGeocoding['results'][0]['place_id'];

                if ($trySave) {
                    self::saveOrganizationPlace($place_id, $ResultGeocoding, 'geocode');
                }

                return $place_id;
            }
        }

        return false;
    }

    /**
     * @param OrganizationsPlaces $placeDataObj
     * @return string
     */
    public static function getGeocodeString($placeDataObj)
    {
        //identifica i parametri per costruire la stringa
        $GeoCoderParams = [];
        if ($placeDataObj->address || $placeDataObj->street_number) {
            $tmp_params = [];

            if ($placeDataObj->address) {
                $tmp_params[] = $placeDataObj->address;
            }

            if ($placeDataObj->address && $placeDataObj->street_number) {
                $tmp_params[] = $placeDataObj->street_number;
            }

            $GeoCoderParams[] = implode(" ", $tmp_params);
        }

        if ($placeDataObj->postal_code || $placeDataObj->city || $placeDataObj->province) {
            $tmp_params = [];
            if ($placeDataObj->postal_code) {
                $tmp_params[] = $placeDataObj->postal_code;
            }

            if ($placeDataObj->city) {
                $tmp_params[] = $placeDataObj->city;
            }

            if ($placeDataObj->province) {
                $tmp_params[] = $placeDataObj->province;
            }

            $GeoCoderParams[] = implode(" ", $tmp_params);
        }

        if ($placeDataObj->region) {
            $GeoCoderParams[] = $placeDataObj->region;
        }

        if ($placeDataObj->country) {
            $GeoCoderParams[] = $placeDataObj->country;
        }

        return (count($GeoCoderParams) ? implode(", ", $GeoCoderParams) : "");
    }

    /**
     * @param string $place_id
     * @param array $ResultGeocoding
     * @param string $from
     * @throws \yii\base\InvalidConfigException
     */
    public static function saveOrganizationPlace($place_id, $ResultGeocoding, $from = 'geocode')
    {
        /** @var OrganizationsPlaces $model */
        $model = Module::instance()->createModel('OrganizationsPlaces');
        /** @var OrganizationsPlaces $placeObj */
        $placeObj = $model::find()
            ->where(["place_id" => $place_id])
            ->one();

        if (!$placeObj) {
            $placeObj = Module::instance()->createModel('OrganizationsPlaces');
            $placeObj->place_id = $place_id;
            $placeObj->place_response = Json::encode($ResultGeocoding);
            $placeObj->place_type = "google";

            //get the result
            $googleResult = ($from == 'geocode' ? $ResultGeocoding['results'][0] : $ResultGeocoding['result']);

            $geometry = $googleResult['geometry'];
            if ($geometry) {
                if (isset($geometry['location'])) {
                    $Location = $geometry['location'];

                    if (isset($Location['lat'])) {
                        $placeObj->latitude = (String)$Location['lat'];
                    }
                    if (isset($Location['lng'])) {
                        $placeObj->longitude = (String)$Location['lng'];
                    }
                }
            }

            $address_components = $googleResult['address_components'];
            if ($address_components) {
                foreach ($address_components as $address_component) {
                    if (in_array('country', $address_component['types'])) {
                        $placeObj->country = (String)$address_component['long_name'];
                    }
                    if (!$placeObj->city && in_array('locality', $address_component['types'])) {
                        $placeObj->city = (String)$address_component['short_name'];
                    }
                    if (in_array('administrative_area_level_3', $address_component['types'])) {
                        $placeObj->city = (String)$address_component['short_name'];
                    }
                    if (in_array('administrative_area_level_2', $address_component['types'])) {
                        $placeObj->province = (String)$address_component['short_name'];
                    }
                    if (in_array('administrative_area_level_1', $address_component['types'])) {
                        $placeObj->region = (String)$address_component['short_name'];
                    }
                    if (in_array('street_number', $address_component['types'])) {
                        $placeObj->street_number = (String)$address_component['short_name'];
                    }
                    if (in_array('postal_code', $address_component['types'])) {
                        $placeObj->postal_code = (String)$address_component['short_name'];
                    }
                    if (in_array('route', $address_component['types'])) {
                        $placeObj->address = (String)$address_component['short_name'];
                    }
                }
            }

            if ($placeObj->validate()) {
                $placeObj->detachBehaviors();
                $placeObj->save();
            } else {
                print_r($placeObj->getErrors());
                die();
            }
        }
    }
}
