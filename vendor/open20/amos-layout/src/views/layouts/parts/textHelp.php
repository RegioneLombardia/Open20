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

if (array_key_exists('textHelp', $this->params) && isset($this->params['textHelp']['filename'])) {
    $text = trim($this->renderPhpFile(
            FileHelper::localize($this->context->getViewPath().DIRECTORY_SEPARATOR.'help'.DIRECTORY_SEPARATOR.$this->params['textHelp']['filename'].'.php'),
            $this->params['textHelp']
    ));
    if (!empty($text) && strlen($text) > 0) {
        echo '<div class="text-help-layout col-xs-12">'.$text.'</div>';
    }
}
