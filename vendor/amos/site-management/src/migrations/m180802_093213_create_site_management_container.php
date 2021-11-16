<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180802_093213_create_site_management_container extends Migration
{
    const TABLE_CONTAINER = "site_management_container";
    const TABLE_TEMPLATE = "site_management_template";
    const TABLE_ELEM = "site_management_element";
    const TABLE_CONT_ELEM_MM = "site_management_container_element_mm";

    const TABLE_ELEM_PUBBLICATION = "site_manage_cont_elem_pubblication";
    const TABLE_ELEM_PUBBLICATION_TYPE = "site_manage_pubblication_type";
    const TABLE_ELEM_PUBBLICATION_CLASS = "site_manage_cont_elem_pubblication_class";
    const TABLE_ELEM_PUBBLICATION_USER_MM = "site_manage_cont_elem_pubblication_user_mm";

    const TABLE_FIELDS_TYPE= 'site_management_fields_type';
    const TABLE_CONT_ELEM_FIELDS_VALUES = 'site_management_container_elem_fields_val';
    const TABLE_TEMPLATE_FIELD =  'site_management_template_fields';







    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_CONTAINER, true) === null)
        {
            $this->createTable(self::TABLE_CONTAINER, [
                'id' => Schema::TYPE_PK,
                'section_id' => $this->integer()->notNull()->comment('Section'),
                'is_masonry' => $this->integer(1)->defaultValue(1)->comment('Is Masonry'),
                'title' => $this->string()->comment('Title'),
                'description' => $this->text()->comment('Description'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_sm_container_section_id1', self::TABLE_CONTAINER, 'section_id', 'site_management_section', 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        if ($this->db->schema->getTableSchema(self::TABLE_TEMPLATE, true) === null)
        {
            $this->createTable(self::TABLE_TEMPLATE, [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->notNull()->comment('Name'),
                'description' => $this->text()->comment('Description'),
                'view_path' => $this->string()->comment('View path'),
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


        if ($this->db->schema->getTableSchema(self::TABLE_ELEM, true) === null)
        {
            $this->createTable(self::TABLE_ELEM, [
                'id' => Schema::TYPE_PK,
                'template_id' => $this->integer()->notNull()->comment('Template'),
                'title' => $this->string()->comment('Title'),
                'description' => $this->text()->comment('Description'),
                'elem_order' => $this->string()->comment('Order'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_sm_cont_elem_template_id1', self::TABLE_ELEM, 'template_id', self::TABLE_TEMPLATE, 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        if ($this->db->schema->getTableSchema(self::TABLE_CONT_ELEM_MM, true) === null)
        {
            $this->createTable(self::TABLE_CONT_ELEM_MM, [
                'id' => Schema::TYPE_PK,
                'container_id' => $this->integer()->notNull()->comment('Container'),
                'element_id' => $this->integer()->notNull()->comment('Element'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_sm_cont_elem_mm_container_id1', self::TABLE_CONT_ELEM_MM, 'container_id', 'site_management_container', 'id');
            $this->addForeignKey('fk_sm_cont_elem_mm_cont_elem_id1', self::TABLE_CONT_ELEM_MM, 'element_id', self::TABLE_ELEM, 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }


        if ($this->db->schema->getTableSchema(self::TABLE_ELEM_PUBBLICATION_TYPE, true) === null)
        {
            $this->createTable(self::TABLE_ELEM_PUBBLICATION_TYPE, [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->notNull()->comment('Type'),
                'description' => $this->string()->comment('Description'),
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

        if ($this->db->schema->getTableSchema(self::TABLE_ELEM_PUBBLICATION, true) === null)
        {
            $this->createTable(self::TABLE_ELEM_PUBBLICATION, [
                'id' => Schema::TYPE_PK,
                'container_id' => $this->integer()->notNull()->comment('Container'),
                'container_elem_mm_id' => $this->integer()->notNull()->comment('Element-container'),
                'pubblication_type_id' => $this->integer()->notNull()->comment('Template'),
                'start_date' => $this->dateTime()->comment('Start date'),
                'end_date' => $this->dateTime()->comment('End date'),
                'start_time' => $this->time()->comment('Start time'),
                'end_time' => $this->dateTime()->comment('End time'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_sm_cont_elem_pubbl_cont_elem_id1', self::TABLE_ELEM_PUBBLICATION, 'container_elem_mm_id', self::TABLE_CONT_ELEM_MM, 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }


        if ($this->db->schema->getTableSchema(self::TABLE_ELEM_PUBBLICATION_CLASS, true) === null)
        {
            $this->createTable(self::TABLE_ELEM_PUBBLICATION_CLASS, [
                'id' => Schema::TYPE_PK,
                'cont_elem_pubblication_id' => $this->integer()->notNull()->comment('Pubblication'),
                'class' => $this->string()->notNull()->comment('Class'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_sm_cont_elem_pubbl_class_pubbl_id1', self::TABLE_ELEM_PUBBLICATION_CLASS, 'cont_elem_pubblication_id', self::TABLE_ELEM_PUBBLICATION, 'id');
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        if ($this->db->schema->getTableSchema(self::TABLE_ELEM_PUBBLICATION_USER_MM, true) === null)
        {
            $this->createTable(self::TABLE_ELEM_PUBBLICATION_USER_MM, [
                'id' => Schema::TYPE_PK,
                'cont_elem_pubblication_id' => $this->integer()->notNull()->comment('Pubblication'),
                'user_id' => $this->integer()->notNull()->comment('User'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_sm_cont_elem_pubbl_usermm_pubbl_id1', self::TABLE_ELEM_PUBBLICATION_USER_MM, 'cont_elem_pubblication_id', self::TABLE_ELEM_PUBBLICATION, 'id');
            $this->addForeignKey('fk_sm_cont_elem_pubbl_usermm_user_id1', self::TABLE_ELEM_PUBBLICATION_USER_MM, 'user_id', 'user', 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        $this->insert(self::TABLE_TEMPLATE, ['id' => 1 , 'name' => 'Simple', 'view_path' => '@vendor/amos/site-management/src/templates/simple']);
        $this->insert(self::TABLE_TEMPLATE, ['id' => 2 , 'name' => 'New', 'view_path' => '@vendor/amos/site-management/src/templates/new']);
        $this->insert(self::TABLE_TEMPLATE, ['id' => 3 , 'name' => 'Month special', 'view_path' => '@vendor/amos/site-management/src/templates/month_special']);
        $this->insert(self::TABLE_TEMPLATE, ['id' => 4 , 'name' => 'HTML base']);
        $this->insert(self::TABLE_TEMPLATE, ['id' => 5 , 'name' => 'News', 'view_path' => '@vendor/amos/site-management/src/templates/news']);


        $this->insert(self::TABLE_ELEM_PUBBLICATION_TYPE, ['id' => 1 , 'name' => 'All users']);
        $this->insert(self::TABLE_ELEM_PUBBLICATION_TYPE, ['id' => 2 , 'name' => 'Selected users']);
        $this->insert(self::TABLE_ELEM_PUBBLICATION_TYPE, ['id' => 3 , 'name' => 'A class of users']);



        if ($this->db->schema->getTableSchema(self::TABLE_FIELDS_TYPE, true) === null)
        {
            $this->createTable(self::TABLE_FIELDS_TYPE, [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->unique()->notNull()->comment('Name'),
                'type' => $this->string()->notNull()->comment('Type'),
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

        if ($this->db->schema->getTableSchema(self::TABLE_CONT_ELEM_FIELDS_VALUES, true) === null)
        {
            $this->createTable(self::TABLE_CONT_ELEM_FIELDS_VALUES, [
                'id' => Schema::TYPE_PK,
                'container_elem_id' => $this->integer()->notNull()->comment('Element'),
                'field_id' => $this->integer()->notNull()->comment('Field'),
                'value' => $this->text()->notNull()->comment('Value'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_sm_cont_elem_field_val_elem_id1', self::TABLE_CONT_ELEM_FIELDS_VALUES, 'container_elem_id', self::TABLE_ELEM, 'id');
            $this->addForeignKey('fk_sm_cont_elem_field_val_field_id1', self::TABLE_CONT_ELEM_FIELDS_VALUES, 'field_id', self::TABLE_FIELDS_TYPE, 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        if ($this->db->schema->getTableSchema(self::TABLE_TEMPLATE_FIELD, true) === null)
        {
            $this->createTable(self::TABLE_TEMPLATE_FIELD, [
                'id' => Schema::TYPE_PK,
                'template_id' => $this->integer()->notNull()->comment('Element'),
                'field_id' => $this->integer()->notNull()->comment('Field'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_sm_template_field_id1', self::TABLE_TEMPLATE_FIELD, 'field_id', self::TABLE_FIELDS_TYPE, 'id');
            $this->addForeignKey('fk_sm_template_template_id1', self::TABLE_TEMPLATE_FIELD, 'template_id', self::TABLE_TEMPLATE, 'id');


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
        $this->dropTable(self::TABLE_CONTAINER);
        $this->dropTable(self::TABLE_TEMPLATE);
        $this->dropTable(self::TABLE_ELEM);
        $this->dropTable(self::TABLE_CONT_ELEM_MM);
        $this->dropTable(self::TABLE_ELEM_PUBBLICATION);
        $this->dropTable(self::TABLE_ELEM_PUBBLICATION_TYPE);
        $this->dropTable(self::TABLE_ELEM_PUBBLICATION_USER_MM);
        $this->dropTable(self::TABLE_ELEM_PUBBLICATION_CLASS);

        $this->dropTable(self::TABLE_TEMPLATE_FIELD);
        $this->dropTable(self::TABLE_FIELDS_TYPE);
        $this->dropTable(self::TABLE_CONT_ELEM_FIELDS_VALUES);





        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
