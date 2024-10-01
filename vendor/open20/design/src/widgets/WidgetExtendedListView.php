<?php

namespace open20\design\widgets;

use yii\helpers\ArrayHelper;

class WidgetExtendedListView extends \yii\widgets\ListView {

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
        $pagerClass = (!is_null($this->pagerClass)) ? $this->pagerClass : WidgetExtendedLinkPager::className();
        $class = ArrayHelper::remove($pager, 'class', $pagerClass);
        $pager['pagination'] = $pagination;
        $pager['view'] = $this->getView();

        return $class::widget($pager);
    }
}