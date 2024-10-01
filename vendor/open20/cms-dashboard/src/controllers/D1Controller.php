<?php

namespace open20\cms\dashboard\controllers;

use open20\amos\core\controllers\BackendController;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\news\models\News;
use luya\cms\models\NavItemRedirect;
use yii\base\InvalidConfigException;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use open20\cms\dashboard\Module;
use open20\cms\dashboard\utilities\Utility;
use open20\amos\core\helpers\Html;
use yii\base\DynamicModel;
use app\modules\cms\models\Nav;
use app\modules\cms\models\NavItem;
use app\modules\cms\models\NavItemPage;
use Yii;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\cms\dashboard\models\CmsWfRequest;
use open20\cms\dashboard\utilities\Email;
use luya\cms\models\NavContainer;
use open20\amos\tag\utility\TagFreeUtility;
use open20\amos\core\icons\AmosIcons;
use yii\helpers\VarDumper;
use yii\helpers\Url;

/**
 * Description of DashboardController
 *
 */
class D1Controller extends BackendController {

    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'pagine',
                            'update-page',
                            'create-page',
                            'delete-page',
                            'delete-version',
                            'publication-request',
                            'unpublishing-request',
                            'create-new-version',
                            'new-sort',
                            'set-visibility-menu',
                            'modelli',
                            'crea-modello',
                            'aggiorna-modello',
                            'luya-admin-menu',
                            'seo-url',
                            'ajax-user-list',
                            'ajax-versions',
                            'ajax-profiles',
                        ],
                        'allow' => true,
                        'roles' => ['REDATTORECMS'],
                    ],
                    [
                        'actions' => [
                            'index',
                            'pagine',
                            'update-page',
                            'create-page',
                            'delete-page',
                            'publish-version',
                            'unpublish-page',
                            'publish-page',
                            'delete-version',
                            'request-publication',
                            'wf-approve',
                            'wf-refuse',
                            'menu',
                            'luya-admin-menu',
                        ],
                        'allow' => true,
                        'roles' => ['CMS_PUBLISH_PAGE', 'CAPOREDATTORECMS'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [],
            ],
        ];
    }

    /**
     * @param $container
     * @return string
     */
    public function actionPagine($container = 'default', $openPage = null) {
        $enableFilter = Module::instance()->enableContainerFilterForEditor;
        // $this->setAvailableViews([
        //     'grid' => [
        //         'name' => 'grid',
        //         'label' => AmosIcons::show('view-list-alt') . Html::tag('p', Module::t('amospagine', 'Tabella')),
        //         'url' => '?currentView=grid'
        //     ],
        // ]);
        $this->setUpLayout('list');
        $get = Yii::$app->request->get();
        if (\Yii::$app->user->can('CMS_PUBLISH_PAGE')) {
            //NOTHING
        } else if ($enableFilter && \Yii::$app->user->can('CAPOREDATTORECMS')) {
            if (!in_array($container, Module::instance()->mapPermissionContainer['CAPOREDATTORECMS'])) {
                $container = 'default';
            }
        } else {
            if ($enableFilter && !in_array($container, Module::instance()->mapPermissionContainer['REDATTORECMS'])) {
                $container = 'default';
            }
        }
        $menu = Utility::getAdminMenuLuya($container, 0, false, $get);
        $menuQuery = Utility::getAdminMenuLuyaTree($container, true);

        $titleSection = Module::txt('Pagine');
        $subTitleSection = Html::tag('p', Module::txt('Qui trovi l\'elenco di tutte le pagine del sito: puoi modificarle o crearne di nuove includendo i componenti redazionali con l\'editor drag and drop.'), ['class' => 'lead m-t-20']);
        $labelCreate = Module::txt('Nuova');
        $titleCreate = Module::t('amospagine', 'Crea una nuova pagina nel menu "{container}"', ['container' => $container]);
        $urlCreate = '/' . Module::getModuleName() . '/d1/create-page?container=' . $container;
        $hideCreate = false;

        \Yii::$app->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'pagine',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            //            'urlLinkAll' => $urlLinkAll,
            //            'labelLinkAll' => $labelLinkAll,
            //            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            //            'labelManage' => $labelManage,
            //            'titleManage' => $titleManage, 
            'urlCreate' => $urlCreate,
            'hideCreate' => $hideCreate,
            'canCreate' => true, //da completare con i permessi
                //            'urlManage' => $urlManage,
        ];
        $model = new DynamicModel(['container', 'search', 'updown', 'item', 'status', 'type', 'updated_by']);
        $model->addRule(['container', 'search'], 'string');
        $model->addRule(['status', 'type'], 'safe');
        $model->addRule(['updown', 'item', 'updated_by'], 'integer');

        $model->setAttributeLabels([
            'container' => Module::txt('Mostra'),
            'search' => Module::txt('Ricerca libera'),
        ]);

        if (!empty($get['DynamicModel'])) {
            if (!empty($get['DynamicModel']['search'])) {
                $model->search = $get['DynamicModel']['search'];
            }
            if (!empty($get['DynamicModel']['type'])) {
                $model->type = $get['DynamicModel']['type'];
            }
            if (!empty($get['DynamicModel']['updated_by'])) {
                $model->updated_by = $get['DynamicModel']['updated_by'];
            }
            if (!empty($get['DynamicModel']['status'])) {
                $model->status = $get['DynamicModel']['status'];
            }
        }
        $model->container = $container;
        $this->view->params['containerFullWidth'] = true;

        $ancestors = Utility::getPageAncestors($openPage);

        return $this->render('pages', ['dataProvider' => $menu, 'container' => $container, 'modelContainer' => $model, 'menuQuery' => $menuQuery, 'ancestors' => $ancestors]);
    }

    /**
     * @param $container
     * @return string
     */
    public function actionMenu($container = 'default') {

        $enableFilter = Module::instance()->enableContainerFilterForEditor;
        $this->setUpLayout('list');
        $get = Yii::$app->request->get();
        if (\Yii::$app->user->can('CMS_PUBLISH_PAGE')) {
            //NOTHING
        } else if ($enableFilter && \Yii::$app->user->can('CAPOREDATTORECMS')) {
            if (!in_array($container, Module::instance()->mapPermissionContainer['CAPOREDATTORECMS'])) {
                $container = 'default';
            }
        } else {
            if ($enableFilter && !in_array($container, Module::instance()->mapPermissionContainer['REDATTORECMS'])) {
                $container = 'default';
            }
        }
        $menu = Utility::getAdminMenuLuya($container, 0, false, $get);

        $menuQuery = Utility::getAdminMenuLuyaTree($container, true);

        $titleSection = Module::txt('Menu');

        $subTitleSection = Html::tag('p', Module::txt('Permette la gestione di tutti i menu e la visibilita delle pagine nel menu.'));

        $labelCreate = Module::txt('Nuovo menu');
        $titleCreate = Module::txt('Crea un nuovo menu');

        $urlCreate = '/' . Module::getModuleName() . '/d1/create-menu?container=' . $container;

        $hideCreate = true;

        \Yii::$app->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'pagine',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            //            'urlLinkAll' => $urlLinkAll,
            //            'labelLinkAll' => $labelLinkAll,
            //            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            //            'labelManage' => $labelManage,
            //            'titleManage' => $titleManage, 
            'urlCreate' => $urlCreate,
            'hideCreate' => $hideCreate,
            'canCreate' => false, //da completare con i permessi
                //            'urlManage' => $urlManage,
        ];
        $model = new DynamicModel(['container', 'search', 'item', 'updown']);
        $model->addRule(['container', 'search'], 'string');
        $model->addRule(['item', 'updown'], 'integer');
        $model->setAttributeLabels([
            'container' => Module::txt('Mostra'),
            'search' => Module::txt('Ricerca libera'),
        ]);

        if (!empty($get['DynamicModel']) && !empty($get['DynamicModel']['search'])) {
            $model->search = $get['DynamicModel']['search'];
        }
        $model->container = $container;

        $this->view->params['containerFullWidth'] = true;
        return $this->render('menu', ['dataProvider' => $menu, 'container' => $container, 'modelContainer' => $model, 'menuQuery' => $menuQuery]);
    }

    /**
     * @param $id
     * @param $url
     * @return string|\yii\web\Response
     */
    public function actionUpdatePage($id, $url = null) {
        /** @var Module $dashboardModule */
        $dashboardModule = Module::instance();

        $this->setUpLayout('form');
        $nav = Nav::findOne($id);
        if (empty($nav)) {
            Yii::$app->getSession()->addFlash('danger', Module::txt('La pagina non esiste.'));
            if (!empty($url)) {
                return $this->redirect([$url]);
            }
            return $this->redirect(['pagine']);
        }

        $modelimage = NavItem::findOne(['nav_id' => $nav->id]);
        $navItemRedirect = null;

        $container = '';
        $containerArray = (new \yii\db\Query())->from('cms_nav_container')->andWhere(['id' => $nav->nav_container_id])->select('name')->one();
        if (!empty($containerArray['name'])) {
            $container = str_replace(' Container', '', $containerArray['name']);
        }

        $versions = Utility::getVersions($modelimage->id);
        $menuQuery = Utility::getAdminMenuLuyaTree(null, true, [], 1);

        if (empty($modelimage)) {
            Yii::$app->getSession()->addFlash('danger', Module::txt('La pagina non esiste.'));
            if (!empty($url)) {
                return $this->redirect([$url]);
            }
            return $this->redirect(['pagine']);
        }
        //$titleSection = Module::txt('Aggiorna pagina:') . ' ' . $modelimage->title;
        //$subTitleSection = Html::tag('p', Module::txt('Permette di creare una pagina, qui si inseriscono le info basi e nello step successivo si costruisce la pagina con le funzionalit� Drag&Drop.'));
        //$hideCreate = true;
        // \Yii::$app->view->params['createNewBtnParams'] = [
        //     'createNewBtnLabel' => Module::txt('Nuova pagina'),
        //     'createNewUrl' => ['/test'],
        // ];
        // \Yii::$app->view->params = [
        //     'isGuest' => \Yii::$app->user->isGuest,
        //     'modelLabel' => 'pagine',
        //     'titleSection' => $titleSection,
        //     'subTitleSection' => $subTitleSection,
        //     'hideCreate' => $hideCreate,
        //     'canCreate' => false, //da completare con i permessi
        // ];
        $obj = new DynamicModel([
            'title', 'alias', 'description', 'isNewRecord',
            'lang_id', 'layout_id', 'is_draft', 'is_home', 'is_protected', 'is_readonly', 'parent_id', 'nav_container_id',
            'keywords', 'title_tag', 'publish_from', 'publish_till', 'tag_free', 'permission_rbac', 'profiles_rbac',
            'type_of_page', 'redirect_type', 'redirect_value', 'redirect_target', 'redirect_value_item_id'
        ]);
        $obj->addRule(['lang_id', 'layout_id', 'is_draft', 'is_home', 'parent_id', 'nav_container_id', 'is_protected', 'is_readonly'], 'integer');
        $obj->addRule(['title', 'alias', 'title_tag'], 'string', ['max' => $dashboardModule->defaultTitlePageLength]);
        $obj->addRule(['title', 'alias'], 'filter', ['filter' => 'trim']);
        $obj->addRule(['description', 'keywords'], 'string');
        $obj->addRule(['isNewRecord', 'tag_free'], 'safe');
        $obj->addRule(['isNewRecord', 'permission_rbac', 'profiles_rbac'], 'safe');
        $obj->addRule(['title'], 'required');
        $obj->addRule(['publish_from', 'publish_till'], 'safe');
        $obj->addRule(['publish_till'], 'compare', ['compareAttribute' => 'publish_from', 'operator' => '>', 'skipOnEmpty' => true]);
        $obj->addRule(['type_of_page', 'redirect_type', 'redirect_value', 'redirect_target', 'redirect_value_item_id', 'socialwall'], 'safe');
        $obj->addRule(['redirect_value'], 'url');

        $obj->setAttributeLabels([
            'title' => Module::txt('Titolo'),
            'alias' => Module::txt('Alias'),
            'description' => Module::txt('Descrizione'),
            'parent_id' => Module::txt('Pagina genitore'),
            'keywords' => Module::txt('Parole chiave'),
            'title_tag' => Module::txt('Titolo (SEO)'),
            'publish_from' => Module::txt('Data pubblicazione'),
            'publish_till' => Module::txt('Data fine pubblicazione'),
            'tag_free' => Module::txt('Tag liberi'),
            'permission_rbac' => Module::txt('Permessi di accesso'),
            'profiles_rbac' => Module::txt('Profili di accesso'),
            'redirect_target' => Module::txt('Destinazione redirezione'),
            'redirect_value' => Module::txt('Url'),
            'redirect_value_item_id' => Module::txt('Pagina'),
            'redirect_type' => Module::txt('Tipo di redirezione'),
            'is_protected' => Module::txt('Abilita protezione'),
            'is_readonly' => Module::txt('Visualizza sempre nel menu')
        ]);

        $obj->load(['DynamicModel' => $nav->attributes]);
        $obj->title = $modelimage->title;
        $obj->alias = $modelimage->alias;
        $oldalias = $modelimage->alias;

        $obj->isNewRecord = false;
        $obj->tag_free = TagFreeUtility::get($nav->id, get_class($nav));
        if (Module::instance()->enableProfile) {
            $obj->profiles_rbac = $this->getRbacProfiles($nav->id);
        } else {
            $obj->permission_rbac = $this->getRbacPermission($nav->id);
        }
        // se � di tupo redirect precompilo i dati
        if ($modelimage->nav_item_type == 3) {
            $obj->type_of_page = 2;
            $navItemRedirect = NavItemRedirect::findOne($modelimage->nav_item_type_id);
            if ($navItemRedirect) {
                $obj->redirect_type = $navItemRedirect->type;
                $obj->redirect_target = $navItemRedirect->target;
                if ($navItemRedirect->type == 1) {
                    $obj->redirect_value_item_id = $navItemRedirect->value;
                } else {
                    $obj->redirect_value = $navItemRedirect->value;
                }
            }
        }

        $post = \Yii::$app->request->post();
        if ($post && $obj->load($post) && $modelimage->load($post) && $obj->validate()) {

            $modelimage->title = $obj->title;
            $modelimage->alias = $obj->alias;
            $nav->parent_nav_id = (empty($obj->parent_id) ? 0 : $obj->parent_id);
            $nav->publish_from = $obj->publish_from;
            $nav->publish_till = $obj->publish_till;
            if ($obj->is_home) {
                Utility::setHomepage($nav->id);
                $nav->is_home = $obj->is_home;
            }
            // Gestione protezione delle pagine
            Utility::setNavProtection($nav->id, $obj->is_protected);
            Utility::setNavReadOnly($nav->id, $obj->is_readonly);

            //modifica dati redirect
            if ($navItemRedirect) {
                $navItemRedirect->type = $obj->redirect_type;
                $navItemRedirect->target = $obj->redirect_target;
                if ($navItemRedirect->type == 1) {
                    $navItemRedirect->value = $obj->redirect_value_item_id;
                } else {
                    $navItemRedirect->value = $obj->redirect_value;
                }
                $navItemRedirect->save(false);
            }

            if ($oldalias != $modelimage->alias) {
                $modelimage->alias = Utility::getAlias($obj->title, $obj->alias);
            }

            if ($modelimage->save(false) && $nav->save()) {
                Yii::$app->getSession()->addFlash('success', Module::txt('Pagina aggiornata con successo'));
                if (Module::instance()->enableProfile) {
                    $this->setRbacProfiles($nav->id, $obj->profiles_rbac);
                } else {
                    $this->setRbacPermission($nav->id, $obj->permission_rbac);
                }

                TagFreeUtility::set($nav->id, get_class($nav), $obj->tag_free);

                // Elastic Search
                Utility::updateESIndex($modelimage->id);

                Yii::$app->cache->flush();

                if (!is_null(\Yii::$app->getModule('socialwall'))) {
                    if (array_key_exists('Socialwall', $post)) {
                        \open20\socialwall\utility\Social::saveSocialwallSelection('cms', $nav->id, $post['Socialwall']);
                    }
                }

                if (!empty($url)) {
                    return $this->redirect([$url]);
                }
                return $this->redirect(['pagine']);
            }
            Yii::$app->getSession()->addFlash('danger', Module::txt('Verificare i dati inseriti.'));
        }
        return $this->render('_page', ['model' => $obj, 'parent_id' => $nav->parent_nav_id,
                    'modelimage' => $modelimage, 'versions' => $versions, 'isUpdate' => true,
                    'menuQuery' => $menuQuery, 'nameContainer' => $container,
                    'nav' => $nav
        ]);
    }

    public function beforeAction($action) {

        if (!\Yii::$app->user->isGuest && \Yii::$app->adminuser->isGuest) {
            $this->redirect([
                '/site/to-menu-url',
                'url' => Yii::$app->params['platform']['frontendUrl'] . '/admin/login/login-cms-admin?redirect=/' . Module::getModuleName() . '/d1/' . $action->id
            ]);
        }
        try {
//            if(!empty(\Yii::$app->adminuser->id)){
//                \Yii::$app->db->createCommand("update admin_user_login set timestamp_create = " . time() . " where user_id = " . \Yii::$app->adminuser->id)->execute();
//            }
        } catch (Exception $ex) {
            
        }
        return parent::beforeAction($action);
    }

    public function actionCreatePage($parent_id = null, $url = null, $container = 'default') {
        /** @var Module $dashboardModule */
        $dashboardModule = Module::instance();

        $this->setUpLayout('form');
        $navContainer = NavContainer::find()->andWhere(['alias' => $container])->one();
        $modelimage = new \app\modules\cms\models\NavItem();

        $obj = new DynamicModel([
            'title', 'alias', 'description', 'isNewRecord', 'lang_id', 'layout_id', 'is_draft', 'tag_free', 'permission_rbac', 'profiles_rbac',
            'parent_id', 'nav_container_id', 'keywords', 'title_tag', 'publish_from', 'publish_till', 'modello',
            'redirect_type', 'type_of_page', 'redirect_value', 'redirect_target', 'redirect_value_item_id', 'is_protected', 'is_readonly'
        ]);
        $obj->addRule(['lang_id', 'layout_id', 'is_draft', 'parent_id', 'nav_container_id', 'modello', 'is_protected', 'is_readonly'], 'integer');
        $obj->addRule(['title', 'alias', 'title_tag'], 'string', ['max' => $dashboardModule->defaultTitlePageLength]);
        $obj->addRule(['title', 'alias'], 'filter', ['filter' => 'trim']);
        $obj->addRule(['description', 'keywords'], 'string');
        $obj->addRule(['isNewRecord', 'tag_free'], 'safe');
        $obj->addRule(['isNewRecord', 'permission_rbac', 'profiles_rbac'], 'safe');
        $obj->addRule(['title', 'type_of_page'], 'required');
        $obj->addRule(['publish_from', 'publish_till'], 'safe');
        $obj->addRule(['type_of_page', 'redirect_type', 'redirect_value', 'redirect_target', 'redirect_value_item_id'], 'safe');
        $obj->addRule(['publish_till'], 'compare', ['compareAttribute' => 'publish_from', 'operator' => '>', 'skipOnEmpty' => true]);
        $obj->addRule(['redirect_value'], 'url');

        $obj->setAttributeLabels([
            'title' => Module::txt('Titolo'),
            'alias' => Module::txt('Alias'),
            'description' => Module::txt('Descrizione'),
            'parent_id' => Module::txt('Pagina genitore'),
            'keywords' => Module::txt('Parole chiave'),
            'title_tag' => Module::txt('Titolo (SEO)'),
            'publish_from' => Module::txt('Data pubblicazione'),
            'publish_till' => Module::txt('Data fine pubblicazione'),
            'modello' => Module::txt('Scegli il modello'),
            'tag_free' => Module::txt('Tag liberi'),
            'permission_rbac' => Module::txt('Permessi di accesso'),
            'profiles_rbac' => Module::txt('Profili di accesso'),
            'type_of_page' => Module::txt('Tipologia di pagina'),
            'redirect_target' => Module::txt('Destinazione redirezione'),
            'redirect_value' => Module::txt('Url'),
            'redirect_value_item_id' => Module::txt('Pagina'),
            'redirect_type' => Module::txt('Tipo di redirezione'),
            'is_protected' => Module::txt('Abilita protezione'),
            'is_readonly' => Module::txt('Visualizza sempre nel menu')
        ]);

        $obj->isNewRecord = true;
        $post = \Yii::$app->request->post();

        $menuQuery = Utility::getAdminMenuLuyaTree($container, true, []);
        $modelli = Utility::getLuyaTemplate(true);
        $obj->modello = 0;
        if ($post && $obj->load($post) && $modelimage->load($post) && $obj->validate()) {
            if (!empty($obj->modello)) {
                $nav = Nav::findOne($obj->modello);
                $navItemModel = NavItem::find()->andWhere(['nav_id' => $nav->id])->one();
                $fromPageModel = NavItemPage::findOne($navItemModel->nav_item_type_id);

                $navNew = new Nav();
                $navItemNew = new NavItem();
                $navItemPageNew = new NavItemPage();
                $transaction = Nav::getDb()->beginTransaction();
                try {

                    $navNew->nav_container_id = $navContainer->id;
                    $navNew->parent_nav_id = empty($obj->parent_id) ? 0 : $obj->parent_id;
                    $navNew->is_deleted = 0;
                    $navNew->is_hidden = 1;
                    $navNew->is_home = 0;
                    $navNew->is_offline = 1;
                    $navNew->is_draft = 0;
                    if ($navNew->save(false)) {
                        $navItemNew->nav_id = $navNew->id;
                        $navItemNew->lang_id = $this->getDefaultLanguageId();
                        $navItemNew->nav_item_type = 1;
                        $navItemNew->nav_item_type_id = 0;
                        $navItemNew->create_user_id = \Yii::$app->adminuser->getId();
                        $navItemNew->update_user_id = \Yii::$app->adminuser->getId();
                        $navItemNew->timestamp_create = time();
                        $navItemNew->title = $obj->title;
                        $navItemNew->alias = Utility::getAlias($obj->title, $obj->alias);
                        $navItemNew->description = $obj->description;
                        $navItemNew->keywords = $obj->keywords;
                        $navItemNew->title_tag = $obj->title_tag;
                        $navItemNew->is_url_strict_parsing_disabled = 0;
                        $navItemNew->is_cacheable = 0;
                        if ($navItemNew->save(false)) {
                            $navItemPageNew->nav_item_id = $navItemNew->id;
                            $navItemPageNew->layout_id = $fromPageModel->layout_id;
                            $navItemPageNew->timestamp_create = time();
                            $navItemPageNew->create_user_id = \Yii::$app->adminuser->getId();
                            $navItemPageNew->version_alias = 'Initial';
                            if ($navItemPageNew->save(false)) {
                                $navItemNew->nav_item_type_id = $navItemPageNew->id;
                                if ($navItemNew->save(false)) {
                                    $ok = NavItemPage::copyBlocks($fromPageModel->id, $navItemPageNew->id);
                                    if ($ok) {
                                        TagFreeUtility::set($navNew->id, get_class($navNew), $obj->tag_free);
                                        $transaction->commit();
                                        $model = $navNew;
                                    }
                                }
                            }
                        }
                    } else {

                        $transaction->rollBack();
                    }
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            } else {
                $obj->description = $modelimage->description;
                $obj->alias = Utility::getAlias($obj->title, $obj->alias);
                $obj->lang_id = $this->getDefaultLanguageId();
                $obj->layout_id = Module::instance()->defaultLayoutId;
                $obj->is_draft = 0;
                if (empty($obj->parent_id)) {
                    $obj->parent_id = 0;
                }
                $obj->nav_container_id = (!empty($navContainer) ? $navContainer->id : Module::instance()->getDefaultContainerId());
                $other = [
                    'Nav' => [
                        'publish_from' => $obj->publish_from,
                        'publish_till' => $obj->publish_till,
                        'is_deleted' => 0,
                        'is_home' => 0,
                    ],
                    'NavItem' => [
                        'keywords' => $modelimage->keywords,
                        'title_tag' => $modelimage->title_tag,
                        'is_url_strict_parsing_disabled' => 0,
                        'is_cacheable' => 0,
                    ],
                ];
                // se tipologia redirect
                if ($obj->type_of_page == 2) {
                    $other['NavItemRedirect'] = [
                        'type' => $obj->redirect_type,
                        'value' => $obj->redirect_type == 1 ? $obj->redirect_value_item_id : $obj->redirect_value,
                        'target' => $obj->redirect_target,
                    ];
                    $obj->layout_id = Module::instance()->defaultLayoutId;
                }

                $model = $this->createPage($obj, $other);
            }

            if (!empty($model) && !empty($model->id)) {
                // Gestione protezione delle pagine
                Utility::setNavProtection($model->id, $obj->is_protected);
                Utility::setNavReadOnly($model->id, $obj->is_protected);

                if ($obj->type_of_page == 2) {
                    Yii::$app->getSession()->addFlash('success', Module::txt('Pagina di tipo "Redirect" creata con successo in stato "Bozza".'));
                    return $this->redirect(['pagine']);
                }
                Yii::$app->getSession()->addFlash('success', Module::txt('Pagina creata con successo in stato "Bozza": procedi ad inserire i componenti drag&drop.'));

                if (Module::instance()->enableProfile) {
                    $this->setRbacProfiles($model->id, $obj->profiles_rbac);
                } else {
                    $this->setRbacPermission($model->id, $obj->permission_rbac);
                }
                if (!empty($url)) {
                    return $this->redirect([$url]);
                }
                return $this->redirect(['/admin#!/template/cmsadmin~2Fdefault~2Findex/update/' . $model->id]);
            }
            Yii::$app->getSession()->addFlash('danger', Module::txt('Verificare i dati inseriti.'));
        }

        return $this->render('_page', ['model' => $obj, 'parent_id' => $parent_id,
                    'modelimage' => $modelimage, 'container' => $container,
                    'nameContainer' => str_replace(' Container', '', $navContainer->name),
                    'modelli' => $modelli, 'menuQuery' => $menuQuery]);
    }

    public function createPage($obj, $other) {
        $model = new Nav();
        $parentNavId = $obj->parent_id;

        $navContainerId = $obj->nav_container_id;
        \app\modules\cms\admin\Module::setBackendUserId(\Yii::$app->adminuser->getId());
        $create = $model->createPage(
                $parentNavId,
                $navContainerId,
                $obj->lang_id,
                $obj->title,
                $obj->alias,
                $obj->layout_id,
                $obj->description,
                $obj->is_draft,
                $other
        );

        return $model;
    }

    public function getDefaultLanguageId() {
        $defaultLanguage = Yii::$app->db->createCommand('SELECT id FROM admin_lang where is_default=1')->queryOne();
        return $defaultLanguage['id'];
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionIndex() {
        Url::remember();
        $this->setUpLayout('@frontend/views/layouts/main');
        $this->view->params['openSidebarRedattoreOnLoadPage'] = true;
        $this->view->params['hideBtnModifyCmsPage'] = true;

        /** @var \open20\amos\admin\models\UserProfile $userModule */
        $userModule = CurrentUser::getUserProfile();

        $classes = $userModule->profileClasses;
        $myrole = null;
        $idCurrentUserIfRedattore = null; // Contiene un id solo se l'utente � redattore, per filtrare ulteriormente la query
        foreach ($classes as $classrole) {
            if ($classrole->code == 'caporedattore' || $classrole->code == 'redattore') {
                $myrole = $classrole->name;
                if ($classrole->code == 'redattore') {
                    $idCurrentUserIfRedattore = Yii::$app->user->id;
                }
            }
        }

        // TODO provvisorio
        $moduleNews = Yii::$app->getModule('news');
        if ($moduleNews) {
            $contents = $this->getDashboardContents($idCurrentUserIfRedattore);
        }

        return $this->render('index', [
                    'contents' => $contents,
                    'isCapoRedattore' => \yii::$app->user->can('CAPOREDATTORECMS'),
                    'userModule' => $userModule,
                    'myrole' => $myrole
        ]);
    }

    public function actionDeletePage($id, $item_id, $container, $url) {
        $nav = Nav::findOne($id);
        if ($nav->is_offline == 0) {
            Yii::$app->getSession()->addFlash('danger', Module::txt('Non è possibile cancellare una pagina pubblicata.'));
            return $this->redirect([$url]);
        }
        $children = Utility::getAdminMenuLuya($container, $id, true);
        /** @var \open20\amos\sondaggi\models\search\SondaggiDomandeSearch $model */
        if ($children->count() > 0) {
            Yii::$app->getSession()->addFlash('danger', Module::txt('Non è possibile cancellare una pagina che ha delle sotto-pagine.'));
            return $this->redirect([$url]);
        }
        $ok = Utility::deletePage($id, $item_id);
        if ($ok) {
            if ($nav->is_draft) {
                Yii::$app->getSession()->addFlash('success', Module::txt('Modello cancellato correttamente.'));
            } else {
                Yii::$app->getSession()->addFlash('success', Module::txt('Pagina cancellata correttamente.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::txt('Si è verificato un problema durante la cancellazione della pagina, contattare l\'amministratore.'));
        }
        return $this->redirect([$url]);
    }

    public function actionPublishVersion($id, $url) {
        $version = Utility::getVersion($id);
        $nav = Nav::findOne($version['nav_id']);
        $online = (($version['id'] == $version['nav_item_type_id'] && $nav['is_offline'] == 0) ? true : false);
        if ($online) {
            Yii::$app->getSession()->addFlash('warning', Module::txt('La versione è già quella pubblicata.'));
            return $this->redirect([$url]);
        }
        $ok = Utility::publishVersion($id, $version['item_id'], $version['nav_id']);
        if ($ok) {
            Yii::$app->getSession()->addFlash('success', Module::txt('Versione pubblicata correttamente.'));
            \Yii::$app->cache->flush();

            $navItem = \open20\elasticsearch\models\NavItem::findOne(['nav_id' => $nav->id]);
            $navItem->save();
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::txt('Si è verificato un problema durante la pubblicazione, contattare l\'amministratore.'));
        }
        return $this->redirect([$url]);
    }

    /**
     *
     * @param int $nav_id
     * @param type $url
     * @return type
     */
    public function actionUnpublishPage($nav_id, $url) {

        $ok = Utility::unpublishNav($nav_id);
        if ($ok) {
            Yii::$app->getSession()->addFlash('success', Module::txt('Pagina rimossa dalla pubblicazione correttamente.'));

            \Yii::$app->cache->flush();

            // Per l'aggiornamento degli indici di Elastic Search
            $moduleES = Yii::$app->getModule('elasticsearch');
            if ($moduleES) {
                $navItem = \open20\elasticsearch\models\NavItem::findOne(['nav_id' => $nav_id]);
                $navItem->save();
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::txt('Si è verificato un problema durante la spubblicazione, contattare l\'amministratore.'));
        }
        return $this->redirect([$url]);
    }

    public function actionDeleteVersion($id, $url) {
        $version = Utility::getVersion($id);
        $online = (($version['id'] == $version['nav_item_type_id']) ? true : false);
        if ($online) {
            Yii::$app->getSession()->addFlash('danger', Module::txt('Non è possibile cancellare la versione pubblicata.'));
            return $this->redirect([$url]);
        }
        $ok = Utility::deleteVersion($id);
        if ($ok) {
            Yii::$app->getSession()->addFlash('success', Module::txt('Versione cancellata correttamente.'));
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::txt('Si è verificato un problema durante la cancellazione, contattare l\'amministratore.'));
        }
        return $this->redirect([$url]);
    }

    public function actionPublicationRequest($nav_id, $item_id, $version_id, $url) {
        $model = new CmsWfRequest();
        if (!empty($version_id)) {
            $version = NavItemPage::findOne($version_id);
            $titleVersion = ($version->version_alias == 'Initial' ? Module::txt('Versione iniziale') : $version->version_alias);
            $model->url = \Yii::$app->urlManager->createAbsoluteUrl(['/' . Module::getModuleName() . '/d1/publish-version', 'id' => $version_id, 'url' => '/' . Module::getModuleName() . '/d1/pagine']);
        } else {
            $version = NavItem::findOne($item_id);
            $titleVersion = $version->title;
            $model->url = \Yii::$app->urlManager->createAbsoluteUrl(['/' . Module::getModuleName() . '/d1/publish-page', 'id' => $nav_id, 'url' => '/' . Module::getModuleName() . '/d1/pagine']);
        }
        $model->nav_id = $nav_id;
        $model->nav_item_id = $item_id;
        $model->nav_item_page_id = $version_id;
        $model->from_user = \Yii::$app->user->id;
        $model->title = 'Richiesta di pubblicazione di una pagina';
        $profile = \open20\amos\admin\models\UserProfile::find()
                ->andWhere(['user_id' => \Yii::$app->user->id])
                ->one();
        $preview = \Yii::$app->urlManager->createAbsoluteUrl(['/cms-page-preview', 'itemId' => $model->nav_item_id, 'version' => $version_id]);

        $model->hash = Utility::generateRandomHash();

        if ($model->save()) {
            $approve = \Yii::$app->urlManager->createAbsoluteUrl(['/' . Module::getModuleName() . '/d1/wf-approve', 'id' => $model->id, 'hash' => $model->hash, 'url' => '/' . Module::getModuleName() . '/d1/pagine']);

            $notApprove = \Yii::$app->urlManager->createAbsoluteUrl(['/' . Module::getModuleName() . '/d1/wf-refuse', 'id' => $model->id, 'hash' => $model->hash, 'url' => '/' . Module::getModuleName() . '/d1/pagine']);
            $model->description = "L'utente " . $profile->getNomeCognome() . " ha chiesto di la pubblicazione della seguente pagina:<br>"
                    . " <a href=\"$preview\" title=\"visualizza l'anteprima della pagina\">$titleVersion</a><br>"
                    . "Per pubblicare la pagina è possibile cliccando su uno dei link in basso oppure dalla propria dashboard oppure cliccando il seguente url di accettazione (previo login):<br>"
                    . " <a href=\"$approve\" title=\"accetta la pubblicazione\">" . Module::txt('Accetta la pubblicazione') . "</a><br>"
                    . " <a href=\"$notApprove\" title=\"rifiuta la pubblicazione\">" . Module::txt('Rifiuta la pubblicazione') . "</a><br>"
                    . "<br>";
            $model->save(false);
            Email::sendRequestPublicationMail($model);
            Yii::$app->getSession()->addFlash('success', Module::txt('Richiesta di pubblicazione effettuata correttamente.'));
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::txt('Richiesta di pubblicazione non andata a buon fine. Contattare l\'amministratore.'));
        }
        return $this->redirect($url);
    }

    public function actionUnpublishingRequest($nav_id, $item_id, $version_id, $url) {
        $model = new CmsWfRequest();
        $item = NavItem::findOne($item_id);
        $titleVersion = $item->title;
        $model->url = \Yii::$app->urlManager->createAbsoluteUrl(['/' . Module::getModuleName() . '/d1/unpublish-page', 'id' => $nav_id, 'url' => '/' . Module::getModuleName() . '/d1/pagine']);
        $model->nav_id = $nav_id;
        $model->nav_item_id = $item_id;
        $model->nav_item_page_id = $version_id;
        $model->from_user = \Yii::$app->user->id;
        $model->title = 'Richiesta di rimozione dalla pubblicazione di una pagina';
        $profile = \open20\amos\admin\models\UserProfile::find()
                ->andWhere(['user_id' => \Yii::$app->user->id])
                ->one();

        $preview = \Yii::$app->urlManager->createAbsoluteUrl(['/cms-page-preview', 'itemId' => $model->nav_item_id, 'version' => $version_id]);

        $model->hash = Utility::generateRandomHash();

        if ($model->save()) {
            $approve = \Yii::$app->urlManager->createAbsoluteUrl(['/' . Module::getModuleName() . '/d1/wf-approve', 'id' => $model->id, 'hash' => $model->hash, 'url' => '/' . Module::getModuleName() . '/d1/pagine']);

            $notApprove = \Yii::$app->urlManager->createAbsoluteUrl(['/' . Module::getModuleName() . '/d1/wf-refuse', 'id' => $model->id, 'hash' => $model->hash, 'url' => '/' . Module::getModuleName() . '/d1/pagine']);
            $model->description = "L'utente " . $profile->getNomeCognome() . " ha chiesto di la rimozione dalla pubblicazione della seguente pagina:<br>"
                    . " <a href=\"$preview\" title=\"visualizza l'anteprima della pagina\">$titleVersion</a><br>"
                    . "Per rimuovere la pagina è possibile farlo da uno dei link in basso oppure dalla propria dashboard oppure cliccando il seguente url di accettazione (previo login):<br>"
                    . " <a href=\"$approve\" title=\"accetta la pubblicazione\">" . Module::txt('Accetta la rimozione dalla pubblicazione') . "</a><br>"
                    . " <a href=\"$notApprove\" title=\"rifiuta la pubblicazione\">" . Module::txt('Rifiuta la rimozione dalla pubblicazione') . "</a><br>"
                    . "<br>";
            $model->save(false);
            Email::sendRequestPublicationMail($model);
            Yii::$app->getSession()->addFlash('success', Module::txt('Richiesta di rimozione effettuata correttamente.'));
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::txt('Richiesta di rimozione non andata a buon fine. Contattare l\'amministratore.'));
        }
        return $this->redirect($url);
    }

    public function actionWfApprove($id, $hash, $url) {
        $model = CmsWfRequest::find()->andWhere(['id' => $id])
                ->andWhere(['hash' => $hash])
                ->andWhere(['processed' => 0])
                ->one();
        if (empty($model)) {
            Yii::$app->getSession()->addFlash('info', Module::txt('Mi spiace, ma non è stata trovata nessuna richiesta.'));
        } else {
            $model->processed = 1;
            $model->processed_by_user = \Yii::$app->user->id;
            $model->save(false);
            Email::sendApprovedMail($model);
            return $this->redirect($model->url);
        }
        return $this->redirect($url);
    }

    public function actionWfRefuse($id, $hash, $url) {
        $model = CmsWfRequest::find()->andWhere(['id' => $id])
                ->andWhere(['hash' => $hash])
                ->andWhere(['processed' => 0])
                ->one();
        if (empty($model)) {
            Yii::$app->getSession()->addFlash('info', Module::txt('Mi spiace, ma non è stata trovata nessuna richiesta.'));
            return $this->redirect($url);
        }

        if (\Yii::$app->request->post() && $model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            Email::sendRefusedMail($model);
            return $this->redirect($url);
        }

        return $this->render('_wf_refuse', ['model' => $model]);
    }

    public function actionPublishPage($id, $url) {
        $model = Nav::findOne($id);
        $model->is_offline = 0;
        $model->save(false);
        Yii::$app->getSession()->addFlash('success', Module::txt('Pagina pubblicata correttamente.'));
        return $this->redirect($url);
    }

    public function actionCreateNewVersion($item_id, $page_id, $vname, $vvers = null) {
        $navItemModel = NavItem::findOne($item_id);
        $fromPageModel = NavItemPage::findOne((empty($vvers) ? $page_id : $vvers));

        $layoutId = $fromPageModel->layout_id;
        $model = new NavItemPage();
        $model->attributes = [
            'nav_item_id' => $item_id,
            'timestamp_create' => time(),
            'create_user_id' => \Yii::$app->adminuser->getId(),
            'version_alias' => $vname,
            'layout_id' => $layoutId,
        ];

        $save = $model->save(false);

        if ($save) {
            NavItemPage::copyBlocks($fromPageModel->id, $model->id);
        }

        if (empty($navItemModel->nav_item_type_id) && $navItemModel->nav_item_type == 1) {
            $navItemModel->updateAttributes(['nav_item_type_id' => $model->id]);
        }

        $navItemModel->updateAttributes(['timestamp_update' => time()]);

        return $this->redirect(['/admin#!/template/cmsadmin~2Fdefault~2Findex/update/' . $navItemModel->nav_id]);
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getDashboardContents($id = null) {
        $tableNav = Nav::tableName() . '.';
        $tableNavItem = NavItem::tableName() . '.';
        $tableWfRequest = CmsWfRequest::tableName() . '.';

        //////////////////// Ultime 10 news + pagine modificate

        $newsLast10Updated = News::find()
                ->select([
                    'created_at',
                    'created_by',
                    'data_pubblicazione',
                    'id',
                    'status',
                    'titolo',
                    'updated_at'
                ])
                ->andWhere(new \yii\db\Expression('created_at != updated_at'))
                ->orderBy(['updated_at' => SORT_DESC])
                ->limit(10)
                ->asArray()
                ->all();

        $pagesLast10Updated = NavItem::find()
                ->select([
                    'cms_nav_item.id',
                    'cms_nav_item.create_user_id',
                    'cms_nav_item.nav_id',
                    'cms_nav_item.timestamp_update',
                    'cms_nav_item.title',
                    'cms_nav_item.update_user_id'
                ])
                ->joinWith('nav')
                ->andWhere(['is_deleted' => 0])
                ->orderBy(['cms_nav_item.timestamp_update' => SORT_DESC])
                ->limit(10)
                ->asArray()
                ->all();

        $last10Updated = [];
        foreach ($newsLast10Updated as $v) {
            $last10Updated[$v['updated_at'] . '-news'][] = $v;
        }
        foreach ($pagesLast10Updated as $v) {
            $last10Updated[\Yii::$app->formatter->asDatetime($v['timestamp_update'], 'php:Y-m-d H:i:s') . '-navitem'][] = $v;
        }

        krsort($last10Updated);

        ////////////////////
        //////////////////// Ultime 10 news + pagine pubblicate
        $newsLast10Published = News::find()
                ->select([
                    'created_at',
                    'created_by',
                    'data_pubblicazione',
                    'id',
                    'titolo'
                ])
                ->andWhere(['status' => News::NEWS_WORKFLOW_STATUS_VALIDATO])
                ->andWhere(['not', ['data_pubblicazione' => null]])
                ->andWhere(['<=', 'data_pubblicazione', date('Y-m-d')])
                ->orderBy(['data_pubblicazione' => SORT_DESC])
                ->limit(10)
                ->all();

        $pagesLast10Published = NavItem::find()
                ->select([
                    $tableNavItem . 'id',
                    $tableNavItem . 'create_user_id',
                    $tableNavItem . 'nav_id',
                    $tableNavItem . 'title'
                ])
                ->joinWith('nav')
                ->andWhere(['is_deleted' => 0])
                ->andWhere(['is_offline' => 0])
                ->andWhere(['not', ['publish_from' => null]])
                ->orderBy(['publish_from' => SORT_DESC])
                ->limit(10)
                ->asArray()
                ->all();

        $last10Published = [];
        foreach ($newsLast10Published as $news) {
            $last10Published[$news['data_pubblicazione'] . '-news'] = $news;
        }
        foreach ($pagesLast10Published as $page) {
            $last10Published[\Yii::$app->formatter->asDatetime($page['nav']['publish_from'], 'php:Y-m-d H:i:s') . '-navitem'] = $page;
        }

        krsort($last10Published);
        ////////////////////
        //////////////////// News + pagine che saranno pubblicate
        $upcomingNews = News::find()
                ->select([
                    'created_at',
                    'created_by',
                    'data_pubblicazione',
                    'id',
                    'status',
                    'titolo'
                ])
                ->andWhere(['status' => News::NEWS_WORKFLOW_STATUS_VALIDATO])
                ->andWhere(['>=', 'data_pubblicazione', date('Y-m-d')])
                ->orderBy(['data_pubblicazione' => SORT_DESC])
                ->all();

        $upcomingPages = NavItem::find()
                ->select([
                    $tableNavItem . 'id',
                    $tableNavItem . 'create_user_id',
                    $tableNav . 'is_offline',
                    $tableNavItem . 'nav_id',
                    $tableNavItem . 'title'
                ])
                ->joinWith('nav')
                ->andWhere(['is_deleted' => 0])
                ->andWhere(['is_offline' => 1])
                ->andWhere(['>=', 'publish_from', strtotime(date('Y-m-d'))])
                ->orderBy(['publish_from' => SORT_DESC])
                ->asArray()
                ->all();

        $upcoming = [];
        foreach ($upcomingNews as $news) {
            $upcoming[$news['data_pubblicazione'] . '-news'] = $news;
        }
        foreach ($upcomingPages as $page) {
            $upcoming[\Yii::$app->formatter->asDatetime($page['nav']['publish_from'], 'php:Y-m-d H:i:s') . '-navitem'] = $page;
        }

        krsort($upcoming);
        ////////////////////
        //////////////////// Notizie + pagine da validare

        $validationRequestNews = News::find()
                ->select([
                    'created_at',
                    'created_by',
                    'data_pubblicazione',
                    'id',
                    'status',
                    'titolo'
                ])
                ->andWhere(['status' => News::NEWS_WORKFLOW_STATUS_DAVALIDARE])
                ->andWhere(['or',
                    ['>=', 'data_pubblicazione', date('Y-m-d')],
                    ['is', 'data_pubblicazione', null],
                ])
                ->orderBy(['data_pubblicazione' => SORT_DESC])
                ->andFilterWhere(['updated_by' => $id]) // Se l'utente � redattore, $id � il suo id, altrimenti � null
                ->all();

        $validationRequestPages = NavItem::find()
                ->select([
                    $tableWfRequest . 'from_user',
                    $tableNavItem . 'id',
                    $tableNavItem . 'nav_id',
                    $tableNavItem . 'title'
                ])
                ->join('JOIN', CmsWfRequest::tableName(), CmsWfRequest::tableName() . '.nav_item_id = ' . NavItem::tableName() . '.id')
                ->joinWith('nav')
                ->andWhere(['is_deleted' => 0])
                ->andWhere(['processed' => 0])
                ->andWhere(['is_offline' => 1])
                ->andWhere(['or',
                    ['>=', 'publish_from', strtotime(date('Y-m-d'))],
                    ['is', 'publish_from', null],
                ])
                ->orderBy(['publish_from' => SORT_DESC])
                ->andFilterWhere(['updated_by' => $id])
                ->asArray()
                ->all();

        $validationRequests = [];
        foreach ($validationRequestNews as $news) {
            $validationRequests[$news['data_pubblicazione'] . '-news'] = $news;
        }
        foreach ($validationRequestPages as $page) {
            $validationRequests[\Yii::$app->formatter->asDatetime($page['nav']['publish_from'], 'php:Y-m-d H:i:s') . '-navitem'] = $page;
        }
        krsort($validationRequests);

        ////////////////////

        $contents = [
            'last10Updated' => array_slice($last10Updated, 0, 10, true),
            'last10Published' => array_slice($last10Published, 0, 10, true),
            'upcoming' => array_slice($upcoming, 0, 10, true),
            'validationRequests' => array_slice($validationRequests, 0, 10, true),
        ];

        return $contents;
    }

    public function actionNewSort($id, $up, $dest, $url = null, $container = 'default') {
        $model = Nav::findOne($id);

        $modelDest = Nav::findOne($dest);

        if (!empty($model) && !empty($modelDest)) {
            if ($up == 3) {
                $newIndex = $modelDest->sort_index + 1;
                $model->sort_index = $newIndex;
                $model->parent_nav_id = $modelDest->id;
                $allModelsUp = Nav::find()
                        ->andWhere(['>', 'sort_index', $modelDest->sort_index])
                        ->andWhere(['nav_container_id' => $model->nav_container_id])
                        ->andWhere(['parent_nav_id' => $modelDest->parent_nav_id])
                        ->andWhere(['is_deleted' => 0])
                        ->andWhere(['<>', 'id', $model->id])
                        ->orderBy('sort_index asc')
                        ->all();
                foreach ($allModelsUp as $mup) {
                    $newIndex++;
                    $mup->sort_index = $newIndex;
                    $mup->save(false);
                }
                $model->save(false);
            } else if ($up == 1) {
                $newIndex = $modelDest->sort_index + 1;
                $model->sort_index = $newIndex;
                $model->parent_nav_id = $modelDest->parent_nav_id;
                $allModelsUp = Nav::find()
                        ->andWhere(['>', 'sort_index', $modelDest->sort_index])
                        ->andWhere(['nav_container_id' => $model->nav_container_id])
                        ->andWhere(['parent_nav_id' => $modelDest->parent_nav_id])
                        ->andWhere(['is_deleted' => 0])
                        ->andWhere(['<>', 'id', $model->id])
                        ->orderBy('sort_index asc')
                        ->all();
                foreach ($allModelsUp as $mup) {
                    $newIndex++;
                    $mup->sort_index = $newIndex;
                    $mup->save(false);
                }
                $model->save(false);
            } else {
                $newIndex = $modelDest->sort_index;
                $model->sort_index = $newIndex;
                $model->parent_nav_id = $modelDest->parent_nav_id;
                $allModelsUp = Nav::find()
                        ->andWhere(['>=', 'sort_index', $modelDest->sort_index])
                        ->andWhere(['nav_container_id' => $model->nav_container_id])
                        ->andWhere(['parent_nav_id' => $modelDest->parent_nav_id])
                        ->andWhere(['is_deleted' => 0])
                        ->andWhere(['<>', 'id', $model->id])
                        ->orderBy('sort_index asc')
                        ->all();
                //metto pi� uno altrimenti ci sar� model ed almeno un mup con lo stesso order index 12uando si salvano i mup
                $newIndex = $newIndex + 1;
                foreach ($allModelsUp as $mup) {
                    $newIndex++;
                    $mup->sort_index = $newIndex;
                    $mup->save(false);
                }
                $model->save(false);
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::txt('Ordinamento non modificato, verificare i dati inseriti.'));
        }
        Yii::$app->getSession()->addFlash('success', Module::txt('Ordinamento modificato con successo.'));

        Yii::$app->cache->flush();

        return $this->redirect(['pagine', 'container' => $container, 'openPage' => $id]);
    }

    public function actionLuyaAdminMenu($view = '_subpage') {

        $canPublish = Yii::$app->request->post('canPublish') ?: false;
        $lvl = Yii::$app->request->post('lvl') ?: 0;
        $container = Yii::$app->request->post('container') ?: 'default';
        $parentNavId = Yii::$app->request->post('expandRowKey');
        $ancestors = Yii::$app->request->post('ancestors') ?: [];
        $postSearch = Yii::$app->request->post('postSearch');

        $provider = Utility::getAdminMenuLuya($container, $parentNavId, false, $postSearch, false);
        $provider->pagination = false;

        return Yii::$app->controller->renderAjax(
            $view,
            [
                'dataProvider' => $provider,
                'container' => $container,
                'canPublish' => $canPublish,
                'lvl' => $lvl,
                'ancestors' => $ancestors
            ]
        );
    }

    public function actionSetVisibilityMenu($id, $value = 0, $url = null, $container = 'default') {
        $model = Nav::findOne($id);
        if (!empty($model)) {
            $model->is_hidden = $value;
            if ($model->save(false)) {
                Yii::$app->getSession()->addFlash('success', Module::txt('Visibilita della voce di menu impostata correttamente.'));
                $this->menuFlush();
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::txt('Visibilita della voce di menu non impostata. Verificare i dati della voce.'));
            }
        }
        return $this->redirect(['pagine', 'container' => $container, 'openPage' => $id]);
    }

    public function actionModelli() {

        $this->setUpLayout('list');
        $get = Yii::$app->request->get();

        $menu = Utility::getLuyaTemplate(false, $get);
        $menuQuery = Utility::getAdminMenuLuyaTree(null, true);

        $titleSection = Module::txt('Modelli di pagina');

        $subTitleSection = '<p class="lead m-t-20">' . Module::t('amosdashboards', 'I modelli di pagina sono layout preconfigurati: ti aiutano a comporre più rapidamente le pagine utilizzando i componenti a disposizione all\'interno di schemi ricorrenti.') . '</p>';
        ;

        $labelCreate = Module::txt('Nuovo');
        $titleCreate = Module::txt('Crea un nuovo modello di pagina');

        $urlCreate = '/' . Module::getModuleName() . '/d1/crea-modello';

        $hideCreate = false;

        \Yii::$app->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'pagine',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            //            'urlLinkAll' => $urlLinkAll,
            //            'labelLinkAll' => $labelLinkAll,
            //            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            //            'labelManage' => $labelManage,
            //            'titleManage' => $titleManage, 
            'urlCreate' => $urlCreate,
            'hideCreate' => $hideCreate,
            'canCreate' => true, //da completare con i permessi
                //            'urlManage' => $urlManage,
        ];
        $model = new DynamicModel(['container', 'search', 'item', 'updown', 'status', 'type', 'updated_by']);
        $model->addRule(['container', 'search'], 'string');
        $model->addRule(['item', 'updown'], 'integer');
        $model->setAttributeLabels([
            'container' => Module::txt('Mostra'),
            'search' => Module::txt('Ricerca libera'),
        ]);

        if (!empty($get['DynamicModel']) && !empty($get['DynamicModel']['search'])) {
            $model->search = $get['DynamicModel']['search'];
        }
        $this->view->params['containerFullWidth'] = true;
        return $this->render('layouts', ['dataProvider' => $menu, 'modelContainer' => $model, 'menuQuery' => $menuQuery]);
    }

    public function actionCreaModello($url = null) {
        /** @var Module $dashboardModule */
        $dashboardModule = Module::instance();

        $this->setUpLayout('form');

        $obj = new DynamicModel([
            'title', 'clone', 'alias', 'isNewRecord', 'description', 'lang_id', 'layout_id', 'is_draft',
            'parent_id', 'nav_container_id', 'keywords', 'title_tag', 'publish_from', 'publish_till'
        ]);

        $modelimage = new \app\modules\cms\models\NavItem();
        $obj->isNewRecord = true;
        $obj->addRule(['lang_id', 'layout_id', 'is_draft', 'parent_id', 'nav_container_id', 'clone'], 'integer');
        $obj->addRule(['title', 'alias', 'title_tag'], 'string', ['max' => $dashboardModule->defaultTitlePageLength]);
        $obj->addRule(['title', 'alias'], 'filter', ['filter' => 'trim']);
        $obj->addRule(['description', 'keywords'], 'string');
        $obj->addRule(['isNewRecord'], 'safe');
        $obj->addRule(['title'], 'required');
        $obj->addRule(['publish_from', 'publish_till'], 'safe');
        $obj->setAttributeLabels([
            'title' => Module::txt('Titolo'),
            'clone' => Module::txt('Copia da una pagina esistente'),
        ]);
        $menuQuery = Utility::getAdminMenuLuyaTree(null, true, [], 1);

        if (\Yii::$app->request->post() && $obj->load(\Yii::$app->request->post()) && $modelimage->load(\Yii::$app->request->post()) && $obj->validate()) {
            $navId = \Yii::$app->request->post()['DynamicModel']['clone'];
            if (empty($navId)) {
                $obj->lang_id = $this->getDefaultLanguageId();
                $obj->layout_id = Module::instance()->defaultLayoutId;

                $obj->alias = Utility::getAlias($obj->title, $obj->alias);
                $obj->parent_id = 0;
                $obj->description = '';
                $obj->is_draft = 1;
                $obj->nav_container_id = Module::instance()->getDefaultContainerId();
                $other = [
                    'Nav' => [
                        'is_deleted' => 0,
                        'is_home' => 0,
                    ],
                    'NavItem' => [
                        'is_url_strict_parsing_disabled' => 0,
                        'is_cacheable' => 0,
                    ]
                ];
                $model = $this->createPage($obj, $other);
                if (!empty($model->id)) {
                    Yii::$app->getSession()->addFlash('success', Module::txt('Modello creato con successo'));
                    if (!empty($url)) {
                        return $this->redirect([$url]);
                    }
                    return $this->redirect(['/admin#!/template/cmsadmin~2Fdefault~2Findex/update/' . $model->id]);
                }
                Yii::$app->getSession()->addFlash('danger', Module::txt('Verificare i dati inseriti.'));
            } else {

                $nav = Nav::findOne($navId);
                $navItemModel = NavItem::find()->andWhere(['nav_id' => $navId])->one();
                $fromPageModel = NavItemPage::findOne($navItemModel->nav_item_type_id);

                $layoutId = $fromPageModel->layout_id;
                $navNew = new Nav();
                $navItemNew = new NavItem();
                $navItemPageNew = new NavItemPage();
                $transaction = Nav::getDb()->beginTransaction();
                try {
                    $navNew->nav_container_id = $nav->nav_container_id;
                    $navNew->parent_nav_id = 0;
                    $navNew->is_deleted = 0;
                    $navNew->is_hidden = 1;
                    $navNew->is_home = 0;
                    $navNew->is_offline = 1;
                    $navNew->is_draft = 1;
                    if ($navNew->save(false)) {
                        $navItemNew->nav_id = $navNew->id;
                        $navItemNew->lang_id = $this->getDefaultLanguageId();
                        $navItemNew->nav_item_type = 1;
                        $navItemNew->create_user_id = \Yii::$app->adminuser->getId();
                        $navItemNew->update_user_id = \Yii::$app->adminuser->getId();
                        $navItemNew->timestamp_create = time();
                        $navItemNew->title = $obj->title;
                        $navItemNew->alias = $obj->alias;
                        $navItemNew->description = $obj->description;
                        $navItemNew->keywords = $obj->keywords;
                        $navItemNew->title_tag = $obj->title_tag;
                        $navItemNew->is_url_strict_parsing_disabled = 0;
                        $navItemNew->is_cacheable = 0;
                        if ($navItemNew->save(false)) {
                            $navItemPageNew->nav_item_id = $navItemNew->id;
                            $navItemPageNew->layout_id = $fromPageModel->layout_id;
                            $navItemPageNew->timestamp_create = time();
                            $navItemPageNew->create_user_id = \Yii::$app->adminuser->getId();
                            $navItemPageNew->version_alias = 'Initial';
                            if ($navItemPageNew->save(false)) {
                                $navItemNew->nav_item_type_id = $navItemPageNew->id;
                                if ($navItemNew->save(false)) {
                                    $ok = NavItemPage::copyBlocks($fromPageModel->id, $navItemPageNew->id);
                                    if ($ok) {
                                        $transaction->commit();
                                        Yii::$app->getSession()->addFlash('success', Module::txt('Modello creato con successo'));
                                        if (!empty($url)) {
                                            return $this->redirect([$url]);
                                        }
                                        return $this->redirect(['/admin#!/template/cmsadmin~2Fdefault~2Findex/update/' . $navNew->id]);
                                    }
                                }
                            }
                        }
                    }
                    Yii::$app->getSession()->addFlash('danger', Module::txt('Modello non creato, verificare i dati.'));

                    $transaction->rollBack();
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        return $this->render('_layout', ['model' => $obj, 'menuQuery' => $menuQuery, 'modelimage' => $modelimage]);
    }

    public function actionAggiornaModello($nav_id, $url = null) {
        /** @var Module $dashboardModule */
        $dashboardModule = Module::instance();

        $this->setUpLayout('form');
        $nav = Nav::findOne($nav_id);
        $obj = new DynamicModel([
            'title', 'clone', 'alias', 'isNewRecord', 'description', 'lang_id', 'layout_id', 'is_draft',
            'parent_id', 'nav_container_id', 'keywords', 'title_tag', 'publish_from', 'publish_till'
        ]);

        $modelimage = \app\modules\cms\models\NavItem::find()->andWhere(['nav_id' => $nav_id])->one();
        $obj->isNewRecord = false;
        $obj->addRule(['lang_id', 'layout_id', 'is_draft', 'parent_id', 'nav_container_id', 'clone'], 'integer');
        $obj->addRule(['title', 'alias', 'title_tag'], 'string', ['max' => $dashboardModule->defaultTitlePageLength]);
        $obj->addRule(['title', 'alias'], 'filter', ['filter' => 'trim']);
        $obj->addRule(['description', 'keywords'], 'string');
        $obj->addRule(['isNewRecord'], 'safe');
        $obj->addRule(['title'], 'required');

        $obj->setAttributeLabels([
            'title' => Module::txt('Titolo'),
            'clone' => Module::txt('Copia da una pagina esistente'),
        ]);
        $obj->title = $modelimage->title;
        $menuQuery = Utility::getAdminMenuLuyaTree(null, true, [], 1);

        if (\Yii::$app->request->post() && $obj->load(\Yii::$app->request->post()) && $modelimage->load(\Yii::$app->request->post()) && $obj->validate()) {
            $modelimage->title = $obj->title;
            if ($modelimage->save(false)) {
                Yii::$app->getSession()->addFlash('success', Module::txt('Modello salvato con successo.'));
                if (!empty($url)) {
                    return $this->redirect([$url]);
                }
                return $this->redirect(['modelli']);
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::txt('Modello non salvato, verificare i dati.'));
            }
        }


        return $this->render('_layout', ['model' => $obj, 'menuQuery' => $menuQuery, 'modelimage' => $modelimage]);
    }

    public function actionRendiHome($navId) {
        // /admin/api-cms-nav/toggle-home?homeState=1&navId=1
    }

    public function actionSeoUrl($title) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $alias = Utility::getAlias($title);
        return $alias;
    }

    /**
     * Flush the menu data if component exits.
     *
     * @since 1.0.6
     */
    protected function menuFlush() {
        if (Yii::$app->get('menu', false)) {
            Yii::$app->menu->flushCache();
        }
    }

    /**
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionAjaxUserList($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(new Expression("admin_user.id, CONCAT(firstname, ' ', lastname) AS text"))
                    ->from('admin_user')
                    ->andWhere(['or',
                        ['like', 'lastname', $q],
                        ['like', 'firstname', $q],
                        ['like', "CONCAT( firstname , ' ', lastname )", $q],
                        ['like', "CONCAT( lastname , ' ', firstname )", $q],
                    ])
                    ->andWhere(['is_deleted' => 0])
                    ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }

    public function actionAjaxProfiles($q = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(new Expression("id, name AS text"))
                    ->from('user_profile_classes')
                    ->andWhere(['like', 'name', $q])
                    ->andWhere(['deleted_at' => null])
                    ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }

    public function actionAjaxVersions($id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($id)) {
            $query = new Query();
            $query->select(new Expression("cms_nav_item_page.id AS id, REPLACE(cms_nav_item_page.version_alias, 'Initial', '". \open20\cms\dashboard\Module::txt('Versione iniziale')." ') AS text"))
                    ->from('cms_nav_item_page')
                    ->innerJoin('cms_nav_item', 'cms_nav_item.id = cms_nav_item_page.nav_item_id and cms_nav_item.id = ' . $id);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }

    public function setRbacProfiles($modelid, $profiles) {

        \Yii::$app->db->createCommand()
                ->delete('cms_page_profiles', 'nav_id=:id', [':id' => $modelid])
                ->execute();

        $nav_property = new \amos\userauth\frontend\properties\RolePermissionsProperty();
        $admin_prop = \luya\admin\models\Property::findOne(['var_name' => $nav_property->varName()]);
        \Yii::$app->db->createCommand()
                ->delete('cms_nav_property', 'nav_id=:id and admin_prop_id=:prop', [':id' => $modelid, ':prop' => $admin_prop->id])
                ->execute();

        if (!empty($profiles)) {
            $auths = \open20\amos\admin\models\UserProfileClassesAuthMm::find()
                    ->andWhere(['in', 'user_profile_classes_id', $profiles])
                    ->select('item_id')
                    ->column();
            foreach ($profiles as $pr) {
                \Yii::$app->db
                        ->createCommand()
                        ->insert('cms_page_profiles', [
                            'nav_id' => $modelid,
                            'user_profile_classes_id' => $pr,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_by' => \Yii::$app->user->id,
                            'updated_by' => \Yii::$app->user->id,
                        ])->execute();
            }
            $profilesString = implode(',', $auths);
            $this->setRbacPermission($modelid, $profilesString, $profiles);
        }
    }

    public function setRbacPermission($modelid, $permissions, $profiles = []) {

        $array = [];
        if ($permissions != '') {
            $permission_array = explode(',', $permissions);
            foreach ($permission_array as $permission) {
                $array[] = [
                    'value' => $permission,
                ];
            }
        }

        $arrayProfiles = [];
        if(!empty($profiles)) {
            foreach($profiles as $profile) {
                $arrayProfiles[] = [
                    'value' => $profile
                ];
            }
        }

        $propriety_id = Yii::$app->db
                        ->createCommand('SELECT id FROM admin_property WHERE module_name =:module_name AND var_name =:var_name')
                        ->bindValue(':module_name', 'userauthfrontend')
                        ->bindValue(':var_name', 'rolePermissions')->queryOne();

        $count = Yii::$app->db
                        ->createCommand('SELECT * FROM cms_nav_property WHERE nav_id =:nav_id AND admin_prop_id =:admin_prop_id')
                        ->bindValue(':nav_id', $modelid)
                        ->bindValue(':admin_prop_id', $propriety_id['id'])->queryOne();

        if ($count) {
            if (!empty($array)) {
                Yii::$app->db
                        ->createCommand()
                        ->update("cms_nav_property",
                                ['value' => json_encode($array)], ['nav_id' => $modelid, 'admin_prop_id' => $propriety_id['id']])
                        ->execute();
            } else {
                Yii::$app->db
                        ->createCommand()
                        ->delete('cms_nav_property', ['nav_id' => $modelid, 'admin_prop_id' => $propriety_id['id']])
                        ->execute();
            }
        } else {
            if (!empty($array)) {
                if(!empty($arrayProfiles)) {
                    $array = $arrayProfiles;
                }
                Yii::$app->db
                        ->createCommand()
                        ->insert('cms_nav_property', [
                            'nav_id' => $modelid,
                            'admin_prop_id' => $propriety_id['id'],
                            'value' => json_encode($array),
                        ])->execute();
            }
        }
    }

    public function getRbacProfiles($modelid) {
        $allProfiles = \Yii::$app->db->createCommand("select user_profile_classes_id as id from cms_page_profiles inner join user_profile_classes "
                        . " on user_profile_classes.id = user_profile_classes_id where nav_id = " . $modelid)->queryColumn();
        return $allProfiles;
    }

    /**
     * @param $modelid
     * return string
     */
    public function getRbacPermission($modelid) {
        return Utility::getRbacPermission($modelid);
    }
}
