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

/**
 * Class MetadataType
 * This is the model class for table "site_management_metadata_type".
 * @package amos\sitemanagement\models
 */
class MetadataType extends \amos\sitemanagement\models\base\MetadataType
{
    const TYPE_HTML = 1;
    const TYPE_OPEN_GRAPH = 2;

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'type'
        ];
    }
}
