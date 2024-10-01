<?php

namespace app\components;

use open20\amos\admin\models\UserProfileClassesAuthMm;
use yii\helpers\Html;
use Yii;
use open20\design\utility\DesignUtility;
use open20\amos\core\record\CachedActiveQuery;
use yii\db\Query;
use luya\admin\models\Property;
use yii\helpers\ArrayHelper;
use luya\cms\models\NavContainer;

class CmsMenu {

    /**
     *
     * @var array $arrLvl
     */
    public $arrLvl = [];

    /**
     * PER ORA BLOCCATO A MAX 6 LIVELLI, SI PUO' MAGARI RENDERE DINAMICO
     * @param string $name
     * @param string $iconSubmenu
     * @param bool $onlyFirstLevel
     * @param type $assetBundle
     * @return string
     */
    public function luyaMenu($name, $iconSubmenu, $onlyFirstLevel = false, $assetBundle = null, $expanded = false) {
        $propQuery = Property::find()->andWhere(['in', 'var_name', [
                        'userAuthProtection', 'rolePermissions', 'bulletCounts',
                        'menuReadonly'
            ]])->select(['var_name', 'id']);
        $propCache = CachedActiveQuery::instance($propQuery);
        $propCache->cache();

        $defaultLanguage = Yii::$app->db->createCommand('SELECT id FROM admin_lang where is_default=1')->queryOne();
        /**
         * @var array $prop
         *
         *     [var_name] => id
         *     [bulletCounts] => 9
         *     [menuReadonly] => 10
         *     [rolePermissions] => 8
         *     [userAuthProtection] => 7
         */
        $props = ArrayHelper::map($propCache->asArray()->all(), 'var_name', 'id');

        $queryMenu = NavContainer::find()->alias('c');

        $queryMenu->select([
                    'i.id id0', 'i1.id id1', 'i2.id id2', 'i3.id id3', 'i4.id id4', 'i5.id id5',
                    'i.alias alias0', 'i1.alias alias1', 'i2.alias alias2', 'i3.alias alias3', 'i4.alias alias4', 'i5.alias alias5',
                    'i.title title0', 'i1.title title1', 'i2.title title2', 'i3.title title3', 'i4.title title4', 'i5.title title5',
                    'red.value link0', 'red1.value link1', 'red2.value link2', 'red3.value link3', 'red4.value link4', 'red5.value link5',
                    'red.target target0', 'red1.target target1', 'red2.target target2', 'red3.target target3', 'red4.target target4', 'red5.target target5'
                ])
                ->leftJoin(
                        'cms_nav n',
                        'n.nav_container_id = c.id and n.parent_nav_id = 0 and n.is_deleted = 0 and n.is_hidden = 0 and n.is_offline = 0 and n.is_draft = 0'
                )
                ->leftJoin(
                        'cms_nav n1',
                        'n1.parent_nav_id = n.id and n1.is_deleted = 0 and n1.is_hidden = 0 and n1.is_offline = 0 and n1.is_draft = 0'
                )
                ->leftJoin(
                        'cms_nav n2',
                        'n2.parent_nav_id = n1.id and n2.is_deleted = 0 and n2.is_hidden = 0 and n2.is_offline = 0 and n2.is_draft = 0'
                )
                ->leftJoin(
                        'cms_nav n3',
                        'n3.parent_nav_id = n2.id and n3.is_deleted = 0 and n3.is_hidden = 0 and n3.is_offline = 0 and n3.is_draft = 0'
                )
                ->leftJoin(
                        'cms_nav n4',
                        'n4.parent_nav_id = n3.id and n4.is_deleted = 0 and n4.is_hidden = 0 and n4.is_offline = 0 and n4.is_draft = 0'
                )
                ->leftJoin(
                        'cms_nav n5',
                        'n5.parent_nav_id = n4.id and n5.is_deleted = 0 and n5.is_hidden = 0 and n5.is_offline = 0 and n5.is_draft = 0'
                )
                ->leftJoin('cms_nav_item i', 'i.nav_id = n.id and i.lang_id = ' . $defaultLanguage['id'])
                ->leftJoin('cms_nav_item i1', 'i1.nav_id = n1.id and i1.lang_id = ' . $defaultLanguage['id'])
                ->leftJoin('cms_nav_item i2', 'i2.nav_id = n2.id and i2.lang_id = ' . $defaultLanguage['id'])
                ->leftJoin('cms_nav_item i3', 'i3.nav_id = n3.id and i3.lang_id = ' . $defaultLanguage['id'])
                ->leftJoin('cms_nav_item i4', 'i4.nav_id = n4.id and i4.lang_id = ' . $defaultLanguage['id'])
                ->leftJoin('cms_nav_item i5', 'i5.nav_id = n5.id and i5.lang_id = ' . $defaultLanguage['id']);

        if (!empty($props['rolePermissions'])) {
            $queryMenu->addSelect(['p.value permission0', 'p1.value permission1', 'p2.value permission2', 'p3.value permission3', 'p4.value permission4', 'p5.value permission5'])
                    ->leftJoin('cms_nav_property p', 'p.nav_id = n.id and p.admin_prop_id = ' . $props['rolePermissions'])
                    ->leftJoin('cms_nav_property p1', 'p1.nav_id = n1.id and p1.admin_prop_id = ' . $props['rolePermissions'])
                    ->leftJoin('cms_nav_property p2', 'p2.nav_id = n2.id and p2.admin_prop_id = ' . $props['rolePermissions'])
                    ->leftJoin('cms_nav_property p3', 'p3.nav_id = n3.id and p3.admin_prop_id = ' . $props['rolePermissions'])
                    ->leftJoin('cms_nav_property p4', 'p4.nav_id = n4.id and p4.admin_prop_id = ' . $props['rolePermissions'])
                    ->leftJoin('cms_nav_property p5', 'p5.nav_id = n5.id and p5.admin_prop_id = ' . $props['rolePermissions']);
        }
        if (!empty($props['userAuthProtection'])) {
            $queryMenu->addSelect(['pp.value isProtected0', 'pp1.value isProtected1', 'pp2.value isProtected2', 'pp3.value isProtected3', 'pp4.value isProtected4', 'pp5.value isProtected5'])
                    ->leftJoin('cms_nav_property pp',
                            'pp.nav_id = n.id and pp.admin_prop_id = ' . $props['userAuthProtection'])
                    ->leftJoin(
                            'cms_nav_property pp1',
                            'pp1.nav_id = n1.id and pp1.admin_prop_id = ' . $props['userAuthProtection']
                    )
                    ->leftJoin(
                            'cms_nav_property pp2',
                            'pp2.nav_id = n2.id and pp2.admin_prop_id = ' . $props['userAuthProtection']
                    )
                    ->leftJoin(
                            'cms_nav_property pp3',
                            'pp3.nav_id = n3.id and pp3.admin_prop_id = ' . $props['userAuthProtection']
                    )
                    ->leftJoin(
                            'cms_nav_property pp4',
                            'pp4.nav_id = n4.id and pp4.admin_prop_id = ' . $props['userAuthProtection']
                    )
                    ->leftJoin(
                            'cms_nav_property pp5',
                            'pp5.nav_id = n5.id and pp5.admin_prop_id = ' . $props['userAuthProtection']
            );
        }
        if (!empty($props['bulletCounts'])) {
            $queryMenu->addSelect(['b.value bullet0', 'b1.value bullet1', 'b2.value bullet2', 'b3.value bullet3', 'b4.value bullet4', 'b5.value bullet5'])
                    ->leftJoin('cms_nav_property b', 'b.nav_id = n.id and b.admin_prop_id = ' . $props['bulletCounts'])
                    ->leftJoin('cms_nav_property b1', 'b1.nav_id = n1.id and b1.admin_prop_id = ' . $props['bulletCounts'])
                    ->leftJoin('cms_nav_property b2', 'b2.nav_id = n2.id and b2.admin_prop_id = ' . $props['bulletCounts'])
                    ->leftJoin('cms_nav_property b3', 'b3.nav_id = n3.id and b3.admin_prop_id = ' . $props['bulletCounts'])
                    ->leftJoin('cms_nav_property b4', 'b4.nav_id = n4.id and b4.admin_prop_id = ' . $props['bulletCounts'])
                    ->leftJoin('cms_nav_property b5', 'b5.nav_id = n5.id and b5.admin_prop_id = ' . $props['bulletCounts']);
        }


        if (!empty($props['menuReadonly'])) {
            $queryMenu->addSelect(['r.value readOnly0', 'r1.value readOnly1', 'r2.value readOnly2', 'r3.value readOnly3', 'r4.value readOnly4', 'r5.value readOnly5'])
                    ->leftJoin('cms_nav_property r', 'r.nav_id = n.id and r.admin_prop_id = ' . $props['menuReadonly'])
                    ->leftJoin('cms_nav_property r1', 'r1.nav_id = n1.id and r1.admin_prop_id = ' . $props['menuReadonly'])
                    ->leftJoin('cms_nav_property r2', 'r2.nav_id = n2.id and r2.admin_prop_id = ' . $props['menuReadonly'])
                    ->leftJoin('cms_nav_property r3', 'r3.nav_id = n3.id and r3.admin_prop_id = ' . $props['menuReadonly'])
                    ->leftJoin('cms_nav_property r4', 'r4.nav_id = n4.id and r4.admin_prop_id = ' . $props['menuReadonly'])
                    ->leftJoin('cms_nav_property r5', 'r5.nav_id = n5.id and r5.admin_prop_id = ' . $props['menuReadonly']);
        }
        $queryMenu->leftJoin('cms_nav_item_redirect red', 'red.id = i.nav_item_type_id AND i.nav_item_type = 3')
                ->leftJoin('cms_nav_item_redirect red1', 'red1.id = i1.nav_item_type_id AND i1.nav_item_type = 3')
                ->leftJoin('cms_nav_item_redirect red2', 'red2.id = i2.nav_item_type_id AND i2.nav_item_type = 3')
                ->leftJoin('cms_nav_item_redirect red3', 'red3.id = i3.nav_item_type_id AND i3.nav_item_type = 3')
                ->leftJoin('cms_nav_item_redirect red4', 'red4.id = i4.nav_item_type_id AND i4.nav_item_type = 3')
                ->leftJoin('cms_nav_item_redirect red5', 'red5.id = i5.nav_item_type_id AND i5.nav_item_type = 3')
                ->where(['c.alias' => $name])
                ->andWhere(['c.is_deleted' => 0])
                ->orderBy('n.sort_index,n1.sort_index,n2.sort_index,n3.sort_index,n4.sort_index,n5.sort_index');

        /*
          $menuCache = CachedActiveQuery::instance($queryMenu);
          $menuCache->cache();
          $allMenu = $menuCache->asArray()->all();
         */
        $allMenu = $queryMenu->asArray()->all();

        $enableDoubleAction = (isset(\Yii::$app->params['layoutConfigurations']['enableMenuDoubleAction'])) ? \Yii::$app->params['layoutConfigurations']['enableMenuDoubleAction'] : false;

        $menuHtml = $this->arrayBiHamburgerMenuRender($allMenu, $iconSubmenu, $onlyFirstLevel, $assetBundle, 0, $expanded, $enableDoubleAction);

        return $menuHtml;
    }

    /**
     *
     * @param array $row
     * @param int $indx
     * @return boolean
     */
    public static function isReadOnlyMenu($row, $indx) {
        if (self::isAlwaysVisibleMenu($row, $indx) && (!self::canPermissionMenu($row, $indx) || !self::isPublic($row, $indx))) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param array $row
     * @param int $indx
     * @return boolean
     */
    public static function isAlwaysVisibleMenu($row, $indx) {
        if (!empty($row['readOnly' . $indx])) {
            return $row['readOnly' . $indx];
        }
        return false;
    }

    /**
     *
     * @param array $row
     * @param int $indx
     * @return boolean
     */
    public static function canPermissionMenu($row, $indx) {

        if (!empty($row['permission' . $indx])) {

            // Se l'utente è ADMIN può vedere la pagina a prescindere
            if(Yii::$app->user->can('ADMIN')) {
                return true;
            }

            $enableProfile = false;
            $moduleDashboard = \Yii::$app->getModule('dashboards');
            if (!empty($moduleDashboard)) {
                if ($moduleDashboard->hasProperty('enableProfile') && $moduleDashboard->enableProfile) {
                    $enableProfile = true;
                }
            }

            $permissions = json_decode($row['permission' . $indx]);

            if($enableProfile) { // Profili abilitati

                $values = []; // Array dove ogni chiave è un profilo con i relativi permessi
                foreach($permissions as $profileId) {
                    $permissionsProfile = UserProfileClassesAuthMm::find()
                        ->select('item_id')
                        ->andWhere(['user_profile_classes_id' => $profileId->value])
                        ->asArray()
                        ->all();
                    $permissionsProfile = ArrayHelper::map($permissionsProfile, 'item_id', 'item_id');
                    $values[] = $permissionsProfile;
                }

                if (!\Yii::$app->user->isGuest) {
                    foreach ($values as $profile) {
                        $canAccess = true;
                        foreach ($profile as $permission) {
                            $can = \Yii::$app->user->can($permission);
                            if (!$can) {
                                $canAccess = false;
                                break;
                            }
                        }
                        if($canAccess) {
                            return true;
                        }
                    }
                    if($canAccess) {
                        return true;
                    }
                }
                // Se si arriva qui, nessun permesso è stato trovato associato all'utente
                return false;
            } else { // Profili non abilitati
                foreach ($permissions as $permission) {
                    if (\Yii::$app->user->can($permission->value)) {
                        return true;
                    }
                }
                return false;
            }
        }
        return true;
    }

    /**
     *
     * @param array $row
     * @param int $indx
     * @return boolean
     */
    public static function isPublic($row, $indx) {
        $isPublic = true;
        if (\Yii::$app->user->isGuest) {
            if (!empty($row['isProtected' . $indx])) {
                if ($row['isProtected' . $indx] == true) {
                    $isPublic = false;
                }
            }
        }
        return $isPublic;
    }

    /**
     *
     * @param array $row
     * @param int $indx
     * @return int
     */
    public static function getBulletCountMenu($row, $indx) {
        $count = 0;

        if (\Yii::$app->user->isGuest) {
            return 0;
        }

        $bc = $row['bullet' . $indx];
        if (!empty($bc)) {
            $explode = explode('-', $bc);
            if (count($explode) == 2) {
                $table = $explode[0];
                $bulletType = $explode[1];
                $theCount = \open20\amos\core\record\Record::getStaticBullet($bulletType, false, $table, true);
                $count = is_null($theCount) ? 0 : $theCount;
            }
        }

        return $count;
    }

    /**
     *
     * @param array $row
     * @param int $indx
     * @return string
     */
    public static function getBadgeMenu($row, $indx) {
        $count = self::getBulletCountMenu($row, $indx);
        $badge = '';
        $bulletParent = Html::tag('span', '', ['class' => 'bullet-parent']);

        if ($count > 0) {
            $badge = Html::tag('small', '', ['class' => 'badge badge-pill badge-danger bullet-navbar']);
        }
        return $bulletParent . $badge;
    }

    /**
     *
     * @param array $array
     * @param int $indx
     * @return boolean
     */
    public static function hasChildrenMenu($array, $indx) {
        if (!empty($array['alias' . ($indx + 1)])) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param array $row
     * @param int $indx
     * @return string
     */
    public static function getLinkByParent($row, $indx) {
        $urlArr = [];
        $i = 0;
        while ($i <= $indx) {
            $urlArr[] = $row['alias' . $i];
            $i++;
        }
        return '/' . \Yii::$app->composition['langShortCode'] . '/' . implode('/', $urlArr);
    }

    /**
     *
     * @param array $row
     * @param int $indx
     * @return string
     */
    public static function getLinkTarget($row, $indx) {
        $link = $row['link' . $indx];
        if (!empty($row['target' . $indx]) && is_numeric($row['target' . $indx])) {
            $link = self::recursiveLink($row['target' . $indx]);
        } else if (!empty($row['link' . $indx]) && is_numeric($row['link' . $indx])) {
            $link = self::recursiveLink($row['link' . $indx]);
        }
        return $link;
    }

    /**
     *
     * @param int $target
     * @return string
     */
    public static function recursiveLink($target) {
        $link = '';
        $item = \luya\cms\models\NavItem::find()->andWhere(['nav_id' => $target])->one();
        if (!empty($item)) {
            if ($item->nav_item_type != 3) {
                $link = '/' . \Yii::$app->composition['langShortCode'] . '/' . $item->alias;
            } else {
                $itemRedir = \luya\cms\models\NavItemRedirect::findOne($item->nav_item_type_id);
                $link = $itemRedir->resolveValue();
            }
        }
        return $link;
    }

    /**
     *
     * @param array $menu
     * @param string $iconSubmenu
     * @param bool $onlyFirstLevel
     * @param type $assetBundle
     * @param int $indx
     * @return string
     */
    public function arrayBiHamburgerMenuRender(
            $menu,
            $iconSubmenu,
            $onlyFirstLevel = false,
            $assetBundle = null,
            $indx = 0,
            $expanded = false,
            $enableDoubleAction = false
    ) {
        $listMenu = '';
        $currentUrl = end(explode('/', strtok(\yii\helpers\Url::current(), '?')));
        self::registerRecursiveBulletJs();
        $mnLvl = [];

        foreach ($menu as $row) {

            if (!empty($row['id' . $indx]) && !in_array($row['id' . $indx], $mnLvl)) {
                $mnLvl[] = $row['id' . $indx];
                $href = '/site/to-menu-url?url=' . (!empty($row['link' . $indx]) ? self::getLinkTarget($row, $indx) : self::getLinkByParent($row, $indx));
                $title = $row['title' . $indx];
                $titleAlt = $title;
                $canPermission = self::canPermissionMenu($row, $indx);
                $isPublic = self::isPublic($row, $indx);
                $badge = self::getBadgeMenu($row, $indx);
                $isReadonly = self::isReadonlyMenu($row, $indx);
                $hasChildren = self::hasChildrenMenu($row, $indx);

                $isSubMenu = ($indx > 0 ? true : false);

                $classReadonly = '';

                $iconLock = '';

                $isActive = ($currentUrl == $row['alias' . $indx]);

                $dataToggle = '';

                if ($isReadonly) {
                    $href = '/it/login';
                    $classReadonly = 'menu-link-locked';
                    $titleAlt = \Yii::t("app", "per abilitare questo menu") . ' ' . DesignUtility::getTextSigninOrSignup();
                    $dataToggle = 'tooltip';
                    $iconLock = '<span class="mdi mdi-lock-outline icon-myopen ml-auto"></span>';
                }


                if (($isPublic == true && $canPermission == true) || $isReadonly) {

                    if ($hasChildren && !($onlyFirstLevel) && !$isReadonly) {
                        $subIndx = $indx + 1;

                        $singleMenu = '';
                        $singleDropdownMenu = '';
                        $menuDoubleAction = '';
                        if ($enableDoubleAction) {
                            $singleMenu = Html::a(
                                            Html::tag('span', $title) . $badge,
                                            null,
                                            [
                                                'class' => 'nav-link' . ' ' . ($isActive ? 'active' : ''),
                                                'target' => '_self',
                                                'title' => $titleAlt,
                                                'href' => $href
                                            ]
                            );
                            $singleDropdownMenu = Html::a(
                                            $iconSubmenu,
                                            null,
                                            [
                                                'class' => 'nav-link dropdown-toggle' . ' ' . ($isActive ? 'active' : ''),
                                                'target' => '_self',
                                                'title' => $titleAlt,
                                                'href' => '#',
                                                'data' =>
                                                [
                                                    'toggle' => (($hasChildren) ? 'dropdown' : '')
                                                ],
                                                'aria' =>
                                                [
                                                    'expanded' => $expanded ? 'true' : 'false'
                                                ]
                                            ]
                            );
                            $menuDoubleAction = $singleMenu . $singleDropdownMenu;
                        } else {
                            $singleMenu = Html::a(
                                            Html::tag('span', $title) . $badge . $iconSubmenu,
                                            null,
                                            [
                                                'class' => 'nav-link dropdown-toggle' . ' ' . ($isActive ? 'active' : ''),
                                                'target' => '_self',
                                                'title' => $titleAlt,
                                                'href' => '#',
                                                'data' =>
                                                [
                                                    'toggle' => (($hasChildren) ? 'dropdown' : '')
                                                ],
                                                'aria' =>
                                                [
                                                    'expanded' => $expanded ? 'true' : 'false'
                                                ]
                                            ]
                            );
                            $menuDoubleAction = $singleMenu;
                        }

                        $listMenu .= Html::tag(
                                        'li',
                                        $menuDoubleAction
                                        .
                                        Html::tag(
                                                'div',
                                                Html::tag(
                                                        'div',
                                                        Html::tag(
                                                                'ul',
                                                                $this->arrayBiHamburgerSubmenuRender(
                                                                        $menu,
                                                                        $iconSubmenu,
                                                                        $assetBundle,
                                                                        $subIndx,
                                                                        $currentUrl,
                                                                        $row['id' . $indx]
                                                                ),
                                                                [
                                                                    'class' => 'link-list'
                                                                ]
                                                        ),
                                                        [
                                                            'class' => 'link-list-wrapper'
                                                        ]
                                                ),
                                                [
                                                    'id' => 'menu' . $row['id' . $indx],
                                                    'class' => 'dropdown-menu' . ($expanded ? ' show' : '')
                                                ]
                                        ),
                                        [
                                            'class' => 'nav-item dropdown' . ' ' . ($isActive ? 'active' : '') . ' ' . ($expanded ? ' show' : '') . ' ' . ($enableDoubleAction ? 'cms-menu-double-action' : '')
                                        ]
                        );
                    } else {

                        $listMenu .= Html::tag(
                                        'li',
                                        Html::a(
                                                $title . $badge . $iconLock,
                                                null,
                                                [
                                                    'class' => $classReadonly . ' nav-link' . ' ' . ($isActive ? 'active' : '') . ($expanded ? ' show' : ''),
                                                    'target' => '_self',
                                                    'title' => $titleAlt,
                                                    'href' => $href,
                                                    'data-toggle' => $dataToggle,
                                                    'data-placement' => 'bottom',
                                                    // 'data-container'=> 'body',
                                                    // 'data-trigger' => 'hover',
                                                    'data-html' => 'true'
                                                ]
                                        ),
                                        [
                                            'class' => 'nav-item' . ' ' . ($isActive ? 'active' : '')
                                        ]
                        );
                    }
                }
            }
        }

        return $listMenu;
    }

    /**
     * Bloccato a 4 livelli
     * @param array $menu
     * @param string $iconSubmenu
     * @param type $assetBundle
     * @param int $indx
     * @param string $currentUrl
     * @param int $father
     * @return string
     */
    public function arrayBiHamburgerSubmenuRender(
            $menu,
            $iconSubmenu,
            $assetBundle = null,
            $indx = 1,
            $currentUrl = null,
            $father = null
    ) {
        $listSubmenu = '';
        if (empty($currentUrl)) {
            $currentUrl = end(explode('/', strtok(\yii\helpers\Url::current(), '?')));
        }
        self::registerRecursiveBulletJs();

        $max = 6;
        foreach ($menu as $row) {

            $lvl = $indx;

            if (!empty($father) && $row['id' . ($lvl - 1)] != $father) {
                $exit = true;
            } else {
                $exit = false;
            }

            while ($lvl < $max && !empty($row['id' . $lvl]) && $exit == false) {

                if (!empty($row['id' . $lvl])) {
                    if (!in_array($row['id' . $lvl], $this->arrLvl)) {
                        $this->arrLvl[] = $row['id' . $lvl];
                        $href = '/site/to-menu-url?url=' . (!empty($row['link' . $lvl]) ? self::getLinkTarget(
                                        $row,
                                        $lvl
                                ) : self::getLinkByParent($row, $lvl));
                        $title = $row['title' . $lvl];
                        $titleAlt = $title;
                        $canPermission = self::canPermissionMenu($row, $lvl);
                        $isPublic = self::isPublic($row, $lvl);
                        $badge = self::getBadgeMenu($row, $lvl);
                        $isReadonly = self::isReadonlyMenu($row, $lvl);
                        $hasChildren = self::hasChildrenMenu($row, $lvl);
                        $isSubMenu = ($lvl > 0 ? true : false);

                        $classReadonly = '';

                        $iconLock = '';

                        $isActive = ($currentUrl == $row['alias' . $lvl]);

                        $dataToggle = '';

                        if ($isReadonly) {
                            $href = '/it/login';
                            $classReadonly = 'menu-link-locked';
                            $titleAlt = \Yii::t("app", "per abilitare questo menu") . ' ' . DesignUtility::getTextSigninOrSignup();
                            $dataToggle = 'tooltip';
                            $iconLock = '<span class="mdi mdi-lock-outline icon-myopen ml-auto"></span>';
                        }


                        if (($isPublic == true && $canPermission == true) || $isReadonly) {

                            if ($hasChildren && !$isReadonly) {

                                $subIndx = $lvl + 1;
                                $listSubmenu .= Html::tag(
                                                'li',
                                                Html::a(
                                                        Html::tag('span', $title) . $badge . $iconSubmenu,
                                                        null,
                                                        [
                                                            'class' => 'nav-link dropdown-toggle' . ' ' . ($isActive ? 'active' : ''),
                                                            'target' => '_self',
                                                            'title' => $titleAlt,
                                                            'href' => '#',
                                                            'data' =>
                                                            [
                                                                'toggle' => (($hasChildren) ? 'dropdown' : '')
                                                            ],
                                                            'aria' =>
                                                            [
                                                                'expanded' => 'false'
                                                            // 'controls' => 'menu' . $item->id
                                                            ]
                                                        ]
                                                )
                                                . Html::tag(
                                                        'div',
                                                        Html::tag(
                                                                'div',
                                                                Html::tag(
                                                                        'ul',
                                                                        $this->arrayBiHamburgerSubmenuRender(
                                                                                $menu,
                                                                                $iconSubmenu,
                                                                                $assetBundle,
                                                                                $subIndx,
                                                                                $currentUrl,
                                                                                $row['id' . $lvl]
                                                                        ),
                                                                        [
                                                                            'class' => 'link-list'
                                                                        ]
                                                                ),
                                                                [
                                                                    'class' => 'link-list-wrapper'
                                                                ]
                                                        ),
                                                        [
                                                            'id' => 'menu' . $row['id' . $lvl],
                                                            'class' => 'dropdown-menu'
                                                        ]
                                                ),
                                                [
                                                    'class' => 'nav-item dropdown dropdown-submenu' . ' ' . ($isActive ? 'active' : '')
                                                ]
                                );
                            } else {

                                $listSubmenu .= Html::tag(
                                                'li',
                                                Html::a(
                                                        Html::tag('span', $title) . $badge . $iconLock,
                                                        null,
                                                        [
                                                            'class' => $classReadonly . ' list-item' . ($isActive ? ' active' : ''),
                                                            'target' => '_self',
                                                            'title' => $titleAlt,
                                                            'href' => $href,
                                                        ]
                                                ),
                                                [
                                                    'class' => ($isActive ? ' active' : '')
                                                ]
                                );
                            }
                        }
                    }
                } else {
                    $exit = true;
                }
                $lvl++;
            }
        }

        return $listSubmenu;
    }

    /**
     *
     * @param array $menu
     * @param array $array
     * @param string $field
     * @param int $indx
     */
    public static function multilevelMenu($menu, $array, $field, $indx = 0) {
        switch ($indx) {
            case 0:
                if (!array_key_exists($array[$field . $indx], $menu)) {
                    $menu[$array[$field . $indx]] = [];
                }
                break;
            case 1:
                if (!array_key_exists($array[$field . $indx], $menu[$array[$field . ($indx - 1)]])) {
                    $menu[$array[$field . ($indx - 1)]][$array[$field . $indx]] = [];
                }
                break;
            case 2:
                if (!array_key_exists(
                                $array[$field . $indx],
                                $menu[$array[$field . ($indx - 2)]][$array[$field . ($indx - 1)]]
                        )) {
                    $menu[$array[$field . ($indx - 2)]][$array[$field . ($indx - 1)]][$array[$field . $indx]] = [];
                }
                break;
            case 3:
                if (!array_key_exists(
                                $array[$field . $indx],
                                $menu[$array[$field . ($indx - 3)]][$array[$field . ($indx - 2)]][$array[$field . ($indx - 1)]]
                        )) {

                    $menu[$array[$field . ($indx - 3)]][$array[$field . ($indx - 2)]][$array[$field . ($indx - 1)]][$array[$field . $indx]] = [];
                }
                break;
            case 4:
                if (!array_key_exists(
                                $array[$field . $indx],
                                $menu[$array[$field . ($indx - 4)]][$array[$field . ($indx - 3)]][$array[$field . ($indx - 2)]][$array[$field . ($indx - 1)]]
                        )) {

                    $menu[$array[$field . ($indx - 4)]][$array[$field . ($indx - 3)]][$array[$field . ($indx - 2)]][$array[$field . ($indx - 1)]][$array[$field . $indx]] = [];
                }
                break;
            case 5:
                if (!array_key_exists(
                                $array[$field . $indx],
                                $menu[$array[$field . ($indx - 5)]][$array[$field . ($indx - 4)]][$array[$field . ($indx - 3)]][$array[$field . ($indx - 2)]][$array[$field . ($indx - 1)]]
                        )) {

                    $menu[$array[$field . ($indx - 5)]][$array[$field . ($indx - 4)]][$array[$field . ($indx - 3)]][$array[$field . ($indx - 2)]][$array[$field . ($indx - 1)]][$array[$field . $indx]] = [];
                }
                break;
            case 6:
                if (!array_key_exists(
                                $array[$field . $indx],
                                $menu[$array[$field . ($indx - 6)]][$array[$field . ($indx - 5)]][$array[$field . ($indx - 4)]][$array[$field . ($indx - 3)]][$array[$field . ($indx - 2)]][$array[$field . ($indx - 1)]]
                        )) {

                    $menu[$array[$field . ($indx - 6)]][$array[$field . ($indx - 5)]][$array[$field . ($indx - 4)]][$array[$field . ($indx - 3)]][$array[$field . ($indx - 2)]][$array[$field . ($indx - 1)]][$array[$field . $indx]] = [];
                }
                break;
        }
    }

    /**
     * @param $text
     * @param int $chars
     * @return bool|string
     */
    public static function truncate($text, $chars = 25) {
        if (strlen($text) <= $chars) {
            return $text;
        }
        $text = $text . " ";
        $text = substr($text, 0, $chars);
        $text = substr($text, 0, strrpos($text, ' '));
        $text = $text . "...";
        return $text;
    }

    /**
     *
     */
    public static function registerRecursiveBulletJs() {
        $js = <<<JS

    var bullet = "<small class='badge badge-pill badge-danger bullet-navbar'></small>";
    $('.cms-menu-container-default li .bullet-navbar').each(function(){

        var li = $(this).closest('li');
        $(li).parents('li').each(function(){
            $(this).children('a').children('.bullet-parent').html(bullet);
        });

        var isBullet = $('.btn-always-hamburger-menu .bullet-navbar');
        if(isBullet.length === 0 || isBullet === undefined){
        $('.btn-always-hamburger-menu').append(bullet);
        }
    });
JS;
        \Yii::$app->controller->view->registerJs($js);
    }
}
