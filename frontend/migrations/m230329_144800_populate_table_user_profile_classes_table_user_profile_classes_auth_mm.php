<?php

use yii\db\Migration;
/**
 * Class m230329_144800_populate_table_user_profile_classes_table_user_profile_classes_auth_mm
 */
class m230329_144800_populate_table_user_profile_classes_table_user_profile_classes_auth_mm extends Migration
{
    private $table1 = '{{%user_profile_classes}}';
    private $table2 = '{{%user_profile_classes_auth_mm}}';

    public function safeUp()
    {
        // user_profile_classes
        $this->insert($this->table1,
            [
                'id' => 1,
                'name' => 'Caporedattore',
                'description' => '<p>Caporedattore</p>',
                'code' => 'caporedattore',
                'enabled' => 1
            ]
        );
        $this->insert($this->table1,
            [
                'id' => 2,
                'name' => 'Redattore',
                'description' => '<p>Redattore</p>',
                'code' => 'redattore',
                'enabled' => 1
            ]
        );
        $this->insert($this->table1,
            [
                'id' => 3,
                'name' => 'Utente',
                'description' => '<p>Utente</p>',
                'code' => 'utente',
                'enabled' => 1
            ]
        );

        // user_profile_classes_auth_mm
        $this->insert($this->table2,
            [
                'user_profile_classes_id' => 1,
                'item_id' => 'AMMINISTRATORE_NEWS',
            ]
        );
        $this->insert($this->table2,
            [
                'user_profile_classes_id' => 1,
                'item_id' => 'ATTACH_DATABANK_FILE_ADMINISTRATOR',
            ]
        );
        $this->insert($this->table2,
            [
                'user_profile_classes_id' => 1,
                'item_id' => 'ATTACH_GALLERY_ADMINISTRATOR',
            ]
        );
        $this->insert($this->table2,
            [
                'user_profile_classes_id' => 1,
                'item_id' => 'BASIC_USER',
            ]
        );
        $this->insert($this->table2,
            [
                'user_profile_classes_id' => 1,
                'item_id' => 'CAPOREDATTORECMS',
            ]
        );
        $this->insert($this->table2,
            [
                'user_profile_classes_id' => 1,
                'item_id' => 'CMS_PAGE_EDITOR',
            ]
        );
        $this->insert($this->table2,
            [
                'user_profile_classes_id' => 2,
                'item_id' => 'BASIC_USER',
            ]
        );
        $this->insert($this->table2,
            [
                'user_profile_classes_id' => 2,
                'item_id' => 'CREATORE_NEWS',
            ]
        );
        $this->insert($this->table2,
            [
                'user_profile_classes_id' => 2,
                'item_id' => 'REDATTORECMS',
            ]
        );
    }

    public function safeDown()
    {
        $this->execute("SET foreign_key_checks = 0;");
        $this->truncateTable($this->table1);
        $this->truncateTable($this->table2);
        $this->execute("SET foreign_key_checks = 1;");
    }
}