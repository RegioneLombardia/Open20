<?php
$this->registerJs("  
        function showModal3() {
            $('#loadingovngstate').show();
        }
    setTimeout(showModal3, 500);
", \yii\web\View::POS_READY);
?>
<div class="loadingovngstate" style="display:none">
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
</div>