<?php

use open20\design\Module;
use \yii\helpers\Html;
use open20\design\utility\DesignUtility;
use open20\cms\dashboard\models\CmsDashSidebarItem;

$openOnLoadPage = isset($openOnLoadPage) ? $openOnLoadPage : false;

if ($openOnLoadPage) {
    $sidebarClasses = 'sidebar-redattore affix-parent';
} else {
    $sidebarClasses = 'sidebar-redattore affix-parent sidebar-small';
}
$sidebarItems = CmsDashSidebarItem::getSidebarItems();

$userRoles = array_keys(\Yii::$app->getAuthManager()->getRolesByUser(\Yii::$app->user->id));

$currentUrl = \yii\helpers\Url::current();

$activeUrlDashboard = (strpos($currentUrl, '/dashboards/d1/index') !== false) ? 'active' : '';
$activeUrlManageSidebar = (strpos($currentUrl, '/dashboards/cms-dash-sidebar-item/index') !== false) ? 'active' : '';

$designModule = Yii::$app->getModule(Module::getModuleName());
?>

<?php
$sidebar = '';
$visible = false;
foreach ($sidebarItems as $sidebarItem) {
    $liClass = 'nav-item';
    $liClass = $sidebarItem->class_container ? $liClass . ' ' . $sidebarItem->class_container : $liClass;
    $liId = $sidebarItem->id_container ? $sidebarItem->id_container : 'li-'.$sidebarItem->id;

    $sidebar .= '<li class="' . $liClass . ' JqueryItem-' . $sidebarItem->id . '" id="' . $liId . '" >';
    ?>
    <?php
    // Discriminatore: se il link contiene slash è un link, altrimenti contiene sicuramente backslash quindi è un path

    if (strpos($sidebarItem->link, '\\') !== false) { // WIDGET

        if (DesignUtility::cmsSidebarRedattoreItemCanRender($userRoles, $sidebarItem)) {
            $visible = true;
            $widgetModule = DesignUtility::getWidgetPlugin($sidebarItem->link);
            $widgetModule = \Yii::$app->getModule($widgetModule);
            if (!empty($widgetModule)) {
                $sidebar .= $sidebarItem->link::widget([
                            'sidebarTitle' => $sidebarItem->label,
                            'sidebarMousehoverDescription' => $sidebarItem->description,
                            'sidebarIcon' => $sidebarItem->icon_name
                ]);
            }
        }
    } else { // LINK
        if (DesignUtility::cmsSidebarRedattoreItemCanRender($userRoles, $sidebarItem)) {
            $visible = true;
            $text = '<span class="mdi mdi-' . $sidebarItem->icon_name . ' icon-sidebar"></span><span class="nav-label-link">' . Module::t('amosdesign', $sidebarItem->label) . '</span>';

            $addedOptions = [];
            if ($sidebarItem->link == DesignUtility::JAVASCRIPT_VOID_VALUE) {
                $modalInfo = DesignUtility::cmsSidebarRedattoreGetModalInfoById($sidebarItem->id_container);
                if(!is_null($modalInfo)) {
                    list($idModal, $renderModalView) = $modalInfo;
                    $addedOptions = [
                        'data-toggle' => 'modal',
                        'data-target' => $idModal
                    ];
                    echo $this->render('modals-dynamic-sidebar-redattore' . DIRECTORY_SEPARATOR . $renderModalView);
                }
            }
            $sidebar .= Html::a($text,
                ['/site/to-menu-url', 'url' => $sidebarItem->link],
                \yii\helpers\ArrayHelper::merge(
                        [
                            'class' => "nav-item-link {$sidebarItem->active}",
                            'title' => $sidebarItem->description,
                            'target' => $sidebarItem->isTargetBlank ? '_blank' : '_self',
                        ],
                        $addedOptions
                )
            );
            // Link shortcut
            if ($sidebarItem->link_shortcut) {
                $sidebar .= Html::a('<span class="mdi mdi-plus-circle-outline icon-add-new"></span>',
                                ['/site/to-menu-url', 'url' => $sidebarItem->link_shortcut],
                                [
                                    'class' => 'btn-add-new',
                                    'title' => $sidebarItem->shortcut_description,
                                    'data-toggle' => 'tooltip',
                                    'data-placement' => 'right',
                                    'target' => $sidebarItem->isTargetBlank ? '_blank' : '_self'
                                ]
                );
            }
        }
    }
    ?>
<?php } ?>

<?php
$canAdmin = Yii::$app->user->can('ADMIN');
if ($visible || $canAdmin) {
    ?>
    <nav id="sidebarLeftRedattore" class="<?= $sidebarClasses ?> ">
        <div class="sidebar-nav affix-top">
            <div class="scrollable-nav">
                <ul class="nav sidebar-list">

                    <?php echo $sidebar; ?>
                    <?php if ($canAdmin) : ?>
                        <li class="nav-item">
                            <a href="/dashboards/cms-dash-sidebar-item/index" class="nav-item-link <?= $activeUrlManageSidebar ?>" title="Gestisci Sidebar">
                                <span class="mdi mdi-cog icon-sidebar"></span><span class="nav-label-link"><?= Module::t('amosdesign', 'Gestione') ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

            </div>
            <button onclick="toggleSidebar()" class="btn btn-toggle-sidebar" title="Espandi / Riduci sidebar">
                <span class="mdi mdi-menu-left mdi-24px"></span>
            </button>
        </div>

    </nav>
    <?php
} 