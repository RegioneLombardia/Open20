<?php

use yii\widgets\ListView;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;
use open20\design\utility\DesignUtility;
use open2\amos\ticket\AmosTicket;
use Yii;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$isGuest = \Yii::$app->user->isGuest;
?>


<?php
$modelLabel = "Faq";
$hideBiPluginHeader = (strpos($cssClass, 'hide-bi-plugin-header') !== false) ? true : false;
$hideModuleBackendIfEmptyList = (strpos($cssClass, 'hide-module-if-empty-list') !== false && $dataProvider->getTotalCount() <= 0) ? true : false;

$classModuloBackend = 'modulo-backend-' . $modelLabel . ' ' . $cssClass;
if ($hideModuleBackendIfEmptyList) {
    $classModuloBackend .= ' ' . 'd-none';
}

?>
<div class="<?= $classModuloBackend ?>">
    <?php if (!$hideBiPluginHeader) : ?>
        <?php
        if ($isGuest) {
            $titleSection = AmosTicket::t('amosticket', 'FAQs');
            $urlLinkAll = '/ticket/assistenza/cerca-faq';
            $labelLinkAll = AmosTicket::t('amosticket', 'Tutte le FAQs');
            $titleLinkAll = AmosTicket::t('amosticket', 'Visualizza la lista delle FAQs');

            $ctaLoginRegister = Html::a(
                AmosTicket::t('amosticket', 'accedi o registrati alla piattaforma'),
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => AmosTicket::t(
                        'amosticket',
                        'Clicca per accedere o registrarti alla piattaforma {platformName}',
                        ['platformName' => \Yii::$app->name]
                    )
                ]
            );
            $subTitleSection  .= Html::tag(
                'p',
                AmosTicket::t(
                    'amosticket',
                    'Per aprire un ticket di assistenza, {ctaLoginRegister}',
                    ['ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = AmosTicket::t('amosticket', 'FAQs');
            $urlLinkAll = '/ticket/assistenza/cerca-faq';
            $labelLinkAll = AmosTicket::t('amosticket', 'Tutte le FAQs');
            $titleLinkAll = AmosTicket::t('amosticket', 'Visualizza la lista delle FAQs');

            $subTitleSection = Html::tag('p', AmosTicket::t('amosticket', ''));
        }

        $labelCreate = AmosTicket::t('amosticket', 'Nuova');
        $titleCreate = AmosTicket::t('amosticket', 'Crea una nuova FAQ');
        $labelManage = AmosTicket::t('amosticket', 'Gestisci');
        $titleManage = AmosTicket::t('amosticket', 'Gestisci le FAQs');
        $urlCreate = '/ticket/ticket-faq/create';

        $manageLinks = [];
        $controller = \open2\amos\ticket\controllers\base\TicketFaqController::class;
        if (method_exists($controller, 'getManageLinks')) {
            $manageLinks = $controller::getManageLinks();
        }

        $canCreate = \Yii::$app->user->can('TICKETFAQ_CREATE', ['model' => $model]);

        ?>
        <?=
        $this->render(
            '@vendor/open20/design/src/views/layouts/parts/bi-plugin-header',
            [
                'isGuest' => $isGuest,
                'modelLabel' => $modelLabel,
                'titleSection' => $titleSection,
                'subTitleSection' => $subTitleSection,
                'urlLinkAll' => $urlLinkAll,
                'labelLinkAll' => $labelLinkAll,
                'titleLinkAll' => $titleLinkAll,
                'canCreate' => $canCreate,
                'labelCreate' => $labelCreate,
                'titleCreate' => $titleCreate,
                'labelManage' => $labelManage,
                'titleManage' => $titleManage,
                'urlCreate' => $urlCreate,
                'manageLinks' => $manageLinks,
            ]
        );
        ?>
    <?php endif ?>

    <div class="list-<?= $modelLabel ?>-container">
        <?php
        if ($dataProvider->getTotalCount() > 0) {

            $listViewLayout = $this->render('@vendor/open20/design/src/components/yii2/views/listViewLayout');

            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_itemListFaqDesign',
                'viewParams' => [
                    'detailPage' => $detailPage,
                    'viewFields' => $viewFields,
                    'blockItemId' => $blockItemId,
                ],
                'layout' => $listViewLayout,
                'summary' => DesignUtility::getLayoutSummary($withPagination),
                'pager' => DesignUtility::listViewPagerConfig(),
                'itemOptions' => [
                    'tag' => false
                ],
                'options' => [
                    'class' => 'list-view w-100'
                ]
            ]);
        } else { ?>
            <?php if (!$isGuest) : ?>
                <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= AmosTicket::t('amosticket', 'Non ci sono FAQs!') ?></strong></p>
                        <!-- < ?=
                        Html::a(
                            AmosTicket::t('amosticket', 'Clicca qui'),
                            '/discussioni/discussioni-topic/all-discussions',
                            [
                                'title' => AmosTicket::t('amosticket', 'Clicca e scopri tutte le discussioni della piattaforma {platformName}', ['platformName' => \Yii::$app->name]),
                                'class' => 'btn btn-xs btn-primary'
                            ]
                        )
                            . ' ' .
                            AmosTicket::t('amosticket', 'e scopri ora tutte le discussioni di {platformName}', ['platformName' => \Yii::$app->name])
                        ?> -->
                    </div>
                </div>
            <?php else : ?>
                <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= AmosTicket::t('amosticket', 'Non sono presenti FAQs') ?></strong></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php } ?>
    </div>
</div>