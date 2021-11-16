<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

/**
 * Class m170131_163501_translate_community_tables_and_columns
 */
class m170131_163501_translate_community_tables_and_columns extends \yii\db\Migration
{
    // Old table names
    const COMMUNITY_TIPOLOGIA_MM = 'community_tipologia_community_mm';
    const TIPOLOGIA_COMMUNITY = 'tipologia_community';
    const COMMUNITY = 'community';

    const TIPOLOGIA_COMMUNITY_NEWNAME = 'community_types';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute("SET foreign_key_checks = 0;");

        // drop foreign key constraints on mm table
        $this->dropForeignKey('fk_community_tipologia_community_mm10', self::COMMUNITY_TIPOLOGIA_MM);
        $this->dropForeignKey('fk_tipologia_community_community_mm10', self::COMMUNITY_TIPOLOGIA_MM);
        // drop mm table
        $this->dropTable(self::COMMUNITY_TIPOLOGIA_MM);

        //first table in mm relation : rename table and columns
        $this->renameTable(self::TIPOLOGIA_COMMUNITY, self::TIPOLOGIA_COMMUNITY_NEWNAME);
        $this->renameColumn(self::TIPOLOGIA_COMMUNITY_NEWNAME, 'denominazione', 'name');
        $this->renameColumn(self::TIPOLOGIA_COMMUNITY_NEWNAME, 'descrizione', 'description');

        //second table in mm relation : rename table and columns
        $this->renameColumn(self::COMMUNITY, 'denominazione', 'name');
        $this->renameColumn(self::COMMUNITY, 'descrizione', 'description');
        $this->renameColumn(self::COMMUNITY, 'immagine_copertina_id', 'cover_image_id');

        //add column in table 2 references id of of table one
        $this->addColumn(self::COMMUNITY, 'community_type_id', $this->integer()->defaultValue(null)->after('cover_image_id'));
        $this->addForeignKey('fk_community_type_community', self::COMMUNITY, 'community_type_id', self::TIPOLOGIA_COMMUNITY_NEWNAME, 'id');

        $this->execute("SET foreign_key_checks = 1;");

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute("SET foreign_key_checks = 0;");

        // drop foreign key and column on table 2 that references table 1 in mm relation
        $this->dropForeignKey('fk_community_type_community', self::COMMUNITY);
        $this->dropColumn(self::COMMUNITY, 'community_type_id');

        //second table in mm relation : rename table and columns
        $this->renameColumn(self::COMMUNITY, 'name', 'denominazione');
        $this->renameColumn(self::COMMUNITY, 'description', 'descrizione');
        $this->renameColumn(self::COMMUNITY, 'cover_image_id', 'immagine_copertina_id');

        //first table in mm relation : rename table and columns
        $this->renameTable(self::TIPOLOGIA_COMMUNITY_NEWNAME, self::TIPOLOGIA_COMMUNITY);
        $this->renameColumn(self::TIPOLOGIA_COMMUNITY, 'name', 'denominazione');
        $this->renameColumn(self::TIPOLOGIA_COMMUNITY, 'description', 'descrizione');

        //recreate mm table
        $this->createTable(self::COMMUNITY_TIPOLOGIA_MM, [
            'id' => $this->primaryKey(),
            'community_id' => $this->integer()->notNull()->comment('Community ID'),
            'tipologia_community_id' => $this->integer()->notNull()->comment('Tipologia Community ID'),
            'created_at' => $this->dateTime()->null()->defaultValue(null)->comment('Creato il'),
            'updated_at' => $this->dateTime()->null()->defaultValue(null)->comment('Aggiornato il'),
            'deleted_at' => $this->dateTime()->null()->defaultValue(null)->comment('Cancellato il'),
            'created_by' => $this->integer()->null()->defaultValue(null)->comment('Creato da'),
            'updated_by' => $this->integer()->null()->defaultValue(null)->comment('Aggiornato da'),
            'deleted_by' => $this->integer()->null()->defaultValue(null)->comment('Cancellato da')
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

        //recreate foreign keys on mm tables
        $this->addForeignKey('fk_community_tipologia_community_mm10', self::COMMUNITY_TIPOLOGIA_MM, 'community_id', self::COMMUNITY, 'id');
        $this->addForeignKey('fk_tipologia_community_community_mm10', self::COMMUNITY_TIPOLOGIA_MM, 'tipologia_community_id', self::TIPOLOGIA_COMMUNITY, 'id');

        $this->execute("SET foreign_key_checks = 1;");

        return true;
    }


}
