<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\components
 * @category   CategoryName
 */

namespace open20\amos\core\components;

use open20\amos\core\models\MapWidgetPlaces;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use Yii;

/**
 * Class PlacesComponents
 * @package open20\amos\core\components
 */
class PlacesComponents {

    /**
     * @param string $place_id
     * @return bool|MapWidgetPlaces|null
     * @throws \yii\base\InvalidConfigException
     */
    public static function getPlace($place_id) {
        if (!empty($place_id)) {
            /** @var MapWidgetPlaces $placeObj */
            $placeObj = MapWidgetPlaces::find()
                    ->where(["place_id" => $place_id])
                    ->one();
            return $placeObj;
        }
        return false;
    }

    /**
     * @param string $place_id
     * @return bool
     * @throws InvalidConfigException
     */
    public static function checkPlace($place_id) {
        $placeObj = self::getPlace($place_id);
        if (!$placeObj) {
            return self::getGoogleResponseByPlaceId($place_id, true);
        }
        return true;
    }

    /**
     * @param string $place_id
     * @param bool $trySave
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function getGoogleResponseByPlaceId($place_id, $trySave = true, $returnAddress = false, $formatted = false) {
        //get the google place key
        $googleMapsApiKey = self::getApiKey();

        if (empty($googleMapsApiKey)) {
            throw new InvalidConfigException("Missing Google PLACE API key");
        }

        $place_id = urlencode($place_id);
        $UrlGeocoder = "https://places.googleapis.com/v1/places/$place_id";
        $client = new \yii\httpclient\Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $response = $client->createRequest()
                ->setMethod('GET')
                ->setFormat(\yii\httpclient\Client::FORMAT_JSON)
                ->setUrl($UrlGeocoder)
                ->setHeaders([
                    "X-Goog-Api-Key" => $googleMapsApiKey,
                    "X-Goog-FieldMask" => "*",
                    'Referer' => \Yii::$app->params['platform']['frontendUrl'],
                ])
                ->send();

        if ($response->isOk) {
            $ResultGeocoding = $response->getData();
            if ($trySave) {
                self::savePlace($place_id, $ResultGeocoding, 'place');
            }
            if ($returnAddress) {
                if ($formatted) {
                    return $ResultGeocoding['formattedAddress'];
                } else {
                    return $ResultGeocoding['adrFormatAddress'];
                }
            } else {
                return $place_id;
            }
        } else {
            \Yii::getLogger()->log(print_r($response, 1), \yii\log\Logger::LEVEL_ERROR);
        }

        return false;
    }

    public static function getGoogleResponseByPlaceIdLatLong($place_id, $trySave = true) {
        //get the google place key
        $googleMapsApiKey = self::getApiKey();

        if (empty($googleMapsApiKey)) {
            throw new InvalidConfigException("Missing Google PLACE API key");
        }

        $place_id = urlencode($place_id);
        $UrlGeocoder = "https://places.googleapis.com/v1/places/$place_id";
        $client = new \yii\httpclient\Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $response = $client->createRequest()
                ->setMethod('GET')
                ->setFormat(\yii\httpclient\Client::FORMAT_JSON)
                ->setUrl($UrlGeocoder)
                ->setHeaders([
                    "X-Goog-Api-Key" => $googleMapsApiKey,
                    "X-Goog-FieldMask" => "*",
                    'Referer' => \Yii::$app->params['platform']['frontendUrl'],
                ])
                ->send();

        if ($response->isOk) {
            $ResultGeocoding = $response->getData();
            if ($trySave) {
                self::savePlace($place_id, $ResultGeocoding, 'place');
            }

            if (!empty($ResultGeocoding['location'])) {
                return [$ResultGeocoding['location']['latitude'], $ResultGeocoding['location']['longitude']];
            }
        } else {
            \Yii::getLogger()->log(print_r($response, 1), \yii\log\Logger::LEVEL_ERROR);
        }

        return false;
    }

    public static function getApiKey() {
        $googleMapsApiKey = null;

        if (!is_null(Yii::$app->params['googleMapsApiKey'])) {
            $googleMapsApiKey = Yii::$app->params['googleMapsApiKey'];
        } elseif (Yii::$app->params['google_places_api_key']) {
            $googleMapsApiKey = Yii::$app->params['google_places_api_key'];
        } elseif (!is_null(Yii::$app->params['google-maps']) && !is_null(Yii::$app->params['google-maps']['key'])) {
            $googleMapsApiKey = Yii::$app->params['google-maps']['key'];
        }

        return $googleMapsApiKey;
    }

    /**
     * @param string $geocodeString
     * @param bool $trySave
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function getGoogleResponseByGeocodeString($geocodeString, $trySave = false) {
        //get the google place key
        $googleMapsApiKey = self::getApiKey();

        if (empty($googleMapsApiKey)) {
            throw new InvalidConfigException("Missing Google PLACE API key");
        }

        $UrlGeocoder = "https://places.googleapis.com/v1/places:searchText";

        $client = new \yii\httpclient\Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);

        $response = $client->createRequest()
                ->setMethod('POST')
                ->setFormat(\yii\httpclient\Client::FORMAT_JSON)
                ->setData([
                    "textQuery" => $geocodeString,
                ])
                ->setUrl($UrlGeocoder)
                ->setHeaders([
                    "X-Goog-Api-Key" => $googleMapsApiKey,
                    "X-Goog-FieldMask" => "*",
                    'Referer' => \Yii::$app->params['platform']['frontendUrl'],
                ])
                ->send();

        if ($response->isOk) {
            $ResultGeocoding = $response->getData();
            if (!empty($ResultGeocoding['places'])) {
                $info = $ResultGeocoding['places'][0];
                $place_id = $info['id'];

                if ($trySave) {
                    self::savePlace($place_id, $info, 'geocode');
                }

                return $place_id;
            }
        } else {
            \Yii::getLogger()->log(print_r($response, 1), \yii\log\Logger::LEVEL_ERROR);
        }

        return false;
    }

    /**
     * @param MapWidgetPlaces $placeDataObj
     * @return string
     */
    public static function getGeocodeString($placeDataObj) {
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
    public static function savePlace($place_id, $ResultGeocoding, $from = 'geocode') {
        /** @var MapWidgetPlaces $placeObj */
        $placeObj = MapWidgetPlaces::find()
                ->where(["place_id" => $place_id])
                ->one();

        if (!$placeObj) {
            $placeObj = new MapWidgetPlaces();
            $placeObj->place_id = $place_id;
            $placeObj->place_response = Json::encode($ResultGeocoding);
            $placeObj->place_type = "google";

            if (isset($ResultGeocoding['location'])) {
                $Location = $ResultGeocoding['location'];

                if (isset($Location['latitude'])) {
                    $placeObj->latitude = (string) $Location['latitude'];
                }
                if (isset($Location['longitude'])) {
                    $placeObj->longitude = (string) $Location['longitude'];
                }
            }


            $address_components = $ResultGeocoding['addressComponents'];
            if ($address_components) {
                foreach ($address_components as $address_component) {
                    if (in_array('country', $address_component['types'])) {
                        $placeObj->country = (string) $address_component['longText'];
                    }
                    if (!$placeObj->city && in_array('locality', $address_component['types'])) {
                        $placeObj->city = (string) $address_component['shortText'];
                    }
                    if (in_array('administrative_area_level_3', $address_component['types'])) {
                        $placeObj->city = (string) $address_component['shortText'];
                    }
                    if (in_array('administrative_area_level_2', $address_component['types'])) {
                        $placeObj->province = (string) $address_component['shortText'];
                    }
                    if (in_array('administrative_area_level_1', $address_component['types'])) {
                        $placeObj->region = (string) $address_component['shortText'];
                    }
                    if (in_array('street_number', $address_component['types'])) {
                        $placeObj->street_number = (string) $address_component['shortText'];
                    }
                    if (in_array('postal_code', $address_component['types'])) {
                        $placeObj->postal_code = (string) $address_component['shortText'];
                    }
                    if (in_array('route', $address_component['types'])) {
                        $placeObj->address = (string) $address_component['shortText'];
                    }
                }
            }

            if ($placeObj->validate()) {
                $placeObj->detachBehaviors();
                $placeObj->save();
            } else {
                \Yii::getLogger()->log($placeObj->getErrors(), \yii\log\Logger::LEVEL_ERROR);
                \Yii::$app->getSession()->addFlash('warning', $placeObj->getErrors());
            }
        }
    }
}
