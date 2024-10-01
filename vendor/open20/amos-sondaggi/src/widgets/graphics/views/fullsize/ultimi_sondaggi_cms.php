<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\assets\ModuleSondaggiAsset;
use open20\amos\sondaggi\controllers\PubblicazioneController;
use open20\amos\sondaggi\widgets\graphics\WidgetGraphicsUltimiSondaggi;
use yii\data\ActiveDataProvider;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $lista
 * @var WidgetGraphicsUltimiSondaggi $widget
 * @var string $toRefreshSectionId
 */

ModuleSondaggiAsset::register($this);

$listaModels = $lista->getModels();

?>

<?php

$modelLabel = 'sondaggi';

$labelLinkAll = AmosSondaggi::t('amossondaggi', 'Tutti i sondaggi');
$urlLinkAll = '/sondaggi/pubblicazione/all';
$titleLinkAll = AmosSondaggi::t('amossondaggi', 'Visualizza la lista di tutti i sondaggi');

$subTitleSection = Html::tag('p', AmosSondaggi::t('amossondaggi', ''));

$labelCreate = AmosSondaggi::t('amossondaggi', 'Nuovo');
$titleCreate = AmosSondaggi::t('amossondaggi', 'Crea un nuovo sondaggio');
$labelManage = AmosSondaggi::t('amossondaggi', 'Gestisci');
$titleManage = AmosSondaggi::t('amossondaggi', 'Gestisci i sondaggi');
$urlCreate = '/sondaggi/dashboard/create';

$controller = PubblicazioneController::class;
$manageLinks = (method_exists($controller, 'getManageLinks') ? $controller::getManageLinks() : []);

$moduleCwh = \Yii::$app->getModule('cwh');
if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope = $moduleCwh->getCwhScope();
    $isSetScope = (!empty($scope)) ? true : false;
}

$hideCreate = false;
?>

<div class="widget-graphic-cms-bi-less card-<?= $modelLabel ?> container">
    <div class="page-header">
        <?= $this->render(
            "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-less-plugin-header",
            [
                'isGuest' => \Yii::$app->user->isGuest,
                'isSetScope' => $isSetScope,
                'modelLabel' => 'news',
                'titleSection' => $widget->getLabel(),
                'subTitleSection' => $subTitleSection,
                'urlLinkAll' => $hideCreate ? null : $urlLinkAll,
                'labelLinkAll' => $labelLinkAll,
                'titleLinkAll' => $titleLinkAll,
                'hideCreate' => $hideCreate,
                'labelCreate' => $labelCreate,
                'titleCreate' => $titleCreate,
                'labelManage' => $labelManage,
                'titleManage' => $titleManage,
                'urlCreate' => $urlCreate,
                'manageLinks' => $manageLinks,
                'canCreate' => \Yii::$app->user->can('SONDAGGI_CREATE', ['model' => AmosSondaggi::instance()->createModel('Sondaggi')]),
            ]
        );
        ?>
    </div>

    <?php if ($listaModels) { ?>
        <div class="list-view">
            <div>
                <div class="" role="listbox" data-role="list-view">
                    <?php foreach ($listaModels as $sondaggio) : ?>
                        <div>
                            <?= $this->render('@vendor/open20/amos-sondaggi/src/views/pubblicazione/_item', [
                                'model' => $sondaggio,
                                'moduleName' => 'sondaggi'
                            ]) ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

    <?php } ?>
</div>