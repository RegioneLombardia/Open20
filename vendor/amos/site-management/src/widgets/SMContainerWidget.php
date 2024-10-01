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
use amos\sitemanagement\models\SiteManageContElemPubblication;
use amos\sitemanagement\models\SiteManagementContainer;
use amos\sitemanagement\models\SiteManagementContainerElementMm;
use amos\sitemanagement\models\SiteManagementElement;
use amos\sitemanagement\models\SiteManagementSection;
use amos\sitemanagement\models\SiteManagementTemplate;
use amos\sitemanagement\models\SiteManagePubblicationType;
use amos\sitemanagement\assets\ModuleSiteManagementAsset;
use amos\sitemanagement\utility\SiteManagementUtility;
use yii\base\Widget;
use yii\db\Exception;
use yii\db\Expression;

/**
 * Class SMPageContentWidget
 * @package amos\sitemanagement\widgets
 */
class SMContainerWidget extends Widget
{
    /**
     * @var string $tag
     */
    private $section;

    /**
     * @var SiteManagementContainer $model
     */
    private $model;

    /**
     * @var
     */
    private $viewPath;
    private $defaultLimit = 5;
    private $options;

    /**
     * @throws SiteManagementException
     */
    public function init()
    {
        parent::init();

        if (is_null($this->section)) {
            throw new SiteManagementException('SMPageContainerWidget: missing secton');
        }

        if (!is_string($this->section)) {
            throw new SiteManagementException('SMPageContainerWidget: section is not a string');
        }

        $section = SiteManagementSection::find()->andWhere(['name' => $this->section])->one();
        if (!empty($section)) {

            $container = $section->siteManagementContainer;
            if (!empty($container)) {
                $this->model = $container;
            }
            $this->configureViewPath();
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
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param string $tag
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return string
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * @param string $tag
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $tag
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     *
     */
    public function configureViewPath()
    {
        $module = \Yii::$app->getModule('sitemanagement');
        if ($module) {
            if (empty($this->viewPath)) {
                $this->viewPath = $module->defaultContainerView;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $container = $this->model;

        ModuleSiteManagementAsset::register($this->getView());

        if ($container) {

            if ($container->getSiteManageContElemPubblications()->count() > 0) {
                return $this->renderContainer($container);
            }
        }
        return '';
    }

    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    public function renderContainer($container)
    {
        /** @var $container SiteManagementContainer */
        $elements = [];


        $query = $container->getSiteManageContElemPubblications()
            ->innerJoinWith('siteManagementContainerElementMms')
            ->select(['site_manage_cont_elem_pubblication.*', 'elem_order']);

        $finalQuery = null;
        if (\Yii::$app->user->isGuest) {
            $lang               = SiteManagementUtility::convertLanguage(\Yii::$app->language);
            $elementMmClassname = SiteManagementContainerElementMm::className();
            $elementMmClassname = addslashes($elementMmClassname);
            $query2             = clone $query;
            $query2->innerJoin('entitys_tags_mm',
                    "entitys_tags_mm.record_id=site_management_container_element_mm.id AND entitys_tags_mm.classname='$elementMmClassname'")
                ->leftJoin('tag', 'entitys_tags_mm.tag_id = tag.id')
                ->andWhere(['entitys_tags_mm.deleted_at' => null])
                ->andWhere(['site_management_container_element_mm.deleted_at' => null])
                ->andWhere(['site_manage_cont_elem_pubblication.deleted_at' => null])
                ->andWhere(['classname' => "amos\sitemanagement\models\SiteManagementContainerElementMm"]);

            $query3 = clone $query2;
            $query3->select('site_management_container_element_mm.id');

            $query4 = clone $query2;
            $query4->andWhere([
                'OR',
                ['entitys_tags_mm.tag_id' => NULL],
                ['codice' => $lang]
            ]);

            $query->andWhere(['NOT IN', 'site_management_container_element_mm.id', $query3]);
            $query      = $query4->union($query->createCommand()->rawSql);
            $query->orderBy('elem_order');
            $expression = new \yii\db\Expression("(".$query->createCommand()->rawSql.") as finalquery");
            $finalQuery = (new \yii\db\Query)
                ->select('*')
                ->from($expression)
                ->orderBy("finalquery.elem_order");
        } else {
            $query->orderBy('elem_order');
        }
        //pr($query->createCommand()->rawSql, 'prima');
        if (!empty($finalQuery)) {
            $query = $finalQuery;
        }
        //pr($query->createCommand()->rawSql, 'dopo');
        if (!empty($container->element_limit) && empty($container->element_random)) {
            $query->limit($container->element_limit);
        }

        /** @var  $pubblication SiteManageContElemPubblication */
        foreach ($query->all() as $pubblicationArray) {
            $pubblication = SiteManageContElemPubblication::findOne($pubblicationArray['id']);
            if ($this->checkPubblicationDate($pubblication) && $this->isVisibleToUser($pubblication)) {

                $elements [] = $pubblication->siteManagementElement;
            }
        }

        if ($container->element_random) {
            if (!empty($container->element_limit)) {
                $elements = $this->chooseRandomNElement($elements);
            }
        }
        return $this->render($this->viewPath,
                ['container' => $container, 'elements' => $elements, 'options' => $this->options]);
    }

    /**
     * @param $element
     * @param bool $withValues
     * @return string
     */
    public static function renderElementPreview($element)
    {
        $module = \Yii::$app->getModule('sitemanagement');
        if ($module) {
            $containerView = $module->defaultContainerView;
            return \Yii::$app->getView()->render($containerView, ['elements' => [$element]]);
        }
    }

    /**
     * @param $template_id
     * @return string
     */
    public static function renderTemplatePreview($template_id)
    {
        $template = SiteManagementTemplate::findOne($template_id);
        if ($template) {
            $element = new SiteManagementElement();
            $module  = \Yii::$app->getModule('sitemanagement');
            if ($module) {
                $containerView = $module->defaultContainerView;
                return \Yii::$app->getView()->render($containerView, ['elements' => [$element], 'template' => $template]);
            }
        }
        return '';
    }

    /**
     * Configure the array of value to render the elements with templates
     * @param $element SiteManagementElement
     * @return array
     */
    public static function getWidgetValuesFromElement($element)
    {
        $module        = \Yii::$app->getModule('sitemanagement');
        $widgetsValues = [];

        // Values if an element is linked to Content Model
        if (!empty($element->siteManagementTemplate->content_model) && !empty($module->getModuleOfContentModel($element->siteManagementTemplate->content_model))) {
            $classNameContent = $element->siteManagementTemplate->content_model;
            $modelContent     = $classNameContent::find()->andWhere(['id' => $element->content_model_id])->one();
            if ($modelContent) {
                $widgetsValues['title']       = $modelContent->getTitle();
                $widgetsValues['description'] = $modelContent->getShortDescription();
                if ($modelContent->getPublicatedFrom()) {
                    $widgetsValues['data'] = \Yii::$app->formatter->asDate($modelContent->getPublicatedFrom());
                } else {
                    $widgetsValues['data'] = \Yii::$app->formatter->asDate($modelContent->getPublicatedFrom());
                }
                if ($modelContent instanceof \open20\amos\core\interfaces\ModelImageInterface) {
                    $widgetsValues['image'] = $modelContent->getModelImageUrl('original', false);
                }

                $widgetsValues['link_forward'] = SMContainerWidget::getUrlContentModel($modelContent);
            }
            // values if the element values are on the database and are inserted manually by the user
        } else {
            $vals = $element->siteManagementContainerElemeFieldsVals;
            foreach ($vals as $val) {
                if ($val->siteManagementFieldsType->type == 'file') {
                    $widgetsValues[$val->siteManagementFieldsType->name] = !empty($val->getAttachFiles()) ? $val->getAttachFiles()->getWebUrl()
                            : '';
                } else {
                    $widgetsValues[$val->siteManagementFieldsType->name] = $val->value;
                }
            }
        }
        return $widgetsValues;
    }

    /**
     * @param $element
     * @return array
     */
    public static function getWidgetExampleValuesFromTemplate($template)
    {
        $widgetsValues = [];
        $fields        = $template->siteManagementFieldsTypes;
        foreach ($fields as $field) {
            if ($field->type == 'file') {
                $widgetsValues[$field->name] = '/img/img_default.jpg';
            } else {
                $widgetsValues[$field->name] = "{".$field->name."}";
            }
        }
        return $widgetsValues;
    }

    /**
     * @param $pubblication SiteManageContElemPubblication
     * @return bool
     */
    public function isVisibleToUser($pubblication)
    {
        $user_id = !empty(\Yii::$app->user->id) ? \Yii::$app->user->id : null;
        if ($pubblication->pubblication_type_id == 1) {
            return true;
        } else if ($pubblication->pubblication_type_id == 2) {
            $count = $pubblication->getUsers()->andWhere(['id' => $user_id])->count();
            return $count >= 1;
        } else {
            $roles = $pubblication->siteManageContElemPubblicationClasses;
            foreach ($roles as $role) {
                if (\Yii::$app->user->can($role->class)) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * @param $pubblication SiteManageContElemPubblication
     * @return bool
     */
    public function checkPubblicationDate($pubblication)
    {
        $today     = new \DateTime();
        $startDate = null;
        $endDate   = null;

        if ($pubblication->start_date) {
            $startDate = new \DateTime($pubblication->start_date);
        }

        if ($pubblication->end_date) {
            $endDate = new \DateTime($pubblication->end_date);
        }

        // if is not set a pubblication date is always visible
        // if is set only the start_date is visible from the date selected onward
        // if is set also the end-date check the complete interval
        if ($startDate && $today >= $startDate) {
            // if the hoour of pubblication is not inside the time interval you connot visualize the element
            if ($pubblication->start_time && $pubblication->end_time && !(date('H:i:s') >= $pubblication->start_time && date('H:i:s')
                <= $pubblication->end_time)) {
                return false;
            }
            if ($endDate) {
                return ($today <= $endDate);
            }
            return true;
        }
        return true;
    }

    /**
     * @param $elements
     * @return array
     */
    public function chooseRandomNElement($elements)
    {
        $selectedElements = [];
        $container        = $this->model;
        $max_elements     = $container->element_limit;
        if (empty($max_elements)) {
            $max_elements = $this->defaultLimit;
        }
        if (count($elements) <= $max_elements) {
            return $elements;
        }

        if ($max_elements == 1) {
            $rand_keys = rand(0, count($elements) - 1);
        } else {
            $rand_keys = array_rand($elements, $max_elements);
        }

        if (is_numeric($rand_keys)) {
            $selectedElements [] = $elements[$rand_keys];
        } else {
            foreach ($rand_keys as $key) {
                $selectedElements [] = $elements[$key];
            }
        }
        return $selectedElements;
    }

    /**
     * @param $modelContent
     * @return string
     */
    public static function getUrlContentModel($modelContent)
    {
        $classname = get_class($modelContent);
        $module    = \Yii::$app->getModule('sitemanagement');
        if ($module) {
            $key = array_search($classname, $module->contentModelsEnabled);
            if ($key) {
                if (!empty($module->urlDetailModelsEnabled[$key])) {
                    return $module->urlDetailModelsEnabled[$key].'?id='.$modelContent->id;
                }
            }
        }
        if ($modelContent instanceof \open20\amos\core\interfaces\ViewModelInterface) {
            return $modelContent->getFullViewUrl();
        } else return '';
    }
}