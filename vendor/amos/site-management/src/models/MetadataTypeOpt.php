<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\models
 * @category   CategoryName
 */

namespace amos\sitemanagement\models;

use yii\base\BaseObject;

/**
 * Class MetadataTypeOpt
 * @package amos\sitemanagement\models
 */
class MetadataTypeOpt extends BaseObject
{
    /**
     * This method returns the correct metadata key value attribute to use in the registerMetadata function.
     * @param int $metadataType
     * @return string
     */
    public static function getMetadataKeyValueAttribute($metadataType)
    {
        $conf = [
            MetadataType::TYPE_HTML => 'name',
            MetadataType::TYPE_OPEN_GRAPH => 'property'
        ];
        return $conf[$metadataType];
    }
}
