<?php

use yii\db\Migration;
use \open20\amos\favorites\widgets\ListFavoriteUrlsWidget;
/**
 * Class m230214_111500_update_role_and_area_values
 */
class m230315_144600_populate_table_cms_dash_sidebar_item_default_values extends Migration
{
    private $table = '{{%cms_dash_sidebar_item}}';

    public function safeUp()
    {
        $moduleFavorites = \Yii::$app->getModule('favorites');

        $this->insert($this->table,
            [
                'link' => '/dashboards/d1/index',
                'link_shortcut' => null,
                'label' => 'Dashboard',
                'description' => 'Dashboard redattore',
                'icon_name' => 'view-dashboard',
                'isVisible' => 1,
                'isTargetBlank' => 1,
                'position' => 1
            ]);
        $this->insert($this->table,
            [
                'link' => ListFavoriteUrlsWidget::class,
                'link_shortcut' => null,
                'label' => 'Segnalibri',
                'description' => 'Segnalibri',
                'shortcut_description' => 'Segnalibri',
                'icon_name' => 'bookmark',
                'id_container' => 'open-dropdown-favorites',
                'class_container' => null,
                'isVisible' => 1,
                'isTargetBlank' => 1,
                'position' => 2
            ]);
        $this->insert($this->table,
            [
                'link' => '/dashboards/d1/pagine',
                'link_shortcut' => '/dashboards/d1/create-page?container=default',
                'label' => 'Pagine',
                'description' => 'Gestisci Pagine',
                'shortcut_description' => 'Crea Pagina',
                'icon_name' => 'file-document',
                'isVisible' => 1,
                'isTargetBlank' => 1,
                'position' => 3
            ]);
        $this->insert($this->table,
            [
                'link' => '/dashboards/d1/modelli',
                'link_shortcut' => '/dashboards/d1/crea-modello',
                'label' => 'Modelli',
                'description' => 'Gestisci Modelli',
                'shortcut_description' => 'Crea Modello',
                'icon_name' => 'shape',
                'isVisible' => 1,
                'isTargetBlank' => 1,
                'position' => 4
            ]);
        $this->insert($this->table,
            [
                'link' => '/attachments/attach-gallery/single-gallery',
                'link_shortcut' => '/attachments/attach-gallery-image/create?id=1',
                'label' => 'Asset Immagini',
                'description' => 'Asset Immagini',
                'shortcut_description' => 'Carica Immagini',
                'icon_name' => 'image',
                'isVisible' => 1,
                'isTargetBlank' => 1,
                'position' => 5
            ]);
        $this->insert($this->table,
            [
                'link' => '/attachments/attach-databank-file',
                'link_shortcut' => '/attachments/attach-databank-file/create',
                'label' => 'Asset Allegati',
                'description' => 'Asset Allegati',
                'shortcut_description' => 'Carica Allegati',
                'icon_name' => 'paperclip',
                'isVisible' => 1,
                'isTargetBlank' => 1,
                'position' => 6
            ]);
        $this->insert($this->table,
            [
                'link' => '/news/news/redaction-all-news',
                'link_shortcut' => '/news/news/create',
                'label' => 'Asset Notizie',
                'description' => 'Asset Notizie',
                'shortcut_description' => 'Crea Notizia',
                'icon_name' => 'newspaper',
                'isVisible' => 1,
                'isTargetBlank' => 1,
                'position' => 7
            ]);
        $this->insert($this->table,
            [
                'link' => '/metrics/operators',
                'link_shortcut' => null,
                'label' => 'Report',
                'description' => 'Report',
                'icon_name' => 'chart-pie',
                'isVisible' => 1,
                'isTargetBlank' => 1,
                'position' => 8
            ]);
        $this->insert($this->table,
            [
                'link' => '/amosadmin/user-profile/operators',
                'link_shortcut' => null,
                'label' => 'Operatori',
                'description' => 'Amministra Operatori',
                'icon_name' => 'account-group',
                'isVisible' => 1,
                'isTargetBlank' => 1,
                'position' => 9
            ]);
        $this->insert($this->table,
            [
                'link' => 'javascript:void(0);',
                'link_shortcut' => null,
                'label' => 'Gestione portali',
                'description' => 'Gestione portali',
                'icon_name' => 'layers-triple',
                'isVisible' => 1,
                'isTargetBlank' => 0,
                'position' => 10
            ]);
    }

    public function safeDown()
    {
        $this->execute("TRUNCATE {$this->table};");
    }
}