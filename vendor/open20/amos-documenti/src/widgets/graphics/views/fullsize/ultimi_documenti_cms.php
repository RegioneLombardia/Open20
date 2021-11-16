<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\attachments\models\File;
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti;
use yii\data\ActiveDataProvider;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $listaDocumenti
 * @var WidgetGraphicsUltimiDocumenti $widget
 * @var string $toRefreshSectionId
 */


/** @var AmosDocumenti $moduleDocumenti */
$moduleDocumenti  = \Yii::$app->getModule(AmosDocumenti::getModuleName());
$listaModels = $listaDocumenti->getModels();

?>
<?php
$modelLabel = 'documenti';

$titleSection = AmosDocumenti::t('amosdocumenti', 'Documenti');
$urlLinkAll = AmosDocumenti::t('amosdocumenti', 'documenti/documenti/all-documents');
$labelLinkAll = AmosDocumenti::t('amosdocumenti', 'Tutti i documenti');
$titleLinkAll = AmosDocumenti::t('amosdocumenti', 'Visualizza la lista dei documenti');

$labelCreate = AmosDocumenti::t('amosdocumenti', 'Nuovo');
$titleCreate = AmosDocumenti::t('amosdocumenti', 'Crea un nuovo documento');
$labelManage = AmosDocumenti::t('amosdocumenti', 'Gestisci');
$titleManage = AmosDocumenti::t('amosdocumenti', 'Gestisci i documenti');
$urlCreate = AmosDocumenti::t('amosdocumenti', '/documenti/documenti/create');
$urlManage = AmosDocumenti::t('amosdocumenti', '#');

?>
<div class="widget-graphic-cms-bi-less card-<?= $modelLabel ?> container">
    <div class="page-header">
    <?= $this->render(
            "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-plugin-header",
            [
                'isGuest' => \Yii::$app->user->isGuest,
                'modelLabel' => 'news',
                'titleSection' => $titleSection,
                'subTitleSection' => $subTitleSection,
                'urlLinkAll' => $urlLinkAll,
                'labelLinkAll' => $labelLinkAll,
                'titleLinkAll' => $titleLinkAll,
                'labelCreate' => $labelCreate,
                'titleCreate' => $titleCreate,
                'labelManage' => $labelManage,
                'titleManage' => $titleManage,
                'urlCreate' => $urlCreate,
                'urlManage' => $urlManage,
            ]
        );
        ?>
    </div>

    <div class="list-view">
        <div>
            <div class="" role="listbox" data-role="list-view">
                <?php foreach ($listaModels as $singolodocumento) : ?>
                    <div>
                        <?= $this->render("@vendor/open20/amos-documenti/src/views/documenti/_item_document", ['model' => $singolodocumento]); ?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>