<?php

use luya\admin\Module as Admin;
use luya\helpers\Url;

$user = Yii::$app->adminuser->getIdentity();
$this->beginPage()
?>
<!DOCTYPE html>
<html ng-app="zaa" ng-controller="LayoutMenuController" ng-strict-di>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title ng-bind-template="<?= Yii::$app->siteTitle; ?> &rsaquo; {{currentItem.alias}}"><?= Yii::$app->siteTitle; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= Url::base(true); ?>/admin" />
    <style type="text/css">
        [ng\:cloak],
        [ng-cloak],
        [data-ng-cloak],
        [x-ng-cloak],
        .ng-cloak,
        .x-ng-cloak {
            display: none !important;
        }

        .dragover {
            background-color: #FF006B !important;
            border: 1px solid white !important;
        }
    </style>
    <?php $this->head(); ?>
</head>


<body ng-cloak flow-prevent-drop class="cms-admin-layout-dxp {{browser}}" ng-class="{'debugToolbarOpen': showDebugBar, 'modal-open' : !AdminClassService.modalStackIsEmpty()}">

    <?php $this->beginBody(); ?>

    <?= $this->render('_angulardirectives'); ?>
    <div class="loading-overlay" ng-if="LuyaLoading.getState()">
        <div class="loading-overlay-content">
            <h3 class="loading-overlay-title">
                {{LuyaLoading.getStateMessage()}}
            </h3>
            <div class="loading-overlay-loader">
                <div class="loading-indicator">
                    <div class="rect1"></div><!--
                -->
                    <div class="rect2"></div><!--
                -->
                    <div class="rect3"></div><!--
                -->
                    <div class="rect4"></div><!--
                -->
                    <div class="rect5"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="luya">

        <div ui-view class="luya-main-wrapper"></div>

        <div class="luyasearch" ng-class="{'luyasearch-open' : searchInputOpen, 'luyasearch-closed': !searchInputOpen, 'luyasearch-toggled': isHover}" zaa-esc="escapeSearchInput()">
            <div class="luyasearch-inner">
                <div class="luyasearch-form form-group">
                    <input id="global-search-input" focus-me="searchInputOpen" ng-model-options="{ debounce: 1000 }" ng-model="searchQuery" type="search" class="luyasearch-input form-control" placeholder="<?= Admin::t('layout_filemanager_search_text'); ?>" />
                    <div class="luyasearch-close" ng-click="closeSearchInput()">
                        <i class="material-icons luyasearch-close-icon">close</i>
                    </div>
                </div>
                <div class="alert alert-info" ng-show="searchResponse==null && searchQuery.length <= 2 && searchQuery.length > 0">
                    <?= Admin::t('layout_search_min_letters'); ?>
                </div>
                <div class="alert alert-info" ng-show="(searchResponse.length == 0 && searchResponse != null) && searchQuery.length > 2">
                    <?= Admin::t('layout_search_no_results'); ?>
                </div>
                <div class="luyasearch-loader" ng-show="searchResponse==null && searchQuery.length > 2">
                    <div class="loading-indicator loading-indicator-small">
                        <div class="rect1"></div><!--
                -->
                        <div class="rect2"></div><!--
                -->
                        <div class="rect3"></div><!--
                -->
                        <div class="rect4"></div><!--
                -->
                        <div class="rect5"></div>
                    </div>
                </div>
                <div class="luyasearch-results">
                    <div class="luyasearch-result" ng-repeat="item in searchResponse">

                        <div class="card" ng-class="{'card-closed': !groupVisibility}">
                            <div class="card-header" ng-click="groupVisibility=!groupVisibility">
                                <span class="material-icons card-toggle-indicator">keyboard_arrow_down</span>
                                <i class="material-icons">{{item.menuItem.icon}}</i>&nbsp;<span>{{item.menuItem.alias}}</span><span class="badge badge-secondary float-right">{{item.data.length}}</span>
                            </div>
                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <table class="table table-hover table-align-middle">
                                        <thead>
                                            <tr ng-repeat="row in item.data | limitTo:1">
                                                <th ng-hide="!item.hideFields.indexOf(k)" ng-repeat="(k,v) in row">{{k}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="row in item.data" ng-click="searchDetailClick(item, row)">
                                                <td ng-hide="!item.hideFields.indexOf(k)" ng-repeat="(k,v) in row">{{v}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="debug shadow" ng-show="showDebugBar" ng-class="{'debug-toggled': isHover}" ng-init="debugTab=1">
            <ul class="nav nav-tabs debug-tabs">
                <li class="nav-item" ng-click="debugTab=1">
                    <span class="nav-link" ng-class="{'active': debugTab==1}">Network</span>
                </li>
                <li class="nav-item" ng-click="debugTab=2">
                    <span class="nav-link" ng-class="{'active': debugTab==2}">Infos</span>
                </li>
                <li class="nav-item" ng-click="debugTab=3">
                    <span class="nav-link" ng-class="{'active': debugTab==3}">Packages</span>
                </li>
                <li class="nav-item" ng-click="debugTab=4">
                    <span class="nav-link" ng-class="{'active': debugTab==4}">OpenAPI</span>
                </li>
                <li class="nav-item" ng-click="showDebugBar=0">
                    <span class="nav-link">
                        <i class="material-icons">close</i>
                    </span>
                </li>
            </ul>
            <div class="debug-panel debug-panel-network" ng-class="{'debug-panel-network-open': debugDetail}" ng-if="debugTab==1">
                <div class="debug-network-items pr-3">
                    <p class="lead">Requests ({{AdminDebugBar.data.length}})<button type="button" ng-click="AdminDebugBar.clear()" class="btn btn-icon mb-3 btn-sm float-right">Clear list <i class="material-icons">clear</i></button></p>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="w-50">URL</th>
                                    <th class="w-25">Status</th>
                                    <th class="w-25">Time (ms)</th>
                                </tr>
                            </thead>
                            <tr ng-repeat="(key, item) in AdminDebugBar.data | reverse" ng-show="item.responseStatus" ng-click="loadDebugDetail(item, key)" style="cursor:pointer;">
                                <td>{{ item.url }}</td>
                                <td>{{ item.responseStatus }}</td>
                                <td>{{ item.parseTime }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="debug-network-detail" ng-show="debugDetail">
                    <div class="table-responsive">
                        <p class="lead">Request<button type="button" ng-click="closeDebugDetail()" class="btn btn-icon mb-3 btn-sm float-right">close <i class="material-icons">close</i></button></p>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th class="w-25" scope="row">URL</th>
                                <td class="w-75">{{debugDetail.url}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Time</th>
                                <td>
                                    <span ng-if="debugDetail.parseTime">{{debugDetail.parseTime}}</span>
                                    <span ng-if="!debugDetail.parseTime">-</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Data</th>
                                <td><code ng-if="debugDetail.requestData">{{debugDetail.requestData | json}}</code><span ng-if="!debugDetail.requestData">-</span>
                                </td>
                            </tr>
                        </table>
                        <p class="lead mt-3">Response</p>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th class="w-25" scope="row">Status</th>
                                <td class="w-75">
                                    <span ng-if="debugDetail.responseStatus">{{debugDetail.responseStatus}}</span>
                                    <span ng-if="!debugDetail.responseStatus">-</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Data</th>
                                <td><code ng-if="debugDetail.responseData">{{debugDetail.responseData | json }}</code><span ng-if="!debugDetail.responseData">-</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="debug-panel" ng-if="debugTab==2">
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered">
                        <thead>
                            <tr>
                                <th><?= Admin::t('layout_debug_table_key'); ?></th>
                                <th><?= Admin::t('layout_debug_table_value'); ?></th>
                            </tr>
                        </thead>
                        <tr>
                            <td><?= Admin::t('layout_debug_luya_version'); ?>:</td>
                            <td><?= \luya\Boot::VERSION; ?></td>
                        </tr>
                        <tr>
                            <td>Vendor:</td>
                            <td><?= strftime("%c", Yii::$app->getPackageInstaller()->getTimestamp()); ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_id'); ?>:</td>
                            <td><?= Yii::$app->id ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_sitetitle'); ?>:</td>
                            <td><?= Yii::$app->siteTitle ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_remotetoken'); ?>:</td>
                            <td><?= $this->context->colorizeValue(Yii::$app->remoteToken, true); ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_assetmanager_forcecopy'); ?>:</td>
                            <td><?= $this->context->colorizeValue(Yii::$app->assetManager->forceCopy); ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_transfer_exceptions'); ?>:</td>
                            <td><?= $this->context->colorizeValue(Yii::$app->errorHandler->transferException); ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_caching'); ?>:</td>
                            <td><?= (Yii::$app->has('cache')) ? Yii::$app->cache->className() : $this->context->colorizeValue(false); ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_yii_debug'); ?>:</td>
                            <td><?= $this->context->colorizeValue(YII_DEBUG); ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_yii_env'); ?>:</td>
                            <td><?= YII_ENV; ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_yii_timezone'); ?>:</td>
                            <td><?= Yii::$app->timeZone; ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_php_timezone'); ?>:</td>
                            <td><?= date_default_timezone_get(); ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_php_ini_memory_limit'); ?>:</td>
                            <td><?= ini_get('memory_limit'); ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_php_ini_max_exec'); ?>:</td>
                            <td><?= ini_get('max_execution_time'); ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_php_ini_post_max_size'); ?>:</td>
                            <td><?= ini_get('post_max_size'); ?></td>
                        </tr>
                        <tr>
                            <td><?= Admin::t('layout_debug_php_ini_upload_max_file'); ?>:</td>
                            <td><?= ini_get('upload_max_filesize'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="debug-panel" ng-if="debugTab==3">
                <div class="row">
                    <div class="col-md-4 mb-3" ng-repeat="(packageName, package) in packages">
                        <div class="card h-100">
                            <div class="card-header">
                                {{ package.package.prettyName }}
                                <a target="_blank" ng-href="{{package.package.sourceUrl}}" class="float-right"><i class="material-icons">link</i></a>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-0">Version:</p>
                                <p class="card-text">{{ package.package.version}}</p>
                                <p class="text-muted mb-0">Bootstraping files:</p>
                                <p class="card-text">{{ package.bootstrap | json }}</p>
                                <p class="text-muted mb-0">Blocks resources:</p>
                                <p class="card-text">{{ package.blocks | json }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="debug-panel" ng-if="debugTab==4">
                <div class="alert alert-info">
                    In order to fetch the latest OpenAPI file either set endpoint to public with <code>publicOpenApi</code> or define and use <code>remoteToken</code>
                    <span class="badge badge-secondary badge--inherit-font-size float-right">BETA</span>
                </div>
                <p>
                    Public OpenAPI File:
                    <span class="badge badge--inherit-font-size badge-<?= $this->context->module->publicOpenApi ? 'success' : 'danger' ?>"><?= Yii::$app->formatter->asBoolean($this->context->module->publicOpenApi); ?></span>
                </p>
                <p>
                    OpenAPI available with Remote Token:
                    <span class="badge badge--inherit-font-size badge-<?= !empty(Yii::$app->remoteToken) ? 'success' : 'danger' ?>"><?= Yii::$app->formatter->asBoolean(Yii::$app->remoteToken); ?></span>
                    <?php if (!empty(Yii::$app->remoteToken)) : ?><span class="badge badge--inherit-font-size badge-info"><?= sha1(Yii::$app->remoteToken); ?></span><?php endif; ?>
                </p>

                <?php if ($this->context->module->publicOpenApi || Yii::$app->remoteToken) : ?>
                    <div class="card">
                        <div class="card-header">Access</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>OpenApi JSON Link</label>
                                <input type="text" readonly value="<?= Url::toRoute(['/admin/api-admin-remote/openapi', 'token' => Yii::$app->remoteToken ? sha1(Yii::$app->remoteToken) : null], true); ?>" class="form-control" />
                            </div>
                            <p class="card-text">
                                <a href="<?= Url::toRoute(['/admin/default/api-doc']); ?>" target="_blank" class="btn btn-primary">Open Documentation</a>
                                <span class="badge badge--inherit-font-size badge-secondary">Authentication is required</span>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="toasts" ng-repeat="item in toastQueue  | filter:{title:''}" ng-class="{'toasts--mainnav-small': !isHover}">
        <div class="modal toasts-modal fade show" ng-if="item.type == 'confirm'" zaa-esc="item.close()" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{item.title}}</h5>
                        <button type="button" class="close" ng-click="item.close()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{item.message}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" ng-click="item.close()"><?= Admin::t('button_abort'); ?></button>
                        <button type="button" class="btn btn-primary" ng-click="item.click()"><?= Admin::t('button_yes'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="toasts-toast" ng-if="item.type != 'confirm'" style="transform: translateY(-{{ ($index * 100) }}%);">
            <div class="alert text-white" ng-click="item.close()" ng-class="{'alert-success': item.type == 'success', 'alert-danger': item.type == 'error', 'alert-warning': item.type == 'warning', 'alert-info': item.type == 'info'}">
                <i class="material-icons toasts-toast-icon" ng-show="item.type == 'success'">check_circle</i>
                <i class="material-icons toasts-toast-icon" ng-show="item.type == 'error'">error_outline</i>
                <i class="material-icons toasts-toast-icon" ng-show="item.type == 'warning'">warning</i>
                <i class="material-icons toasts-toast-icon" ng-show="item.type == 'info'">info_outline</i>
                <span>{{item.message}}</span>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>