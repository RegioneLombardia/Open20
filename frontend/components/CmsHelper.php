<?php

namespace app\components;

use yii\helpers\Html;
use luya\admin\models\Lang;
use luya\cms\models\NavItem;
use Yii;

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
            $html .= '<li class="' . ($item->isActive ? ' active preopened' : '') . ($item->hasChildren ? ($item->depth == 1 ? ' dropdown' : ' dropdown-submenu') : '') . '">';
            $html .= '<a class="' . ($item->hasChildren ? ' nav-link dropdown-toggle' : '') . '" ' . ($item->hasChildren ? ' data-toggle="dropdown"' : '') . ' href="' . ($item->hasChildren ? "javascript:void(0)" : $item->link) . '">' . $item->title;
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
     * render recursively luya menu.
     * 
     * @param array $menu
     */
    public static function BiHamburgerSubmenuRender($menu)
    {
        $completeSubmenu = '';
        $listSubmenu = '';

        foreach ($menu as $item) {
            if ($item->hasChildren) {
                $listSubmenu .= Html::tag(
                    'li',
                    Html::a(
                        $item->title,
                        null,
                        [
                            'class' => 'list-item' . ($item->isActive ? ' active' : ''),
                            'target' => '_self',
                            'title' => $item->title,
                            'href' => $item->link,
                        ]
                    ) .
                        Html::tag(
                            'ul',
                            static::BiHamburgerSubmenuRender(Yii::$app->menu->getLevelContainer($item->depth + 1, $item)),
                            [
                                'class' => 'link-sublist'
                            ]
                        ),
                    [
                        'class' => ($item->isActive ? ' active' : '')
                    ]
                );
            } else {
                $listSubmenu .= Html::tag(
                    'li',
                    Html::a(
                        $item->title,
                        null,
                        [
                            'class' => 'list-item' . ($item->isActive ? ' active' : ''),
                            'target' => '_self',
                            'title' => $item->title,
                            'href' => $item->link,
                        ]
                    ),
                    [
                        'class' => ($item->isActive ? ' active' : '')
                    ]
                );
            }
        }

        if ($item->depth == 1) {
            $completeSubmenu .= $listSubmenu;
        }

        return $completeSubmenu;
    }

    /**
     * render recursively luya menu.
     * 
     * @param array $menu
     */
    public static function BiHamburgerMenuRender($menu, $iconSubmenu, $customContainerClass = '', $onlyFirstLevel = false)
    {
        $completeMenu = '';
        $listMenu = '';

        foreach ($menu as $item) {
            if ($item->hasChildren && !($onlyFirstLevel)) {
                $listMenu .= Html::tag(
                    'li',
                    Html::a(
                        Html::tag('span', $item->title) . $iconSubmenu,
                        null,
                        [
                            'class' => 'nav-link dropdown-toggle' . ' ' . ($item->isActive ? 'active' : '') . ' ' . 'large medium right-icon',
                            'target' => '_self',
                            'title' => $item->title,
                            'href' => '#menu' . $item->id,
                            'data' =>
                            [
                                'toggle' => (($item->hasChildren) ? 'collapse' : '')
                            ],
                            'aria' =>
                            [
                                'expanded' => 'false',
                                'controls' => 'menu' . $item->id
                            ]
                        ]
                    ) .
                        Html::tag(
                            'div',
                            Html::tag(
                                'div',
                                Html::tag(
                                    'ul',
                                    static::BiHamburgerSubmenuRender(Yii::$app->menu->getLevelContainer($item->depth + 1, $item)),
                                    [
                                        'class' => 'link-list'
                                    ]
                                ),
                                [
                                    'class' => 'link-list-wrapper'
                                ]
                            ),
                            [
                                'class' => 'dropdown-menu'
                            ]
                        ),
                    [
                        'class' => 'nav-item dropdown' . ' ' . ($item->isActive ? 'active' : '')
                    ]
                );
            } else {
                $listMenu .= Html::tag(
                    'li',
                    Html::a(
                        $item->title,
                        null,
                        [
                            'class' => 'nav-link' . ' ' . ($item->isActive ? 'active' : ''),
                            'target' => '_self',
                            'title' => $item->title,
                            'href' => $item->link,
                        ]
                    ),
                    [
                        'class' => 'nav-item' . ' ' . ($item->isActive ? 'active' : '')
                    ]
                );
            }
        }

        if ($item->depth == 1) {
            $completeMenu .= $listMenu;
        }
        return $completeMenu;
    }

    /**
     *
     * @param type $itemId
     * @param type $version
     * @return type
     */
    public static function ContentRender($itemId, $version = false)
    {
        $rendered = '';
        $controller = Yii::$app->controller;
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
        $text = $text . " ";
        $text = substr($text, 0, $chars);
        $text = substr($text, 0, strrpos($text, ' '));
        $text = $text . "...";
        return $text;
    }
}
