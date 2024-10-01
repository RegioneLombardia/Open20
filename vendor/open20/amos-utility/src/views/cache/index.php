<?php
/**
 * @var $this \yii\web\View
 */

$cleaned = 0;

try {
    if ($cleanAssets) {
        $cleaned = \Yii::$app->controller->cleanAssetDirs();
    } else {
        $base = \Yii::$app->controller->cleanBaseCache();
    }
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
    return $e->getCode();
}

?>
<?php if (empty($cleanAssets)) { ?>
    <h1>Base Cache</h1>
    <center>Cleaned <?= (int)$base; ?> Cache! <?= $base ? 'Great!' : 'Sorry =('; ?></center>
    <hr/>
<?php } else { ?>
    <h1>Assets Cache</h1>
    <center>Cleaned <?= (int)$cleaned; ?> Cache! <?= $cleaned ? 'Great!' : 'Sorry =('; ?></center>
    <center style="color:#ff7a19;"><?= $cleaned ? 'Don\'t Be Afraid, May the cache has been recreated in the meantime, but the older has been cleaned anyway' : ''; ?></center>
<?php } ?>