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

use amos\sitemanagement\i18n\grammar\MetadataGrammar;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use yii\helpers\Url;

/**
 * Class Metadata
 * This is the model class for table "site_management_metadata".
 * @package amos\sitemanagement\models
 */
class Metadata extends \amos\sitemanagement\models\base\Metadata implements ViewModelInterface, ModelLabelsInterface
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'key_value'
        ];
    }

    /**
     * Return the columns to show as default in GridViewWidget
     * @return array
     */
    public function getGridViewColumns()
    {
        return [
            'metadataType.type',
            'key_value',
            'content',
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return "sitemanagement/metadata/view";
    }

    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        return Url::toRoute(["/" . $this->getViewUrl(), "id" => $this->id]);
    }

    /**
     * @return mixed
     */
    public function getGrammar()
    {
        return new MetadataGrammar();
    }
}
