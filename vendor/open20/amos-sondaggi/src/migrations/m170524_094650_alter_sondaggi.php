<?php

class m170524_094650_alter_sondaggi extends \yii\db\Migration {

    const TABLE_PERMISSION = '{{%sondaggi_pubblicazione}}';

    public function safeUp() {
       $this->execute('SET FOREIGN_KEY_CHECKS=0;');
       $this->execute("
           
            ALTER TABLE `sondaggi_pubblicazione` ADD `mail_subject` VARCHAR(255) NULL DEFAULT NULL AFTER `entita_id`;
            ALTER TABLE `sondaggi_pubblicazione` ADD `mail_message` TEXT NULL DEFAULT NULL AFTER `mail_subject`;
            ALTER TABLE `sondaggi_pubblicazione` ADD `text_not_compilable` TEXT NULL DEFAULT NULL AFTER `mail_message`;
            ALTER TABLE `sondaggi_pubblicazione` ADD `text_end` TEXT NULL DEFAULT NULL AFTER `text_not_compilable`;
            ALTER TABLE `sondaggi_pubblicazione` ADD `text_end_title` VARCHAR(255) NULL DEFAULT NULL AFTER `text_end`;
            ALTER TABLE `sondaggi_pubblicazione` ADD `text_end_html` INTEGER DEFAULT 0 AFTER `text_end_title`;
            ALTER TABLE `sondaggi_pubblicazione` ADD `text_not_compilable_html` INTEGER DEFAULT 0 AFTER `text_end_html`;

               ");
       $this->execute('SET FOREIGN_KEY_CHECKS=0;');
    }

    public function safeDown() {
        echo "Down() non previsto per il file m170524_094650_alter_sondaggi";
        return false;
    }

}
