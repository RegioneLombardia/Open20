<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\seo\behaviors
 * @category   CategoryName
 */

namespace open20\amos\seo\behaviors;

use yii\behaviors\SluggableBehavior;


/**
 * Class SluggableAmosBehavior
 * @package open20\amos\core\behaviors
 */
class SluggableSeoBehavior extends SluggableBehavior {
    
    public $maxLengthSlug;
    
    public function makeSeoUnique($slug) {
        return $this->makeUnique($slug);
    }
    
    public function generateSeoSlug($text) {
        return $this->generateSlug($text);
    }
    
    public function generateUniqueSeoSlug($text) {
        $text = str_replace("\n", " ", $text); // textarea

        $text = str_replace("&nbsp;", " ", $text);
        $text = str_replace(array("'", "-"), "", $text); //remove single quote and dash
        $text = mb_convert_case($text, MB_CASE_LOWER, "UTF-8"); //convert to lowercase
        $text = preg_replace("#[^a-zA-Z0-9]+#", "-", $text); //replace everything non an with dashes
        $text = preg_replace("#(-){2,}#", "$1", $text); //replace multiple dashes with one
        $text = trim($text, "-"); //trim dashes from beginning and end of string if any

        if ($this->maxLengthSlug) {
            $text = substr($text, 0, $this->maxLengthSlug);
        }
        return $this->makeUnique($this->generateSlug([$text]));
    }

}
