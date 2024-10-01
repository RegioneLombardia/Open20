<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

if (!empty(\Yii::$app->params['bsVersion']) && \Yii::$app->params['bsVersion'] == '4.x') {
  
} else {
  \open20\amos\cwh\assets\CwhAsset::register($this);
}

?>


<div class='row'>
  <div class='col-xs-12'>{recipientsCheck}</div>
</div>
