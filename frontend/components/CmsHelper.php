<?php

namespace app\components;

use luya\admin\models\Lang;
use luya\cms\models\NavItem;
use open20\design\utility\DesignUtility;
use Yii;
use yii\helpers\Html;
use open20\amos\core\record\CachedActiveQuery;

/**
 * Class CmsHelper
 * @package app\components
 */
class CmsHelper
{

    /**
     * render recursively luya menu.
     *
     * @param array $menu
     */
    public static function MenuRender($menu)
    {
        $html = '';
        foreach ($menu as $item) {
//            $nav_item = NavItem::findOne(['nav_id' => $nav->itemArray['nav_id']]);
//            $propertyPermissions = $nav->getProperty('rolePermissions');


            $html .= '<li class="'.($item->isActive ? ' active preopened' : '').($item->hasChildren ? ($item->depth == 1
                    ? ' dropdown' : ' dropdown-submenu') : '').'">';
            $html .= '<a class="'.($item->hasChildren ? ' nav-link dropdown-toggle' : '').'" '.($item->hasChildren ? ' data-toggle="dropdown"'
                    : '').' href="'.($item->hasChildren ? "javascript:void(0)" : $item->link).'">'.$item->title;
            $html .= $item->hasChildren ? '<span class="am am-chevron-down"></span>' : '';
            $html .= '</a>';
            if ($item->hasChildren) {
                $html .= '<ul class="dropdown-menu dropdown-menu-left">';
                $html .= static::MenuRender(Yii::$app->menu->getLevelContainer($item->depth + 1, $item));
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }

    /**
     *
     * @param type $itemId
     * @param type $version
     * @return type
     */
    public static function ContentRender($itemId, $version = false)
    {
        $rendered      = '';
        $controller    = Yii::$app->controller;
        $language_code = Yii::$app->composition['langShortCode'];


        $language = Lang::findOne(['short_code' => $language_code]);
        if (!is_null($language)) {
            $navItem = NavItem::findOne(['nav_id' => $itemId, 'lang_id' => $language->id]);
            if (!is_null($navItem)) {
                $rendered = $controller->renderItem($navItem->id, null, $version);
            }
        }

        return $rendered;
    }

    /**
     * @param $text
     * @param int $chars
     * @return bool|string
     */
    public static function truncate($text, $chars = 25)
    {
        if (strlen($text) <= $chars) {
            return $text;
        }
        $text = $text." ";
        $text = substr($text, 0, $chars);
        $text = substr($text, 0, strrpos($text, ' '));
        $text = $text."...";
        return $text;
    }

    /**
     * @param $menu
     * @param $iconSubmenu
     * @param null $assetBundle
     * @return string
     */
    public static function BiHamburgerSubmenuRender($menu, $iconSubmenu, $assetBundle = null)
    {
        $completeSubmenu = '';
        $listSubmenu     = '';
        $completeMenu    = '';
        $listMenu        = '';
        self::registerRecursiveBulletJs();

        foreach ($menu as $item) {
            $classReadonly = '';
            $title         = $item->title;
            $iconLock      = '';
            $href          = '/site/to-menu-url?url='.$item->link;

            $canPermission = self::canPermission($item);
            $isPublic      = self::isPublicMenu($item);
            $badge         = self::getBadge($item);

            $isReadonly = self::isReadonly($item, $canPermission);
            if ($isReadonly) {
                $href          = '/it/login';
                $classReadonly = 'menu-link-locked';
                $title         = \Yii::t("app", "per abilitare questo menu").' '.DesignUtility::getTextSigninOrSignup();
                $iconLock      = '<span class="mdi mdi-lock-outline icon-myopen ml-auto pl-1"></span>';
            }

            if (($isPublic == true && $canPermission == true) || $isReadonly) {
                if ($item->hasChildren && !$isReadonly) {
                    $listSubmenu .= Html::tag(
                            'li',
                            Html::a(
                                Html::tag('span', $item->title).$badge.$iconSubmenu, null,
                                [
                                'class' => 'nav-link dropdown-toggle'.' '.($item->isActive ? 'active' : ''),
                                'target' => '_self',
                                'title' => $item->title,
                                'href' => '#',
                                'data' => [
                                    'toggle' => (($item->hasChildren) ? 'dropdown' : '')
                                ],
                                'aria' => [
                                    'expanded' => 'false'
                                // 'controls' => 'menu' . $item->id
                                ]
                                ]
                            )
                            .Html::tag(
                                'div',
                                Html::tag(
                                    'div',
                                    Html::tag(
                                        'ul',
                                        static::BiHamburgerSubmenuRender(Yii::$app->menu->getLevelContainer($item->depth
                                                + 1, $item), $iconSubmenu),
                                        [
                                        'class' => 'link-list'
                                        ]
                                    ),
                                    [
                                    'class' => 'link-list-wrapper'
                                    ]
                                ),
                                [
                                'id' => 'menu'.$item->id,
                                'class' => 'dropdown-menu'
                                ]
                            ),
                            [
                            'class' => 'nav-item dropdown dropdown-submenu'.' '.($item->isActive ? 'active' : '')
                            ]
                    );
                } else {
                    $listSubmenu .= Html::tag(
                            'li',
                            Html::a(
                                Html::tag('span', $item->title).$badge.$iconLock, null,
                                [
                                'class' => $classReadonly.' list-item'.($item->isActive ? ' active' : ''),
                                'target' => '_self',
                                'title' => $title,
                                'href' => $href,
                                ]
                            ),
                            [
                            'class' => ($item->isActive ? ' active' : '')
                            ]
                    );
                }
            }
        }

        $completeSubmenu .= $listSubmenu;
        return $completeSubmenu;
    }

    /**
     * render recursively luya menu.
     * @param $menu
     * @param $iconSubmenu
     * @param bool $onlyFirstLevel
     * @param null $assetBundle
     * @return string
     */
    public static function BiHamburgerMenuRender($menu, $iconSubmenu, $onlyFirstLevel = false, $assetBundle = null)
    {
        $completeMenu = '';
        $listMenu     = '';
        self::registerRecursiveBulletJs();

        foreach ($menu as $item) {
            $classReadonly = '';
            $iconLock      = '';
            $href          = '/site/to-menu-url?url='.$item->link;
            $title         = $item->title;
            $dataToggle    = '';
            $canPermission = self::canPermission($item);
            $isPublic      = self::isPublicMenu($item);
            $badge         = self::getBadge($item);

            $isReadonly = self::isReadonly($item, $canPermission);
            if ($isReadonly) {
                $href          = \Yii::$app->params['linkConfigurations']['loginLinkCommon'];
                $classReadonly = 'menu-link-locked';
                $title         = \Yii::t("app", "per abilitare questo menu").' '.DesignUtility::getTextSigninOrSignup();
                $dataToggle    = 'tooltip';
                $iconLock      = '<span class="mdi mdi-lock-outline icon-myopen ml-auto pl-1"></span>';
            }

            if (($isPublic == true && $canPermission == true) || $isReadonly) {
                if ($item->hasChildren && !($onlyFirstLevel) && !$isReadonly) {
                    $listMenu .= Html::tag(
                            'li',
                            Html::a(
                                Html::tag('span', $item->title).$badge.$iconSubmenu, null,
                                [
                                'class' => 'nav-link dropdown-toggle'.' '.($item->isActive ? 'active' : ''),
                                'target' => '_self',
                                'title' => $item->title,
                                'href' => '#',
                                'data' => [
                                    'toggle' => (($item->hasChildren) ? 'dropdown' : '')
                                ],
                                'aria' => [
                                    'expanded' => 'false'
                                // 'controls' => 'menu' . $item->id
                                ]
                                ]
                            ).
                            Html::tag(
                                'div',
                                Html::tag(
                                    'div',
                                    Html::tag(
                                        'ul',
                                        static::BiHamburgerSubmenuRender(Yii::$app->menu->getLevelContainer($item->depth
                                                + 1, $item), $iconSubmenu, $assetBundle),
                                        [
                                        'class' => 'link-list'
                                        ]
                                    ),
                                    [
                                    'class' => 'link-list-wrapper'
                                    ]
                                ),
                                [
                                'id' => 'menu'.$item->id,
                                'class' => 'dropdown-menu'
                                ]
                            ),
                            [
                            'class' => 'nav-item dropdown'.' '.($item->isActive ? 'active' : '')
                            ]
                    );
                } else {
                    $listMenu .= Html::tag(
                            'li',
                            Html::a(
                                $item->title.$badge.$iconLock, null,
                                [
                                'class' => $classReadonly.' nav-link'.' '.($item->isActive ? 'active' : ''),
                                'target' => '_self',
                                'title' => $title,
                                'href' => $href,
                                'data-toggle' => $dataToggle,
                                'data-placement' => 'bottom',
                                // 'data-container'=> 'body',
                                // 'data-trigger' => 'hover',
                                'data-html' => 'true'
                                ]
                            ),
                            [
                            'class' => 'nav-item'.' '.($item->isActive ? 'active' : '')
                            ]
                    );
                }
            }
        }

        if ($item->depth == 1) {
            $completeMenu .= $listMenu;
        }

        return $completeMenu;
    }

    /**
     *
     * @param NavItem $item
     * @return boolean
     */
    public static function isPublicMenu($item)
    {
        $isPublic = true;
        if (\Yii::$app->user->isGuest) {

            $navQuery = \luya\cms\models\Nav::find()->andWhere(['id' => $item->getNavId()]);
            $navCache = CachedActiveQuery::instance($navQuery);
            $navCache->cache(60);
            $nav      = $navCache->one();

            $properties = $nav->properties; //pr($properties, 'prima');
            foreach ($properties as $v) {
                if (!empty($v->admin_prop_id) && $v->admin_prop_id == 7) {
                    if ($v->value == true) {
                        $isPublic = false;
                    }
                }
            }
        }
        return $isPublic;
    }

    /**
     * @param $item
     * @return bool
     */
    public static function canPermission($item)
    {
        $canPermission = true;

        $navQuery = \luya\cms\models\Nav::find()->andWhere(['id' => $item->getNavId()]);
        $navCache = CachedActiveQuery::instance($navQuery);
        $navCache->cache(60);
        $nav      = $navCache->one();


        $propertyPermissions = self::getProperty($nav, 'rolePermissions');

        if (!empty($propertyPermissions)) {
            $canPermission = $propertyPermissions->checkPermissions();
        }
        return $canPermission;
    }

    /**
     * @param $item
     * @return bool
     */
    public static function isAlwaysVisible($item)
    {
        $isAlwaysVisible = false;
        $idNav           = $item->getNavId();
        $navQuery        = \luya\cms\models\Nav::find()->andWhere(['id' => $idNav]);
        $navCache        = CachedActiveQuery::instance($navQuery);
        $navCache->cache(60);
        $nav             = $navCache->one();

        $propertyMenuReadonly = self::getProperty($nav, 'menuReadonly');

        if (!empty($propertyMenuReadonly)) {
            $isAlwaysVisible = $propertyMenuReadonly->value;
        }
        return $isAlwaysVisible;
    }

    public static function getProperty($nav, $varName)
    {
        $hasMany      = $nav->getProperties();
        $hasManyCache = CachedActiveQuery::instance($hasMany);
        $hasManyCache->cache(60);
        $properties   = $hasManyCache->all();
        foreach ($properties as $prop) {
            $hasOne      = $prop->getAdminProperty();
            $hasOneCache = CachedActiveQuery::instance($hasOne);
            $hasOneCache->cache(60);
            $relation    = $hasOneCache->one();

            if ($relation->var_name == $varName) {
                return self::getObject($prop);
            }
        }

        return false;
    }

    public static function getObject($prop)
    {

        $hasOne      = $prop->getAdminProperty();
        $hasOneCache = CachedActiveQuery::instance($hasOne);
        $hasOneCache->cache(60);
        $relation    = $hasOneCache->one();
        $object      = $relation->createObject($prop->value);

        return $object;
    }

    /**
     * @param $item
     * @param $canPermission
     * @return bool
     */
    public static function isReadonly($item, $canPermission)
    {
        if (self::isAlwaysVisible($item) && !$canPermission) {
            return true;
        }
        return false;
    }

    /**
     * @param $item
     * @return int
     */
    public static function getBulletCount($item)
    {
        $count = 0;

        if (\Yii::$app->user->isGuest) {
            return 0;
        }

        $navQuery = \luya\cms\models\Nav::find()->andWhere(['id' => $item->getNavId()]);
        $navCache = CachedActiveQuery::instance($navQuery);
        $navCache->cache(60);
        $nav      = $navCache->one();

        $propertyBulletCounts = self::getProperty($nav, 'bulletCounts');

        if (!empty($propertyBulletCounts)) {
            $count = $propertyBulletCounts->getCount();
        }
        return $count;
    }

    /**
     * @param $item
     * @return string
     */
    public static function getBadge($item)
    {
        $count        = self::getBulletCount($item);
        $badge        = '';
        $bulletParent = Html::tag('span', '', ['class' => 'bullet-parent']);

        if ($count > 0) {
            $badge = Html::tag('small', '', ['class' => 'badge badge-pill badge-danger bullet-navbar']);
        }
        return $bulletParent.$badge;
    }

    /**
     *
     */
    public static function registerRecursiveBulletJs()
    {
        $js = <<<JS

    var bullet = "<small class='badge badge-pill badge-danger bullet-navbar'></small>";

		var indice = 1;
    $('.cms-menu-container-default li .bullet-navbar').each(function(){
		if(indice < 2){
			indice++;

        var li = $(this).closest('li');
        $(li).parents('li').each(function(){
            $(this).children('a').children('.bullet-parent').html(bullet);
        });
        console.log(indice);
        var isBullet = $('.btn-always-hamburger-menu .bullet-navbar');
        if(isBullet.length === 0 || isBullet === undefined){
        $('.btn-always-hamburger-menu').append(bullet);
        }
			}
    });
JS;
        \Yii::$app->controller->view->registerJs($js);
    }
}