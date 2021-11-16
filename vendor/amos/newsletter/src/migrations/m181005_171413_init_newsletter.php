<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m181005_171413_init_newsletter extends Migration
{
    const TABLE_NEWSLETTER = "newsletter";
    const TABLE_NEWSLETTER_TEMPLATE = "newsletter_template";
    const TABLE_NEWSLETTER_TEMPLATE_CONTENT = "newsletter_template_content";
    const TABLE_NEWSLETTER_SECTION = "newsletter_section";
    const TABLE_NEWSLETTER_SECTION_TYPE = "newsletter_section_type";
    const TABLE_NEWSLETTER_SECTION_CONTENT = "newsletter_section_content";
    const TABLE_NEWSLETTER_CONTENT_TYPE = "newsletter_content_type";





    /**
     * @inheritdoc
     */
    public function up()
    {


        if ($this->db->schema->getTableSchema(self::TABLE_NEWSLETTER_TEMPLATE, true) === null)
        {
            $this->createTable(self::TABLE_NEWSLETTER_TEMPLATE, [
                'id' => Schema::TYPE_PK,
                'layout_path' => $this->string()->comment('Layout'),
                'header_section_view_path' => $this->string()->comment('Header'),
                'footer_section_view_path' => $this->string()->comment('Footer'),
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

        if ($this->db->schema->getTableSchema(self::TABLE_NEWSLETTER_TEMPLATE_CONTENT, true) === null)
        {
            $this->createTable(self::TABLE_NEWSLETTER_TEMPLATE_CONTENT, [
                'id' => Schema::TYPE_PK,
                'newsletter_template_id' => $this->integer()->comment('Template'),
                'view_path_column_1' => $this->string()->comment('Column 1'),
                'view_path_column_2' => $this->string()->comment('Column 2'),
                'model_content_classname' => $this->string()->comment('Model content'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_newsletter_template_content_template_id1', self::TABLE_NEWSLETTER_TEMPLATE_CONTENT, 'newsletter_template_id', 'newsletter_template', 'id');
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        if ($this->db->schema->getTableSchema(self::TABLE_NEWSLETTER, true) === null)
        {
            $this->createTable(self::TABLE_NEWSLETTER, [
                'id' => Schema::TYPE_PK,
                'newsletter_template_id' => $this->integer()->notNull()->comment('Template'),
                'subject' => $this->string()->comment('Subject'),
                'text' => $this->text()->comment('Text'),
                'welcome_type' => $this->integer()->comment('Welcome'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_newsletter_template_id1', self::TABLE_NEWSLETTER, 'newsletter_template_id', 'newsletter_template', 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }



        if ($this->db->schema->getTableSchema(self::TABLE_NEWSLETTER_SECTION, true) === null)
        {
            $this->createTable(self::TABLE_NEWSLETTER_SECTION, [
                'id' => Schema::TYPE_PK,
                'newsletter_id' => $this->integer()->notNull()->comment('Template'),
                'title' => $this->string()->comment('Title'),
                'description' => $this->text()->comment('Description'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_newsletter_section_newsletter_id1', self::TABLE_NEWSLETTER_SECTION, 'newsletter_id', 'newsletter', 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }


        if ($this->db->schema->getTableSchema(self::TABLE_NEWSLETTER_CONTENT_TYPE, true) === null)
        {
            $this->createTable(self::TABLE_NEWSLETTER_CONTENT_TYPE, [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->notNull()->comment('Name'),
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

        if ($this->db->schema->getTableSchema(self::TABLE_NEWSLETTER_SECTION_TYPE, true) === null)
        {
            $this->createTable(self::TABLE_NEWSLETTER_SECTION_TYPE, [
                'id' => Schema::TYPE_PK,
                'newsletter_section_id' => $this->integer()->notNull()->comment('Section'),
                'newsletter_content_type_id' => $this->integer()->comment('Type'),
                'model_content' => $this->string()->comment('Model content'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_newsletter_section_type_template_id1', self::TABLE_NEWSLETTER_SECTION_TYPE, 'newsletter_section_id', 'newsletter_section', 'id');
            $this->addForeignKey('fk_newsletter_section_type_id_kid1', self::TABLE_NEWSLETTER_SECTION_TYPE, 'newsletter_content_type_id', 'newsletter_content_type', 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }


        if ($this->db->schema->getTableSchema(self::TABLE_NEWSLETTER_SECTION_CONTENT, true) === null)
        {
            $this->createTable(self::TABLE_NEWSLETTER_SECTION_CONTENT, [
                'id' => Schema::TYPE_PK,
                'newsletter_section_id' => $this->integer()->notNull()->comment('Template'),
                'newsletter_content_type_id' => $this->integer()->comment('Type'),
                'model_content_classname' => $this->string()->comment('Model content'),
                'model_content_id' => $this->integer()->comment('Model content id'),
                'html' => $this->text()->comment('Html'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_newsletter_newsletter_section_id1', self::TABLE_NEWSLETTER_SECTION_CONTENT, 'newsletter_section_id', 'newsletter_section', 'id');
            $this->addForeignKey('fk_newsletter_content_type_id_id1', self::TABLE_NEWSLETTER_SECTION_CONTENT, 'newsletter_content_type_id', 'newsletter_content_type', 'id');

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
        $this->dropTable(self::TABLE_NEWSLETTER_SECTION);
        $this->dropTable(self::TABLE_NEWSLETTER_SECTION_CONTENT);
        $this->dropTable(self::TABLE_NEWSLETTER_SECTION_TYPE);


        $this->dropTable(self::TABLE_NEWSLETTER);
        $this->dropTable(self::TABLE_NEWSLETTER_TEMPLATE);
        $this->dropTable(self::TABLE_NEWSLETTER_TEMPLATE_CONTENT);
        $this->dropTable(self::TABLE_NEWSLETTER_CONTENT_TYPE);


        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
