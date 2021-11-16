<?php

namespace app\modules\uikit;


/**
 * Description of Uikit
 *
 */
class Uikit extends \trk\uikit\Uikit{
  
    public static function pickBool($array, $keys)
    {
        $retarray = array_intersect_key($array, array_flip((array) $keys));
        foreach($retarray as $key => $value)
        {
            if(empty($retarray[$key])){
                $retarray[$key] = "false";
            }
        }
        return $retarray;
    }
}
