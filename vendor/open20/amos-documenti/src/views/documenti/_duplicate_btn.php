<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\controllers\DocumentiController;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 * @var bool $isInIndex
 * @var string $customClasses
 */

/** @var DocumentiController $appController */
$appController = Yii::$app->controller;
$documentsModule = $appController->documentsModule;

// TODO non rimuovere la riga commentata. Sostituire la riga commentata alla riga successiva quando e se sarà implementata la duplicazione delle cartelle.
//if ($documentsModule->enableContentDuplication && Yii::$app->user->can('DOCUMENTI_UPDATE', ['model' => $model])) {
if ($documentsModule->enableContentDuplication && Yii::$app->user->can('DOCUMENTI_UPDATE', ['model' => $model]) && !$model->isFolder()) {
    if ($documentsModule->enableFolders && $model->isFolder()) {
        $modalDescriptionText = AmosDocumenti::t('amosdocumenti', '#duplicate_content_folder_modal_text');
        $btnText = AmosDocumenti::t('amosdocumenti', '#duplicate_content_folder');
    } else {
        $modalDescriptionText = AmosDocumenti::t('amosdocumenti', '#duplicate_content_document_modal_text');
        $btnText = AmosDocumenti::t('amosdocumenti', '#duplicate_content_document');
    }
    $title = $btnText;
    if ($isInIndex) {
        $btnText = AmosIcons::show('copy', ['class' => '']);
        $classes = (isset($customClasses) ? $customClasses : 'btn btn-tools-secondary');
    } else {
        $classes = (isset($customClasses) ? $customClasses : 'bk-btnImport pull-right btn btn-secondary');
    }
    echo ModalUtility::addConfirmRejectWithModal([
        'modalId' => 'duplicate-content-modal-id-' . $model->id,
        'modalDescriptionText' => $modalDescriptionText,
        'btnText' => $btnText,
        'btnLink' => Url::to(['/documenti/documenti/duplicate-content', 'id' => $model->id]),
        'btnOptions' => [
            'title' => $title,
            'class' => $classes
        ]
    ]);
}

?>
