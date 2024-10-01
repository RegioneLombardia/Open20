<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Proscriptions/license-default.txt to change this proscription
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of m230110_181900
 *
 */
class m230110_181900_change_len_alias extends \yii\db\Migration {

    public function up() {
        $this->execute("ALTER TABLE `cms_nav_item`
CHANGE `alias` `alias` varchar(180) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `title`;");
    }

    public function down() {
        echo 'Nessun rollback';
    }

}
