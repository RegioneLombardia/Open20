<?php

use luya\admin\Module as Admin;
use luya\helpers\Url;

$this->registerJs("  
        function showModal() {
            $('#ngmessang').show();
        }
    setTimeout(showModal, 500);
", \yii\web\View::POS_READY);
?>
<div id="ngmessang" style="display:none;">
    <div class="toasts" ng-repeat="item in toastQueue" ng-class="{'toasts--mainnav-small': !isHover}">
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
        <div class="toasts-toast" ng-if="item.type != 'confirm'" style="transform: translateY(-{{ ($index * 100)}}%);">
            <div class="alert text-white" ng-click="item.close()" ng-class="{'alert-success': item.type == 'success', 'alert-danger': item.type == 'error', 'alert-warning': item.type == 'warning', 'alert-info': item.type == 'info'}">
                <i class="material-icons toasts-toast-icon" ng-show="item.type == 'success'">check_circle</i>
                <i class="material-icons toasts-toast-icon" ng-show="item.type == 'error'">error_outline</i>
                <i class="material-icons toasts-toast-icon" ng-show="item.type == 'warning'">warning</i>
                <i class="material-icons toasts-toast-icon" ng-show="item.type == 'info'">info_outline</i>
                <span>{{item.message}}</span>
            </div>
        </div>
    </div>
</div>