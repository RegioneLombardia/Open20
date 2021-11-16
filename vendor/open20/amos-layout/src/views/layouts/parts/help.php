<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use yii\helpers\FileHelper;

/* @var $this \yii\web\View */
?>
<?php
if (array_key_exists('help', $this->params) && isset($this->params['help']['filename'])) {
    echo '<div class="container-help container">';
    echo $this->renderPhpFile(FileHelper::localize($this->context->getViewPath() . DIRECTORY_SEPARATOR . 'help' . DIRECTORY_SEPARATOR . $this->params['help']['filename'] . '.php'));
    echo '</div>';

}

if (array_key_exists('intro', $this->params) && isset($this->params['intro']['filename'])) {
    echo '<div class="container-intro container">';
    echo $this->renderPhpFile(FileHelper::localize($this->context->getViewPath() . DIRECTORY_SEPARATOR . 'intro' . DIRECTORY_SEPARATOR . $this->params['intro']['filename'] . '.php'));
    echo '</div>';
}
?>