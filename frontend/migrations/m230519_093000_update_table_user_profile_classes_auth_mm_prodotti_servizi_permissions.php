<?php

use yii\db\Migration;
/**
 * Class m230519_093000_update_table_user_profile_classes_auth_mm_prodotti_servizi_permissions
 */
class m230519_093000_update_table_user_profile_classes_auth_mm_prodotti_servizi_permissions extends Migration
{
    private $table = '{{%user_profile_classes_auth_mm}}';
    private $caporedattoreRoles = [
        'PRODOTTISERVIZI_ADMIN',
        'PRODOTTIDESTINATARI_ADMIN',
        'PRODOTTISOLUZIONI_ADMIN',
        'PRODOTTIFINALITA_ADMIN'
    ];
    private $redattoreRoles = [
        'PRODOTTISERVIZI_READER'
    ];
    private $standarduserRoles = [
        'PRODOTTISERVIZI_READER'
    ];

    public function safeUp()
    {
        foreach($this->caporedattoreRoles as $role) :
            // user_profile_classes_auth_mm
            $this->insert($this->table,
                [
                    'user_profile_classes_id' => 1,
                    'item_id' => $role,
                ]
            );
        endforeach;

        foreach($this->redattoreRoles as $role) :
            // user_profile_classes_auth_mm
            $this->insert($this->table,
                [
                    'user_profile_classes_id' => 2,
                    'item_id' => $role,
                ]
            );
        endforeach;

        foreach($this->standarduserRoles as $role) :
            // user_profile_classes_auth_mm
            $this->insert($this->table,
                [
                    'user_profile_classes_id' => 3,
                    'item_id' => $role,
                ]
            );
        endforeach;
    }

    public function safeDown()
    {
        $this->execute("SET foreign_key_checks = 0;");

        foreach($this->caporedattoreRoles as $role) :
            // user_profile_classes_auth_mm
            $this->delete($this->table,
                [
                    'user_profile_classes_id' => 1,
                    'item_id' => $role,
                ]
            );
        endforeach;

        foreach($this->redattoreRoles as $role) :
            // user_profile_classes_auth_mm
            $this->delete($this->table,
                [
                    'user_profile_classes_id' => 2,
                    'item_id' => $role,
                ]
            );
        endforeach;

        foreach($this->standarduserRoles as $role) :
            // user_profile_classes_auth_mm
            $this->delete($this->table,
                [
                    'user_profile_classes_id' => 3,
                    'item_id' => $role,
                ]
            );
        endforeach;

        $this->execute("SET foreign_key_checks = 1;");
    }
}