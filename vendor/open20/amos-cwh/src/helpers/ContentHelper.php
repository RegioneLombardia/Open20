<?php

namespace open20\amos\cwh\helpers;

use open20\amos\cwh\helpers\base\BaseEntitiesHelper;

class ContentHelper extends BaseEntitiesHelper
{
    public static function getEntities($interfaceClassname = '\open20\amos\core\interfaces\ContentModelInterface')
    {
        return parent::getEntities($interfaceClassname);
    }

}