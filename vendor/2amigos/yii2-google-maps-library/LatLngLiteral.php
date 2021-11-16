<?php
/**
 */
namespace dosamigos\google\maps;

use yii\web\JsExpression;

/**
 * LatLngLiteral
 *
 * Google maps LatLngLiteral object
 *
 * @package dosamigos\google\maps
 */
class LatLngLiteral extends LatLng
{
    /**
     * @return string the js constructor of the object
     */
    public function getJs()
    {
        return new JsExpression("{{$this->__toString()}}");
    }
} 