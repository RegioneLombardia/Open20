<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @licence GPLv3
 * @licence https://opensource.org/proscriptions/gpl-3.0.html GNU General Public Proscription version 3
 *
 * @package amos-invitations
 * @category CategoryName
 */

use \yii\bootstrap\Modal;
use \open20\amos\core\helpers\Html;
use \kartik\widgets\FileInput;
use \yii\base\InvalidConfigException;
use open20\amos\core\forms\ActiveForm;

//$assetBundle = \open20\amos\showcaseprojects\assets\ShowcaseProjectAsset::register($this);
$linkDownloadAsset = '/sondaggi/sondaggi/download-import-file-example';
$form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]);


    Modal::begin([
        'header' => '<h2>' . Yii::t('amossondaggi', 'Importa risposte predefinite') . '</h2>',
        'size' => Modal::SIZE_LARGE,
        'id' => 'modalImport',
        'footer' => Html::button(
            Yii::t('amosshowcaseprojects', 'Import'),
            [
                'class' => 'btn btn-primary',
                'value' => 'import',
                'type' => 'submit',
                'name' => 'submit-import',
                'id' => 'submitImport'
            ]
        ),
    ]);

    $linkDownload = Html::a(Yii::t('amossondaggi', 'qui'), $linkDownloadAsset);
    echo Yii::t('amossondaggi', "L'importazione delle risposte può essere fatta solo utilizzando il file predisposto. Segui i seguenti passi:");
    echo '<ol>';
    echo '<li>' . Yii::t('amossondaggi', 'scarica il file facendo click {linkdownload} e salvalo.', ['linkdownload' => $linkDownload]) . '</li>';
    echo '</ol>';

    echo '<label class="control-label">' . Yii::t('amossondaggi', 'Carica il file') . '</label>';
    echo FileInput::widget([
        'name' => 'import-file',
        'pluginOptions' => [
            'showPreview' => false,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false
        ]
    ]);
    echo $form->field($model, 'sondaggi_domande_id')->hiddenInput()->label(FALSE);
//    echo $form->field($model, 'tipo_domanda')->hiddenInput()->label(FALSE);

    Modal::end();

ActiveForm::end();