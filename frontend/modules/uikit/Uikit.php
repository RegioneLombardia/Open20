<?php

namespace app\modules\uikit;


/**
 * Description of Uikit
 *
 */
class Uikit extends \trk\uikit\Uikit{
  
    /**
     * nasconde o abilita controllo su ruolo nella SezioneOrizzontaleBlock
     * @var type boolean
     */
    
    
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
    
    
    /**
     * Renders a link tag.
     *
     * @param  string $title
     * @param  string $url
     * @param  array  $attrs
     * @return string
     */
    public static function anchor($title, $url = null, array $attrs = []) {
        return "<a" . self::attrs(['name' => $url], $attrs) . ">{$title}</a>";
    }
}
