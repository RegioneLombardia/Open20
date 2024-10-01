<?php

/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 18/02/2021
 * Time: 10:31
 */

namespace open20\design\utility;


use open20\cms\dashboard\models\CmsDashSidebarItem;
use open20\design\Module;
use yii\helpers\ArrayHelper;

class DesignUtility
{
    /** @var string */
    const JAVASCRIPT_VOID_VALUE = 'javascript:void(0);';

    /** @var array[] $modalsMapping per renderizzare sidebar dinamica redattore.
     *  Chiave: id dell'oggetto cms-dash-sidebar-item
     *  Valori: id modale di riferimento, nome del file per renderizzarla
     */
    private static $modalsMapping = [
        'gestione-portali' => ['#modalMyPlatform', '_modal-gestione-portali']
    ];

    /**
     * @return string
     */
    public static function getTextSigninOrSignup()
    {
        return Module::t('amosdesign', "accedi o registrati alla piattaforma");
    }

    /**
     * @return array
     */
    public static function listViewPagerConfig()
    {
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
    public static function getLayoutSummary($withSummary = true)
    {
        $listViewLayoutSummary = \Yii::$app->controller->view->render('@vendor/open20/design/src/components/yii2/views/parts/_listViewLayoutSummary');
        $listViewLayoutSummaryWithoutPagination = \Yii::$app->controller->view->render('@vendor/open20/design/src/components/yii2/views/parts/_listViewLayoutSummaryWithoutPagination');

        if ($withSummary) {
            return $listViewLayoutSummary;
        }
        return $listViewLayoutSummaryWithoutPagination;
    }

    /**
     * Funzione per risalire al plugin di un widget dato il suo namespace
     * @param $widgetPath
     * @return false|string[]
     */
    public static function getWidgetPlugin($widgetPath)
    {
        $partialPath = substr($widgetPath, 0, strpos($widgetPath, '\widgets'));
        $plugin = end(explode('\\', $partialPath));
        return $plugin;
    }

    public static function bytesFormat($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } else {
            $bytes = $bytes . ' byte';
        }

        return $bytes;
    }

    /**
     * @param $roles array
     * @param $item CmsDashSidebarItem
     * @return bool
     */
    public static function cmsSidebarRedattoreItemCanRender($roles, $item) {
        if(isset($item->isRolesListActive) && $item->isRolesListActive) {
            $emptyRolesIntersection = empty(array_intersect($roles, $item->roles_mm));
            if($item->rolesListType == CmsDashSidebarItem::TYPE_WHITELIST) {
                return !$emptyRolesIntersection;
            }
            else {
                return $emptyRolesIntersection;
            }
        }
        return true;
    }

    /**
     * @param $view \Yii\web\View Checks permission to show the sidebar
     * @return array
     */
    public static function showSidebarRedattore($view) {
        $a = \Yii::$app->view->params['showSidebarRedattore'];
        $b = \Yii::$app->params['layoutConfigurations']['showSidebarRedattore'];

        if ((isset($a) || isset($b)) && !\Yii::$app->user->isGuest) {
            $check = 0;
            if (\Yii::$app->user->can('ADMIN')) {
                $check = 1;
            }
            if (isset(\Yii::$app->params['layoutConfigurations']['showSidebarRedattorePermission'])) {
                $permissions = \Yii::$app->params['layoutConfigurations']['showSidebarRedattorePermission'];
                foreach ($permissions as $pr) {
                    if (\Yii::$app->user->can($pr)) {
                        $check = 1;
                    }
                }
            }
            if ($check == 1) {
                $var = (isset($a)) ? $a : $b;
                $showSidebarRedattore = ($var);
            }
            $openSidebarRedattoreOnLoadPage = (!empty($view->params['openSidebarRedattoreOnLoadPage'])) ? $view->params['openSidebarRedattoreOnLoadPage'] : false;
        } else {
            $showSidebarRedattore = false;
            $openSidebarRedattoreOnLoadPage = false;
        }
        return [$showSidebarRedattore, $openSidebarRedattoreOnLoadPage];
    }

    public static function cmsSidebarRedattoreGetModalInfoById($key) {
        if(isset(self::$modalsMapping[$key])) {
            return self::$modalsMapping[$key];
        } else {
            return null;
        }
    }
}
