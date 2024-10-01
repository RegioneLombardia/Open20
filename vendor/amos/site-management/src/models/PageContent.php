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

use amos\sitemanagement\i18n\grammar\PageContentGrammar;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use yii\helpers\Url;

/**
 * Class PageContent
 * This is the model class for table "site_management_page_content".
 * @package amos\sitemanagement\models
 */
class PageContent extends \amos\sitemanagement\models\base\PageContent implements ViewModelInterface, ModelLabelsInterface
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'title'
        ];
    }

    /**
     * Return the columns to show as default in GridViewWidget
     * @return array
     */
    public function getGridViewColumns()
    {
        return [
            'title',
            'section.name',
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
        return "sitemanagement/page-content/view";
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
        return new PageContentGrammar();
    }
}
