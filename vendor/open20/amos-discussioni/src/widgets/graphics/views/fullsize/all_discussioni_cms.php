<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
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

$titleSection = AmosDiscussioni::t('amosdiscussioni', 'Discussioni');
$urlLinkAll = '/discussioni/discussioni-topic/all-discussions';
$labelLinkAll = AmosDiscussioni::t('amosdiscussioni', 'Tutte le discussioni');
$titleLinkAll = AmosDiscussioni::t('amosdiscussioni', 'Visualizza la lista delle discussioni');

$labelCreate = AmosDiscussioni::t('amosdiscussioni', 'Nuova');
$titleCreate = AmosDiscussioni::t('amosdiscussioni', 'Crea una nuova discussione');
$labelManage = AmosDiscussioni::t('amosdiscussioni', 'Gestisci');
$titleManage = AmosDiscussioni::t('amosdiscussioni', 'Gestisci le discussioni');
$urlCreate = '/discussioni/discussioni-topic/create';
$urlManage = '#';

?>

<div class="widget-graphic-cms-bi-less card-<?= $modelLabel ?> container">
    <div class="page-header">
        <?= $this->render(
            "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-less-plugin-header",
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
               // 'canCreateCommunityWidget' => AmosDiscussioni::instance()->canCreateCommunityWidget,
            ]
        );
        ?>
    </div>
</div>

<?php if ($listaModels) { ?>
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

<?php } ?>
