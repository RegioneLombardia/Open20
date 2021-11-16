<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\news\AmosNews;
use open20\amos\news\assets\ModuleNewsAsset;
use open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews;
use yii\data\ActiveDataProvider;
use yii\web\View;
use open20\amos\core\utilities\CurrentUser;



ModuleNewsAsset::register($this);

/**
 * @var View $this
 * @var ActiveDataProvider $listaNews
 * @var WidgetGraphicsUltimeNews $widget
 * @var string $toRefreshSectionId
 */
$moduleNews  = \Yii::$app->getModule(AmosNews::getModuleName());
$listaModels = $listaNews->getModels();
$userModule = CurrentUser::getUserProfile();
?>

<?php
$modelLabel = 'news';

if(!\Yii::$app->user->isGuest && \Yii::$app->user->id != Yii::$app->params['platformConfigurations']['guestUserId']){
    $titleSection = 'Notizie';
    $labelCta = 'Visualizza tutte';
    $titleCta = 'Visualizza la lista delle notizie';
    $linkCta = '/news/news/all-news';
    $labelCreate = 'Nuova';
    $titleCreate = 'Crea una nuova notizia';
    $linkCreate = '/news/news/create';
    $labelManage = 'Gestisci';
    $titleManage = 'Gestisci le news';
    $linkManage = '#';
} else {
    $titleSection = 'Ultime notizie';
}

?>
<div class="widget-graphic-cms-bi-less card-<?= $modelLabel ?> container">
    <div class="page-header">
        <?= $this->render(
            "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-plugin-header",
            [
                'titleSection' => $titleSection,
            ]
        );
        ?>
    </div>

<?php if($listaModels){ ?>
    <div class="list-view">
        <div>
            <div class="" role="listbox" data-role="list-view">
                <?php foreach ($listaModels as $singolaNews) : ?>
                    <div>
                        <?= $this->render("@vendor/open20/amos-news/src/views/news/_item", ['model' => $singolaNews]); ?>
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
                                
                        <a href="/news/news/create" class="btn btn-primary">sii il primo a scrivere un contenuto</a>
                        <a href="/amosadmin/user-profile/update?id=<?=$userModule->id ?>" class="btn btn-secondary"> aggiorna i tuoi interessi </a>
                                
                    <?php } ?>
                </div>
        </div>
    </div>
<?php } 

*/?>
</div>