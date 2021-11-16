<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 18/02/2021
 * Time: 10:31
 */

namespace open20\design\utility;


use open20\design\Module;

class DesignUtility
{
    /**
     * @return string
     */
    public static function getTextSigninOrSignup(){
        return Module::t('amosdesign', "accedi o registrati alla piattaforma");
    }

    /**
     * @return array
     */
    public static function listViewPagerConfig(){
        $prevPaginatorIcon = DesignIcon::show('chevron-left', DesignIcon::ICON_MD, 'icon icon-sm');
        $nextPaginatorIcon = DesignIcon::show('chevron-right', DesignIcon::ICON_MD, 'icon icon-sm');
        $startPaginatorIcon = DesignIcon::show('chevron-double-left', DesignIcon::ICON_MD, 'icon icon-sm');
        $endPaginatorIcon = DesignIcon::show('chevron-double-right', DesignIcon::ICON_MD, 'icon icon-sm');

        return [
            'options' => [
                'class' => 'pagination'
            ],
            'pageCssClass' => 'page-item',
            'activePageCssClass' => 'border border-primary rounded',
            'nextPageCssClass' => 'page-item border border-white rounded',
            'prevPageCssClass' => 'page-item border border-white rounded',
            'firstPageCssClass' => 'page-item border border-white rounded',
            'lastPageCssClass' => 'page-item border border-white rounded',
            'prevPageLabel' => $prevPaginatorIcon,
            'nextPageLabel' => $nextPaginatorIcon,
            'firstPageLabel' => $startPaginatorIcon,
            'lastPageLabel' => $endPaginatorIcon,
            'linkOptions' => [
                'class' => 'page-link'
            ],
        ];
    }

    /**
     * @param bool $withSummary
     * @return string
     */
    public static function getLayoutSummary($withSummary = true){
        $listViewLayoutSummary = \Yii::$app->controller->view->render('@vendor/open20/design/src/components/yii2/views/parts/_listViewLayoutSummary');
        $listViewLayoutSummaryWithoutPagination = \Yii::$app->controller->view->render('@vendor/open20/design/src/components/yii2/views/parts/_listViewLayoutSummaryWithoutPagination');

        if($withSummary){
            return $listViewLayoutSummary;
        }
        return $listViewLayoutSummaryWithoutPagination;
    }


}