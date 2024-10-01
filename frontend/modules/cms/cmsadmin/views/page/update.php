<?php

use luya\cms\admin\Module;
use luya\admin\Module as AdminModule;
use luya\cms\helpers\Url;
use open20\cms\dashboard\Module as OpenModule;
use yii\helpers\Html;
use open20\cms\dashboard\utilities\Utility;
use open20\amos\admin\models\UserProfile;

$navId = filter_input(INPUT_GET, 'navId');
$nav = \app\modules\cms\models\Nav::findOne($navId);
$navItem = \app\modules\cms\models\NavItem::find()->andWhere(['nav_id' => $navId])->one();
$container = (new \yii\db\Query())->from('cms_nav_container')->where(['id' => $nav->nav_container_id])->one();
$canPublish = \Yii::$app->user->can('CMS_PUBLISH_PAGE');
$nomeCognome = 'N.d.';
$userCms = Utility::getUserOpenFromCms(!empty($navItem->update_user_id) ? $navItem->update_user_id : $navItem->create_user_id);
if (!empty($userCms)) {
    $userProfile = UserProfile::find()->innerJoin('user', 'user_profile.user_id = user.id')->select('nome,cognome')->andWhere(['user.email' => $userCms['email']])->one();
    if (!empty($userProfile)) {
        $nomeCognome = $userProfile->nome . ' ' . $userProfile->cognome;
    }
}
?>
<div class="cmsadmin" ng-controller="NavController" ng-show="!isDeleted" ng-class="{'cmsadmin-blockholder-collapsed' : !isBlockholderSmall}">

    <?= $this->render('_settings'); ?>
    <div class="row">
        <div class="col cmsadmin-frame-wrapper" ng-if="displayLiveContainer">
            <iframe class="cmsadmin-frame" ng-src="{{ liveUrl | trustAsResourceUrl:liveUrl }}" frameborder="0" width="100%" height="100%"></iframe>
        </div>
        <div class="col">
            <nav class="breadcrumb-container" aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="/site/to-menu-url?url=/it/" title="home">
                            <span class="mdi mdi-home">
                            </span>
                        </a>
                        <span class="separator">/</span>
                    </li>
                    <li class="breadcrumb-item"><a href="/dashboards/d1/<?= ($nav->is_draft ? 'modelli' : 'pagine') ?>" action="view" title="<?= ($nav->is_draft ? 'Modelli' : 'Pagine') ?>"><?= ($nav->is_draft ? 'Modelli' : 'Pagine') ?></a><span class="separator">/</span></li>
                    <li class="breadcrumb-item active"><?= $navItem->title ?></li>
                </ol>
            </nav>
            <div class="cmsadmin-toolbar d-flex">
                <div class="last-modify-open2">
                    <p class="mb-0"><?= OpenModule::txt('<strong>Salvataggio automatico:</strong> abilitato <span class="mdi mdi-information-outline" title="Ogni modifica alla versione corrente verrà automaticamente salvata" data-toggle="tooltip"></span>'); ?></p>
                    <p class="mb-0"><?= OpenModule::txt('<strong>Ultima modifica') . ':</strong>' . ' '?><span  id="last-updated-info" class="mdi mdi-autorenew"><?=$nomeCognome . ' il ' .  \Yii::$app->formatter->asDatetime(
                        !empty($navItem->timestamp_update) ? $navItem->timestamp_update : $navItem->timestamp_create,
                        'php:d/m/Y'
                    ) . ' alle ' . \Yii::$app->formatter->asDatetime(
                        !empty($navItem->timestamp_update) ? $navItem->timestamp_update : $navItem->timestamp_create,
                        'php:H:i'
                    );  ?></p>
                </div>
                <?php /*
                  <div ng-show="!isDraft" class="toolbar-item" tooltip tooltip-text="<?= Module::t('view_update_hidden_info') ?>" tooltip-position="bottom">
                  <label class="switch" for="switch-visibility-status">
                  <span class="switch-label">
                  <i class="material-icons" ng-show="!navData.is_hidden">visibility</i>
                  <i class="material-icons" ng-show="navData.is_hidden">visibility_off</i>
                  </span>
                  <span class="switch-switch">
                  <input class="switch-checkbox" ng-model="navData.is_hidden" ng-true-value="0" ng-false-value="1" type="checkbox" id="switch-visibility-status" />
                  <span class="switch-control"></span>
                  </span>
                  </label>
                  </div>
                  <div ng-show="!isDraft" class="toolbar-item" tooltip tooltip-text="<?= Module::t('view_update_offline_info') ?>" tooltip-position="bottom">
                  <label class="switch" for="switch-online-status">
                  <span class="switch-label">
                  <i class="material-icons" ng-show="!navData.is_offline">cloud_queue</i>
                  <i class="material-icons" ng-show="navData.is_offline">cloud_off</i>
                  </span>
                  <span class="switch-switch">
                  <input class="switch-checkbox" type="checkbox" id="switch-online-status" ng-model="navData.is_offline" ng-true-value="0" ng-false-value="1" />
                  <span class="switch-control"></span>
                  </span>
                  </label>
                  </div>
                  <div class="toolbar-item toolbar-item-lang" ng-class="{'ml-auto':$first}" ng-repeat="lang in AdminLangService.data" ng-click="AdminLangService.toggleSelection(lang)" ng-if="AdminLangService.data.length > 1">
                  <button class="btn-toolbar flag-btn" ng-class="{'active' : AdminLangService.isInSelection(lang.short_code)}">
                  <span class="flag flag-{{lang.short_code}}">
                  <span class="flag-fallback">{{lang.name}}</span>
                  </span>
                  </button>
                  </div>
                 */ ?>
                <?php
                //Modifica proprietà
                $buttons = [];
                $url = '/admin#!/template/cmsadmin~2Fdefault~2Findex/update/' . $navId;
                $buttons[] = '<li>' . Html::a(
                    OpenModule::txt('Modifica proprietà'),
                    ['/' . OpenModule::getModuleName() . '/d1/update-page', 'id' => $navId, 'container' => $container['alias'], 'url' => $url],
                    [
                        'title' => OpenModule::txt('Modifica proprietà'),
                        //'class' => 'btn btn-tool-secondary',
                    ]
                ) . '</li>';

                //Crea pagina figlia
                $buttons[] = '<li>' . Html::a(
                    OpenModule::txt('Crea pagina figlia'),
                    \Yii::$app->urlManager->createUrl(
                        [
                            '/' . OpenModule::getModuleName() . '/d1/create-page',
                            'parent_id' => $navId,
                        ]
                    ),
                    [
                        'title' => OpenModule::txt('Crea pagina figlia'),
                        //'class' => 'btn btn-tool-primary',
                        //'style' => 'border-bottom: 1px solid #ccc;'
                    ]
                ) . '</li>';

                if($nav->is_offline){
                    $ver = 0;
                    if($navItem->nav_item_type == 1)
                        $ver = $navItem->nav_item_type_id;
                   
                    if($ver)
                        $urlP = ['/' . OpenModule::getModuleName() . '/d1/publish-version', 'id' => $ver, 'url' => '/dashboards/d1/pagine'];
                    else
                        $urlP = ['/' . OpenModule::getModuleName() . '/d1/publish-page', 'nav_id' => $navId, 'url' => '/dashboards/d1/pagine'];
                    
                    if(!$canPublish)
                        $urlP = ['/' . OpenModule::getModuleName() . '/d1/publication-request', 'nav_id' => $navId, 'item_id' => $navItem->id, 'version_id' => $ver, 'url' => $url];
                            
                    $buttons[] = '<li>' . Html::a(
                        $canPublish ? OpenModule::txt('Pubblica la pagina') : OpenModule::txt('Richiedi la pubblicazione della pagina'),
                        $urlP,
                        [
                            'title' => OpenModule::txt('Richiedi la pubblicazione della pagina'),
                            'style' => 'color:#297a38;',
                            'data-confirm' => OpenModule::txt('Sei sicuro di voler pubblicare la pagina?'),
                        ]
                    ) . '</li>';
                }else{
                    
                    $urlP = ['/' . OpenModule::getModuleName() . '/d1/unpublishing-request', 'nav_id' => $navId, 'url' => '/dashboards/d1/pagine'];
                    
                    if($canPublish)
                        $urlP = ['/' . OpenModule::getModuleName() . '/d1/unpublish-page', 'nav_id' => $navId, 'url' => '/dashboards/d1/pagine'];
                    
                    if(!$nav->is_home)
                        $buttons[] = '<li>' . Html::a(
                            $canPublish ? OpenModule::txt('Riporta in Bozza') : OpenModule::txt('Riporta la pagina allo stato Bozza'),
                            $urlP,
                            [
                                'title' => OpenModule::txt('Riporta la pagina allo stato Bozza'),
                                'style' => 'color:#a61919; border-top:1px solid #ccc',
                                'data-confirm' => OpenModule::txt('Sei sicuro di voler riportare la pagina in stato Bozza?<br>La pagina non sarà più raggiungibile da url.'),
                            ]
                        ) . '</li>';
                    
                    $buttons[] = '<li>' . Html::a(
                        $nav->is_hidden ? OpenModule::txt('Rendi visibile nel menu') : OpenModule::txt('Nascondi nel menu'),
                        ['/' . OpenModule::getModuleName() . '/d1/set-visibility-menu', 'id' => $navId, 'value'=> !$nav->is_hidden ,'url' => '/dashboards/d1/pagine'],
                        [
                            'title' => $nav->is_hidden ? OpenModule::txt('Rendi visibile nel menu') : OpenModule::txt('Nascondi nel menu'),
                            
                        ]
                    ) . '</li>';
                }
                
                //Cancella pagina
                $disabled = false;
                if (!$nav->is_offline && !$canPublish) {
                    $disabled = true;
                }
                $children = Utility::getAdminMenuLuya($container['alias'], $navId, true);
                /** @var \open20\amos\sondaggi\models\search\SondaggiDomandeSearch $model */
                if ($children->count() > 0 || $nav->is_offline == 0) {
                    $disabled = true;
                }

                $buttons[] = '<li>' . Html::a(OpenModule::txt('Elimina'), ($disabled ? '#' : Yii::$app->urlManager->createUrl([
                    '/' . OpenModule::getModuleName() . '/d1/delete-page',
                    'id' => $navId,
                    'item_id' => $navItem->id,
                    'container' => $container['alias'],
                    'url' => '/' . OpenModule::getModuleName() . '/d1/' . ($nav->is_draft ? 'modelli' : 'pagine'),
                ])), [
                    'title' => ($disabled ? OpenModule::txt('Non è possibile eliminare la pagina in quanto online oppure ha dei figli.') : OpenModule::txt('Elimina')),
                    //'class' => 'btn btn-danger-inverse',
                    'style' => ($disabled ? 'display:none;' : 'color:#a61919; border-top:1px solid #ccc'),
                    'data-confirm' => ($disabled ? null : OpenModule::txt('Sei sicuro di voler eliminare la pagina {pageName}? L\'operazione non è reversibile.', ['pageName' => $navItem->title])),
                ]) . '</li>';
                ?>
                <div class="dropdown ml-auto">
                    <button id="open2ButtonActions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-dropdown dropdown-toggle btn-outline-primary rounded">
                        <span class="mdi mdi-dots-vertical"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="open2ButtonActions">
                        <?= implode('', $buttons) ?>
                    </ul>
                </div>
                <?php /*
                  <div class="toolbar-item" ng-class="{'ml-auto': AdminLangService.data.length <= 1}">
                  <div class="dropdown" ng-class="{'show': toggleSettings}" ng-click="toggleSettings=!toggleSettings">
                  <button type="button" class="btn btn-outline-config btn-icon" ng-class="{'btn-active': toggleSettings}"></button>
                  <div class="dropdown-menu dropdown-menu-right" ng-class="{'show': toggleSettings}">
                  <a class="dropdown-item" ng-click="togglePageSettingsOverlay(9)">
                  <i class="material-icons">tag</i> <span><?= AdminModule::t('menu_system_item_tags'); ?></span>
                  </a>
                  <a class="dropdown-item" ng-click="togglePageSettingsOverlay(2)" ng-if="propertiesData.length > 0">
                  <i class="material-icons">settings</i> <span><?= Module::t('view_update_properties_title'); ?></span>
                  </a>
                  <a class="dropdown-item" ng-show="!isDraft" ng-click="togglePageSettingsOverlay(7)">
                  <i class="material-icons">timelapse</i> <span><?= Module::t('cmsadmin_settings_time_title'); ?></span>
                  </a>
                  <a class="dropdown-item" ng-click="togglePageSettingsOverlay(4)">
                  <i class="material-icons">content_copy</i> <span><?= Module::t('page_update_actions_deepcopy_title'); ?></span>
                  </a>
                  <a class="dropdown-item" ng-click="togglePageSettingsOverlay(8)">
                  <i class="material-icons">collections</i> <span><?= Module::t('page_update_actions_deepcopyastemplate_title'); ?></span>
                  </a>
                  <a class="dropdown-item" ng-show="!isDraft" ng-click="togglePageSettingsOverlay(5)">
                  <i class="material-icons">home</i> <span><?= Module::t('cmsadmin_settings_homepage_title'); ?></span>
                  </a>
                  <a class="dropdown-item" ng-click="togglePageSettingsOverlay(3)">
                  <i class="material-icons">web</i> <span><?= Module::t('page_update_actions_layout_title'); ?></span>
                  </a>
                  <?php if (Yii::$app->adminuser->canRoute(Module::ROUTE_PAGE_DELETE)) : ?>
                  <a class="dropdown-item" ng-click="togglePageSettingsOverlay(6)">
                  <i class="material-icons">delete</i> <span><?= Module::t('cmsadmin_settings_trashpage_title'); ?></span>
                  </a>
                  <?php endif; ?>
                  </div>
                  </div>
                  </div> */ ?>
            </div>
            <div class="cmsadmin-pages">
                <div class="row">
                    <div class="col" ng-repeat="lang in languagesData" ng-if="AdminLangService.isInSelection(lang.short_code)" ng-controller="NavItemController">
                        <?= $this->render('_navitem', ['canBlockUpdate' => $canBlockUpdate, 'canBlockDelete' => $canBlockDelete, 'canBlockCreate' => $canBlockCreate, 'navItem' => $navItem]); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($canBlockCreate) : ?>
            <div class="col blockholder-column" ng-controller="DroppableBlocksController" ng-class="{'blockholder-column-small' : !isBlockholderSmall}" }>
                <div class="blockholder">
                    <div class="blockholder-search">
                        <input class="blockholder-search-input" id="blockholder-search" ng-model="searchQuery" placeholder="Cerca componenti"/>
                        <label class="blockholder-search-label" for="blockholder-search" ng-show="searchQuery.length == 0">
                            <i class="material-icons">search</i>
                        </label>
                        <span class="blockholder-search-label" ng-show="searchQuery.length > 0" ng-click="searchQuery = ''">
                            <i class="material-icons">clear</i>
                        </span>
                    </div>
                    <div class="blockholder-group blockholder-group-copy-stack" ng-show="copyStack.length > 0 && searchQuery.length == 0">
                        <span class="blockholder-group-title">
                            <i class="material-icons">bookmark</i>
                            <span>
                                <?= Module::t('view_update_blockholder_clipboard') ?>
                                <a class="blockholder-clear-button" ng-click="clearStack()"><i class="material-icons">clear</i></a>
                            </span>
                        </span>
                        <ul class="blockholder-list">
                            <li class="blockholder-item" ng-repeat="stackItem in copyStack" dnd dnd-model="stackItem" dnd-isvalid="true" dnd-drop-disabled dnd-css="{onDrag: 'drag-start', onHover: 'red', onHoverTop: 'red-top', onHoverMiddle: 'red-middle', onHoverBottom: 'red-bottom'}">
                                <i class="material-icons blockholder-icon">{{stackItem.icon}}</i>
                                <span>{{stackItem.name}}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="blockholder-group" ng-class="{'blockholder-group-favorites': item.group.is_fav, 'blockholder-group-toggled': !item.group.toggle_open}" ng-repeat="item in blocksData| orderBy:'groupPosition'">
                        <span class="blockholder-group-title" tooltip tooltip-text="{{item.group.name}}" tooltip-position="left" tooltip-disabled="isBlockholderSmall" ng-click="toggleGroup(item.group)" ng-hide="searchQuery.length > 0">
                            <i class="material-icons" ng-if="item.group.is_fav">favorite</i>
                            <i class="material-icons blockholder-toggle-icon" ng-if="!item.group.is_fav">keyboard_arrow_down</i>
                            <span>{{item.group.name}}</span>
                        </span>
                        <ul class="blockholder-list">
                            <li class="blockholder-item" ng-if="item.group.toggle_open" ng-repeat="block in item.blocks| orderBy:'name' | filter:{name:searchQuery}" dnd dnd-model="block" dnd-isvalid="true" dnd-drop-disabled dnd-css="{onDrag: 'drag-start', onHover: 'red', onHoverTop: 'red-top', onHoverMiddle: 'red-middle', onHoverBottom: 'red-bottom'}">
                                <div tooltip tooltip-preview-url="<?= Url::base(true); ?>/cmsadmin/block/preview?blockId={{block.id}}" tooltip-position="left" tooltip-offset-left="-30" tooltip-disabled="!isPreviewEnabled(block)">
                                    <!--<i class="material-icons blockholder-icon" tooltip tooltip-text="{{block.name}}" tooltip-position="left" tooltip-disabled="isBlockholderSmall">{{block.icon}}</i>-->
                                    <i class="mdi mdi-{{block.icon}} material-icons blockholder-icon" tooltip tooltip-text="{{block.name}}" tooltip-position="left" tooltip-disabled="isBlockholderSmall"></i>
                                    <span>{{block.name}}</span>
                                    <button type="button" class="blockholder-favorite" ng-click="addToFav(block)" ng-if="!item.group.is_fav && !block.favorized">
                                        <i class="material-icons">favorite</i>
                                    </button>
                                    <button type="button" class="blockholder-favorite blockholder-favorite-clear" ng-click="removeFromFav(block)" ng-if="item.group.is_fav">
                                        <i class="material-icons">clear</i>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <button class="blockholder-toggler" type="button" ng-class="{'blockholder-toggler-open' : !isBlockholderSmall}" ng-click="toggleBlockholderSize()">
                        <i class="material-icons">chevron_right</i>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>