<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

namespace open20\amos\slideshow\models;

/**
 * Class SlideshowPage
 * @package open20\amos\slideshow\models
 *
 * This is the model class for table "slideshow_pages".
 */
class SlideshowPage extends \open20\amos\slideshow\models\base\SlideshowPage
{
    public function representingColumn(){
        return [
            'name',
        ];
    }
}

