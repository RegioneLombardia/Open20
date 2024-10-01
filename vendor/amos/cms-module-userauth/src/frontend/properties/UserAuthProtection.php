<?php

namespace amos\userauth\frontend\properties;

use amos\userauth\frontend\Module;
use amos\userauth\frontend\utility\cmspageblock\CmsDataPageBlock;
use open20\amos\community\utilities\CommunityUtil;
use luya\admin\base\CheckboxProperty;
use luya\admin\models\Config;
use luya\cms\frontend\events\BeforeRenderEvent;
use luya\cms\helpers\Url;
use luya\cms\menu\QueryOperatorFieldInterface;
use luya\cms\models\NavItem;
use Yii;
use yii\helpers\ArrayHelper;

class UserAuthProtection extends CheckboxProperty {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->on(self::EVENT_BEFORE_RENDER, [$this, 'ensureUserAccess']);
    }

    /**
     * Check whether the current page requires protection and user is logged in.
     *
     * @param BeforeRenderEvent $event
     */
    public function ensureUserAccess(BeforeRenderEvent $event) {
        if ($this->getValue() == 1) {
            if (Yii::$app->user->isGuest) {
                throw new \yii\web\ForbiddenHttpException(Module::t('Non hai i privilegi per visualizzare la pagina.'));
                // find the nav item to redirect from config
//                $navItem = Yii::$app->menu->find()->where([QueryOperatorFieldInterface::FIELD_NAVID => Config::get(Module::USERAUTH_CONFIG_REDIRECT_NAV_ID)])->with([
//                        'hidden'])->one();
//throw new  yii\web\NotFoundHttpException(Module::t('Non hai i privilegi per visualizzare la pagina.'));
//                // redirect to the given nav item
//                return Yii::$app->response->redirect($navItem->absoluteLink.'?redir='.urlencode($event->menu->absoluteLink));
            } else {
                if (!$this->canSeePage()) {
                    throw new \yii\web\ForbiddenHttpException(Module::t('Non hai i privilegi per visualizzare la pagina.'));
//                    $navItem = Yii::$app->menu->find()->where([QueryOperatorFieldInterface::FIELD_NAVID => Config::get(Module::NOPERMISSION_CONFIG_REDIRECT_NAV_ID)])->with([
//                            'hidden'])->one();
//                    if ($navItem !== false) {
//                        // redirect to the given nav item
//                        return Yii::$app->response->redirect($navItem->absoluteLink);
//                    }else{
//                        return Yii::$app->response->redirect(Url::home(true));
//                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function varName() {
        return 'userAuthProtection';
    }

    /**
     * @inheritdoc
     */
    public function label() {
        return Module::t('userauth.propertie.userauthprotection.label');
    }

    /**
     *
     */
    protected function canSeePage() {
        $canSee = false;
        $canPermission = false;
        $nav = Yii::$app->menu->current;
        $nav_item = NavItem::findOne(['nav_id' => $nav->itemArray['nav_id']]);
        $propertyPermissions = $nav->getProperty('rolePermissions');
        $noComm = false;
        $noPerm = false;
        if (!empty($propertyPermissions)) {
            $canPermission = $propertyPermissions->checkPermissions();
        } else {
            $noPerm = true;
        }
        
        if (!is_null($nav_item)) {
            $values = $this->getBlockConfigValues($nav_item->nav_item_type_id,
                    CmsDataPageBlock::class);
            if (isset($values['community_id'])) {
                $canSee = CommunityUtil::userIsCommunityMember($values['community_id'],
                                \Yii::$app->user->id);
            } else {
                $noComm = true;
            }
        }

        return ($noComm && $noPerm) || $canPermission || $canSee;
    }

    /**
     *
     * @param integer $nav_item_page_id
     * @param type $class_
     * @return array
     */
    protected function getBlockConfigValues($nav_item_page_id, $class_) {
        $items = [];
        $blocks = $this->findBlockModules($nav_item_page_id, $class_);
        foreach ($blocks as $block) {
            $items = ArrayHelper::merge($items, $block->getCfgValues());
        }
        return $items;
    }

    /**
     *
     * @param type $nav_item_page_id
     * @param type $class_
     * @return type
     */
    protected function findBlockModules($nav_item_page_id, $class_) {
        $blocks = $class_::findBlocks($nav_item_page_id);
        return $blocks;
    }

}
