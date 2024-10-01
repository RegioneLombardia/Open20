<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180731_115513_create_site_management_section extends Migration
{
    const TABLE_SECTION = "site_management_section";
    const TABLE_SLIDER = "site_management_slider";
    const TABLE_SLIDER_ELEM = "site_management_slider_elem";


    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_SECTION, true) === null)
        {
            $this->createTable(self::TABLE_SECTION, [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->unique()->notNull()->comment('Section name'),
                'description' => $this->string()->notNull()->comment('Description'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        if ($this->db->schema->getTableSchema(self::TABLE_SLIDER, true) === null)
        {
            $this->createTable(self::TABLE_SLIDER, [
                'id' => Schema::TYPE_PK,
                'section_id' => $this->integer()->notNull()->comment('Section'),
                'title' => $this->string()->comment('Title'),
                'description' => $this->text()->comment('Description'),
                'view_path' => $this->string()->comment('View path'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_site_manage_slider_section_id1', self::TABLE_SLIDER, 'section_id', 'site_management_section', 'id');
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }


        if ($this->db->schema->getTableSchema(self::TABLE_SLIDER_ELEM, true) === null)
        {
            $this->createTable(self::TABLE_SLIDER_ELEM, [
                'id' => Schema::TYPE_PK,
                'slider_id' => $this->integer()->notNull()->comment('Slider'),
                'title' => $this->string()->comment('Title'),
                'description' => $this->text()->comment('Description'),
                'type' => $this->integer(1)->comment('1-IMG, 2-VIDEO'),
                'url_video' => $this->string()->comment('Url video'),
                'order' => $this->integer()->comment('Ordinamento'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_site_manage_slider_slider_id1', self::TABLE_SLIDER_ELEM, 'slider_id', 'site_management_slider', 'id');
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable(self::TABLE_SECTION);
        $this->dropTable(self::TABLE_SLIDER);
        $this->dropTable(self::TABLE_SLIDER_ELEM);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
