<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\widgets\graphics\views\fullsize
 * @category   CategoryName
 */


use open20\amos\discussioni\AmosDiscussioni;

use open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni;
use yii\data\ActiveDataProvider;
use yii\web\View;
use open20\amos\discussioni\assets\ModuleDiscussioniInterfacciaAsset;
use open20\amos\core\utilities\CurrentUser;

/**
 * @var View $this
 * @var ActiveDataProvider $listaTopic
 * @var WidgetGraphicsUltimeDiscussioni $widget
 * @var string $toRefreshSectionId
 */

$moduleDiscussioni = \Yii::$app->getModule(AmosDiscussioni::getModuleName());
$listaModels = $listaTopic->getModels();
ModuleDiscussioniInterfacciaAsset::register($this);

$userModule = CurrentUser::getUserProfile();
?>

<?php
$modelLabel = 'discussioni';

$urlLinkAll = '/discussioni/discussioni-topic/all-discussions';
$labelLinkAll = AmosDiscussioni::t('amosdiscussioni', 'Tutte le discussioni');
$titleLinkAll = AmosDiscussioni::t('amosdiscussioni', 'Visualizza la lista delle discussioni');

$labelCreate = AmosDiscussioni::t('amosdiscussioni', 'Nuova');
$titleCreate = AmosDiscussioni::t('amosdiscussioni', 'Crea una nuova discussione');
$labelManage = AmosDiscussioni::t('amosdiscussioni', 'Gestisci');
$titleManage = AmosDiscussioni::t('amosdiscussioni', 'Gestisci le discussioni');
$urlCreate = '/discussioni/discussioni-topic/create';

$manageLinks = [];
$controller = \open20\amos\discussioni\controllers\DiscussioniTopicController::class;
if (method_exists($controller, 'getManageLinks')) {
    $manageLinks = $controller::getManageLinks();
}


$moduleCwh = \Yii::$app->getModule('cwh');
if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope = $moduleCwh->getCwhScope();
    $isSetScope = (!empty($scope)) ? true : false;
}

?>
<div class="widget-graphic-cms-bi-less card-<?= $modelLabel ?> container">
    <div class="page-header">
    <?= $this->render(
            "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-less-plugin-header",
            [
                'isGuest' => \Yii::$app->user->isGuest,
                'isSetScope' => $isSetScope,
                'modelLabel' => 'discussioni',
                'titleSection' => $widget->getLabel(),
                'urlLinkAll' => $urlLinkAll,
                'labelLinkAll' => $labelLinkAll,
                'titleLinkAll' => $titleLinkAll,
                'labelCreate' => $labelCreate,
                'titleCreate' => $titleCreate,
                'labelManage' => $labelManage,
                'titleManage' => $titleManage,
                'urlCreate' => $urlCreate,
                'manageLinks' => $manageLinks,
                'canCreate' => \Yii::$app->user->can('DISCUSSIONITOPIC_CREATE', ['model' => AmosDiscussioni::instance()->createModel('DiscussioniTopic')]),
            ]
        );
        ?>
    </div>

<?php if($listaModels){ ?>
    <div class="list-view">
        <div>
            <div class="" role="listbox" data-role="list-view">
                <?php foreach ($listaModels as $singolaDiscussione) : ?>
                    <div>
                        <?= $this->render("@vendor/open20/amos-discussioni/src/views/discussioni-topic/_item", ['model' => $singolaDiscussione]); ?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>

<?php }

/* else{ ?>
    <div class="no-result-message mx-auto">

        <div class="flexbox flexbox-column">
                <p class="h4">Non ci sono contenuti che corrispondono ai tuoi interessi. </p>
                <div>
                    <?php if (CurrentUser::isPlatformGuest()){ ?><!--guest va all'accedi e secondo non si vede -->
                        <a class="btn btn-primary" href="/site/login">sii il primo a scrivere un contenuto</a>
                    <?php }else{ ?><!--loggato: vede entrambe: crea/update-->

                        <a href="/discussioni/discussioni-topic/create" class="btn btn-primary">sii il primo a scrivere un contenuto</a>
                        <a href="/amosadmin/user-profile/update?id=<?=$userModule->id ?>" class="btn btn-secondary"> aggiorna i tuoi interessi </a>

                    <?php } ?>
                </div>
        </div>
    </div>
<?php }

*/ ?>
</div>