<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\luoghi\controllers\base
 */

/** @var yii\web\View $this */
/** @var array $config */
/** @var string $urlApiJs */

//pr($config);

$this->registerJsFile($urlApiJs);

$configForJs = json_encode($config);
$js = <<< JS
    const configOnlyOffice = {$configForJs};
    var docEditor = new DocsAPI.DocEditor("placeholder", configOnlyOffice);
    docEditor.onload = function(){
        docEditor.style.height = docEditor.contentWindow.document.body.scrollHeight + 'px';
    }

JS;
$this->registerJs($js);
?>
<div id="placeholder"></div>