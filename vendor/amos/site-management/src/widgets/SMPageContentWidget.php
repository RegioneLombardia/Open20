<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\widgets
 * @category   CategoryName
 */

namespace amos\sitemanagement\widgets;

use amos\sitemanagement\exceptions\SiteManagementException;
use amos\sitemanagement\models\PageContent;
use amos\sitemanagement\models\SiteManagementSection;
use yii\base\Widget;

/**
 * Class SMPageContentWidget
 * @package amos\sitemanagement\widgets
 */
class SMPageContentWidget extends Widget
{
    /**
     * @var string $layout
     */
    public $layout = '{pageContent}';

    /**
     * @var string $tag
     */
    private $tag;

    /**
     * @var string $tag
     */
    private $section;

    /**
     * @var PageContent $model
     */
    private $model;

    /**
     * @throws SiteManagementException
     */
    public function init()
    {
        parent::init();

//        if (is_null($this->tag)) {
//            throw new SiteManagementException('SMPageContentWidget: missing tag');
//        }
//
//        if (!is_string($this->tag)) {
//            throw new SiteManagementException('SMPageContentWidget: tag is not a string');
//        }

        $section = SiteManagementSection::find()->andWhere(['name' => $this->section])->one();
        if($section){
            $this->model = PageContent::findOne(['section_id' => $section->id]);
        //for retrocompatibility
        }else {
            $this->model = PageContent::findOne(['tag' => $this->tag]);
        }
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @param string $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }




    /**
     * @inheritdoc
     */
    public function run()
    {
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);
            return $content === false ? $matches[0] : $content;
        }, $this->layout);
        return $content;
    }

    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{pageContent}':
                return $this->renderPageContent();
            default:
                return false;
        }
    }

    /**
     * @return string
     */
    public function renderPageContent()
    {
        if (is_null($this->model) || !strlen($this->model->content)) {
            return '';
        }
        return $this->model->content;
    }
}
