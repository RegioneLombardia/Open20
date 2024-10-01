<?php
use luya\admin\Module as Admin;
use luya\helpers\Url;
?>

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