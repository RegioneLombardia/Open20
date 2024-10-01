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
$faqTickets = $dataProvider->query->asArray()->all();
$categories = [];
foreach ($faqTickets as $faq) {
    $categories[$faq['ticket_categoria_id']][] = [
        'title' => $faq['domanda'],
        'content' => $faq['risposta'],
    ];
}

$module = \Yii::$app->getModule('ticket');
$disableTicketManagement = (!empty($module) ? $module->disableTicketManagement : false);

?>
<div class="<?= $classModuloBackend ?>">
    <?php if (!$hideBiPluginHeader) : ?>
        <?php
        if ($isGuest) {
            $titleSection = AmosTicket::t('amosticket', 'FAQs');
            $urlLinkAll = '/ticket/assistenza/cerca-faq';
            $labelLinkAll = AmosTicket::t('amosticket', 'Tutte le FAQs');
            $titleLinkAll = \Yii::t('amosticket', 'Visualizza la lista delle FAQs');

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
            $subTitleSection .= Html::tag(
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
        foreach ($categories as $categoryId => $listTicket) {
            $category = \open2\amos\ticket\models\TicketCategorie::findOne($categoryId);
            echo $this->render(
                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-faq-category-accordion',
                [
                    'htmlId' => $categoryId,
                    'catFaqId' => $categoryId,
                    'sectionTitle' => $category->titolo,
                    'showMoreHelp' => !$disableTicketManagement,
                    'linkCtaMoreHelp' => '/ticket/ticket/create?categoriaId=' . $categoryId,
                    'list' => $listTicket
                ]
            );
        } ?>
    </div>
</div>