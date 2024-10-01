<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace open20\elasticsearch\models;

use luya\admin\models\Property;
use luya\cms\models\NavItem as BaseNavItem;
use open20\elasticsearch\i18n\grammar\NavItemGrammar;
use yii\helpers\Url;
use open20\elasticsearch\Module;

class NavItem extends BaseNavItem { 

    static $ogTitle;
    static $ogDescription;
    private $elastic_source_text = "";
    private $elastic_url = "";
    private $elastic_preview = "";
 
    public function getElastic_preview() {
        return $this->elastic_preview;
    }

    public function setElastic_preview($elastic_preview) {
        $this->elastic_preview = $elastic_preview;
    }

    public function getElasticSourceText() {
        return $this->elastic_source_text;
    }

    public function getElasticUrl() {
        return $this->elastic_url;
    }

    public function setElasticSourceText($elastic_source_text) {
        $this->elastic_source_text = $elastic_source_text;
    }

    public function setElasticUrl($elastic_url) {
        $this->elastic_url = $elastic_url;
    }

    /**
     * @return NavItemGrammar
     */
    public function getGrammar() {
        return new NavItemGrammar();
    }

    /**
     * 
     * @return string
     */
    public function getTitle() {
        $ret = '';
        if (is_null($this->ogTitle)) {
            $this->ogTitle = Property::findOne(['var_name' => 'ogTitle']);
        }
        if (!is_null($this->ogTitle)) {
            $m = \luya\cms\models\Property::findOne(['nav_id' => $this->nav_id, 'admin_prop_id' => $this->ogTitle->id]);
            if (!is_null($m)) {
                $ret = $m->value;
            } else {
                $ret = $this->title;
            }
        }
        return $ret;
    }

    /**
     * 
     * @param int $truncate
     * @return string
     */
    public function getDescription($truncate) {
        $ret = $this->description;
        if ($truncate) {
            $ret = $this->__shortText($this->description, 200);
        }
        return $ret;
    }

    /**
     * 
     * @return string
     */
    public function getFullViewUrl() {
        if (empty($this->elastic_url)) {
            $this->createUrl();
        }
        return Url::base() . "/" . $this->elastic_url;
    }

    /**
     * Parse string and return limited one
     * @param $text
     * @param $char_limit
     * @return string
     */
    protected function __shortText($text, $char_limit) {
        //Remove html tags
        $asString = strip_tags($text);

        //If already good string
        if (strlen($asString) < $char_limit) {
            return $asString;
        }

        if ($char_limit != -1) {

            //Limit string
            $asString = substr($asString, 0, $char_limit + 1);

            //Explode to array
            $arrayString = explode(' ', $asString);

            if (count($arrayString) > 1) {
                //Remove last word
                array_pop($arrayString);

                //Merge string
                $asString = implode(' ', $arrayString);
            }

            //Return it
            return $asString . "...";
        } else {
            return $asString;
        }
    }

    /**
     * 
     * @return string
     */
    public function createUrl() {
        $module = Module::instance();
        $ret = true;
        if ($this->id) {
            $path = [];
            $language = $this->lang;
            $path[] = $this->alias;
            $nav = $this->nav;
            $secure = $this->isProtected($nav);
            while (!empty($nav) && $nav->parent_nav_id) {

                if ($nav->is_deleted || $secure || $module->indexDraftNavItem === false) {
                    if ($nav->is_deleted || $nav->is_offline || $secure) {
                        break;
                    }
                }
                $nav_item = NavItem::findOne(['nav_id' => $nav->parent_nav_id, 'lang_id' => $language->id]);
                if (!is_null($nav_item)) {
                    array_unshift($path, $nav_item->alias);
                }
                $nav = Nav::findOne(['id' => $nav->parent_nav_id]);
                $secure = $this->isProtected($nav);
            }
            $short_code = is_null($language) ? "it" : $language->short_code;
            array_unshift($path, $short_code);
            $this->elastic_url = implode("/", $path);
            if (!empty($nav)) {
                if ($module->indexDraftNavItem === false) {
                    $ret = !($nav->is_deleted || $nav->is_offline || $secure);
                } else {
                    $ret = !($nav->is_deleted || $secure);
                }
            }
        }
        return $ret;
    }

    /**
     * 
     * @return string
     */
    public function createUrlPreview() {
        $module = Module::instance();
        $ret = true;
        if ($this->id) {
            $path = [];
            $language = $this->lang;
            $path[] = $this->alias;
            $nav = $this->nav;
            $token = ElasticsearchToken::generateTokens($this->alias);
            while (!empty($nav) && $nav->parent_nav_id) {

                if ($nav->is_deleted) {
                    break;
                }
                $nav_item = NavItem::findOne(['nav_id' => $nav->parent_nav_id, 'lang_id' => $language->id]);
                if (!is_null($nav_item)) {
                    array_unshift($path, $nav_item->alias);
                }
                $nav = Nav::findOne(['id' => $nav->parent_nav_id]);
            }
            $short_code = is_null($language) ? "it" : $language->short_code;
            array_unshift($path, $short_code);
            $this->elastic_url = implode("/", $path);
            if (!empty($nav)) {
                $ret = !($nav->is_deleted);
            }
            $this->elastic_preview = 'cms-page-preview?itemId='.$this->id.'&version='.$this->nav_item_type_id.'&token='.$token;
        }
        return $ret;
    }

    private function isProtected($nav) {
        $module = Module::instance();
        $secure = false;
        if ($module->indexProtectedNavItem === false) {

            $prop = $nav->getProperty("userAuthProtection");
            if ($prop !== false) {
                /** @var luya\admin\base\Property $prop */
                $secure = $prop->value == 1 ? true : false;
            }
        }
        return $secure;
    }
}
