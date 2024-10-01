<?php
// Utilizzo della sidebar dinamica se esiste il CRUD associato
if(class_exists('open20\cms\dashboard\models\CmsDashSidebarItem')){
    echo $this->render('_dynamic-sidebar-redattore', [
        'openOnLoadPage' => $openOnLoadPage,
        'hideHamburgerMenu' => $hideHamburgerMenuHeaderCheck,
        'currentAsset' => $currentAsset
    ]);
}
