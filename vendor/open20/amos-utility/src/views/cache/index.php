<?php
/**
 * @var $this \yii\web\View
 */

$cleaned = 0;

try {
    $cleaned = Yii::$app->controller->cleanAssetDirs();
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
    return $e->getCode();
}

?>
<center>Cleaned <?= (int) $cleaned; ?> Cache! <?= $cleaned ? 'Great!' : 'Sorry =('; ?></center>
<center style="color:#ff7a19;"><?= $cleaned ? 'Don\'t Be Afraid, May the cache has been recreated in the meantime, but the older has been cleaned anyway' : ''; ?></center>