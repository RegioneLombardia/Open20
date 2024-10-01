<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\organizzazioni\Module;
use kartik\widgets\FileInput;
use yii\bootstrap\Modal;

Modal::begin([
    'header' => '<h2>' . Module::t('amosorganizzazioni', '#import_organizations') . '</h2>',
    'size' => Modal::SIZE_LARGE,
    'id' => 'modalImport',
    'footer' => Html::button(
        Module::t('amosorganizzazioni', '#import'),
        [
            'class' => 'btn btn-primary',
            'value' => 'import',
            'type' => 'submit',
            'name' => 'submit-import',
            'id' => 'submitImport'
        ]
    )
]);

$linkDownload = Html::a(Module::t('amosorganizzazioni', '#here'), ['download-import-template']);

echo '<br>' . Module::t('amosorganizzazioni', '#message-import-row-1');
echo '<ol>';
echo '<li>' . Module::t('amosorganizzazioni', '#message-import-row-2', ['linkdownload' => $linkDownload]) . '</li>';
echo '<li>' . Module::t('amosorganizzazioni', '#message-import-row-3') . '</li>';
echo '<li>' . Module::t('amosorganizzazioni', '#message-import-row-4') . '</li>';
echo '<li>' . Module::t('amosorganizzazioni', '#message-import-row-5') . '</li>';
echo '</ol>';

echo '<br><label class="control-label">' . Module::t('amosorganizzazioni', '#upload_file') . '</label>';
echo FileInput::widget([
    'name' => 'import-file',
    'pluginOptions' => [
        'showPreview' => false,
        'showCaption' => true,
        'showRemove' => true,
        'showUpload' => false
    ]
]);

Modal::end();
