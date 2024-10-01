<?php

namespace open20\amos\core\views;

use open20\design\widgets\WidgetExtendedLinkPager;
use yii\helpers\ArrayHelper;
use yii\web\Link;
use yii\widgets\LinkPager;

class ExtendedIconView extends IconView {
    public $pagerClass;

    /**
     * Renders the pager.
     * @return string the rendering result
     */
    public function renderPager()
    {
        $pagination = $this->dataProvider->getPagination();
        if ($pagination === false || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        /* @var $class WidgetExtendedLinkPager */
        $pager = $this->pager;
        $defaultPagerClass = (class_exists('open20\design\widgets\WidgetExtendedLinkPager')) ? WidgetExtendedLinkPager::className() : LinkPager::className();
        $pagerClass = (!is_null($this->pagerClass)) ? $this->pagerClass : $defaultPagerClass;
        $class = ArrayHelper::remove($pager, 'class', $pagerClass);
        $pager['pagination'] = $pagination;
        $pager['view'] = $this->getView();

        return $class::widget($pager);
    }
}