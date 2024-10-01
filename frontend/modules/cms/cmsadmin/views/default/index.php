<?php
use \luya\cms\admin\Module;
use \luya\admin\Module as AdminModule;

?>
<?= $this->render('_angulardirectives'); ?>

<script type="text/ng-template" id="cmsNavReverse.html">
    <span ng-if="data.is_editable" class="treeview-label treeview-label-page" dnd dnd-model="data" dnd-ondrop="dropItem(dragged,dropped,position)" dnd-isvalid="validItem(hover,dragged)" dnd-css="{onDrag:'drag-dragging',onHover:'drag-hover',onHoverTop:'drag-hover-top',onHoverMiddle:'drag-hover-middle',onHoverBottom:'drag-hover-bottom'}">
        <span class="treeview-icon treeview-icon-collapse" ng-show="(menuData.items | menuparentfilter:catitem.id:data.id).length"  ng-click="toggleItem(data)">
            <i class="material-icons">arrow_drop_down</i>
        </span>
        <span class="treeview-icon treeview-icon-right" ng-if="data.is_home==1">
            <i class="material-icons">home</i>
        </span>
        <span class="treeview-icon treeview-icon-right" ng-if="isLocked('cms_nav_item', data.id)" tooltip tooltip-text="<?= AdminModule::t('locked_info'); ?> ({{getLockedName('cms_nav_item', data.id)}})">
            <i class="material-icons">warning</i>
        </span>
        <span class="treeview-link" alt="id={{data.id}}" title="id={{data.id}}" ng-click="go(data)">
            <span class="google-chrome-font-offset-fix">{{data.title}}</span>
        </span>
    </span>
    <span ng-if="!data.is_editable" class="treeview-label treeview-label-page">
        <span class="treeview-link" alt="id={{data.id}}" title="id={{data.id}}" style="cursor: not-allowed;">
            <span class="google-chrome-font-offset-fix">{{data.title}}</span>
        </span>
    </span>
    <ul class="treeview-items" ng-if="data.has_children && data.toggle_open">
        <li class="treeview-item" ng-class="{'treeview-item-active' : isCurrentElement(data), 'treeview-item-isoffline' : data.is_offline, 'treeview-item-collapsed': !data.toggle_open, 'treeview-item-ishidden': data.is_hidden, 'treeview-item-has-children' : (menuData.items | menuparentfilter:catitem.id:data.id).length}" ng-repeat="data in menuData.items | menuparentfilter:catitem.id:data.id" ng-include="'cmsNavReverse.html'"></li>
    </ul>
</script>

<div class="luya-main" ng-class="{'luya-mainnav-is-open' : isHover}">
    <div class="" ng-controller="CmsMenuTreeController" ng-class="">
        
    </div>
    <div class="luya-content luya-content-cmsadmin mt-0 mb-4" ui-view>
        <div ng-controller="CmsDashboard">
            <div class="card mb-2" ng-repeat="item in dashboard" ng-init="item.isToggled = ($index < 3)">
	            <div class="card-header" ng-click="item.isToggled = !item.isToggled">
	                <span class="card-title">{{item.day * 1000 | date:"EEEE, dd. MMMM"}}</span>
	            </div>
	            <div class="card-body" ng-show="item.isToggled">
	                <div class="timeline timeline-left timeline-compact">
	                    <div class="timeline-item timeline-item-center-point" ng-repeat="(key, log) in item.items">
	                        <i class="material-icons" ng-if="log.is_insertion == 1">note_add</i>
	                        <i class="material-icons" ng-if="log.is_update == 1">create</i>
                            <i class="material-icons" ng-if="log.is_deletion == 1">delete</i>
                            <small class="pr-4"><i>{{log.timestamp * 1000 | date:"HH:mm"}}</i></small>
                            <small class="pr-4">{{ log.user.firstname }} {{log.user.lastname}}</small>
	                        <small><span compile-html ng-bind-html="log.action | trustAsUnsafe"></span></small>
	                    </div>
	                </div>
	            </div>
            </div>
        </div>
    </div>
</div>
