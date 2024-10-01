<?php
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignIcon;


$currentAsset = BootstrapItaliaDesignAsset::register($this);
?>
<?php
    switch($data['icon_type'])
    {
        case 1:
?>
        <?= DesignIcon::show($data['icon_name'], DesignIcon::ICON_BI, 'icon '.$data['icon_class'], $currentAsset)?>

 
<?php
        break;
        case 2:
?>
        <?= DesignIcon::show($data['icon_name'], DesignIcon::ICON_MD, 'icon '.$data['icon_class'], $currentAsset)?>

<?php
        break;
    }
?>